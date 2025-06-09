# Nextcloud End-to-End Encryption

## Description

This script can recover your precious files if you encrypted them with the **Nextcloud End-to-End Encryption** and still have access to the data directory and the user mnemonics.

## Security Warning

The main goal of the Nextcloud Encryption Recovery Tools is to recover the contents of encrypted files in case there is a catastrophic failure.
For that reason, the recovery scripts **do not** cryptographically verify the integrity of the files while processing them in order to be able to recover the contents of as many encrypted files as possible.
_(On the contrary, the recovery script intentionally decrypts the AES-GCM protected files in AES-CTR mode to skip the integrity check of the Galois/Counter Mode.)_

## Configuration

In order to use the script you have to configure the given values below:

* **`DATADIRECTORY`** - this is the location of the data directory of your Nextcloud instance, if you copied or moved your data directory then you have to set this value accordingly, this directory has to exist and contain the typical file structure of Nextcloud

* **`USER_MNEMONICS`** - these are the mnemonics for the user keys that have been set by the Nextcloud client when creating the end-to-end encryption keys of the users, each value represents a (username, mnemonic) pair and you can set as many pairs as necessary, you can provide an array of mnemonics per user if you are uncertain which mnemonic is correct and all of them will be tried out

* **`EXTERNAL_STORAGES`** - these are the mount paths of external folders, you have to set these values if you used external storages within your Nextcloud instance, each value represents an (external storage, mount path) pair and you can set as many pairs as necessary, the external storage name has to be written as found in the `DATADIRECTORY/files_encryption/keys/files/` folder, if the external storage belongs to a specific user then the name has to contain the username followed by a slash followed by the external storage name as found in the `DATADIRECTORY/$username/files_encryption/keys/files/` folder, the external storage has to be mounted by yourself and the corresponding mount path has to be set

* **`DEBUG_MODE`** - this is a boolean (`true`|`false`) option to enable debug output that is more verbose than the default output, the debug mode will make the output less readable

* **`DEBUG_MODE_VERBOSE`** - this is a boolean (`true`|`false`) option to enable verbose debug output that is **even more** verbose than the debug output, the verbose debug mode will make the output **even less** readable, to enable `DEBUG_MODE_VERBOSE` you **also** have to enable `DEBUG_MODE`

### Script Source Settings

The configuration can be done directly within the script source.
Scroll down to the `USER CONFIGURATION` section within the script source.
Configuration values set via environment variables take precedence over values set in the script source.

### Environment Variables

All configuration values can alternatively be provided through environment variables and take precedence over settings provided within the script source.
When using environment variables then the following information need to be taken into account:

* Lists like `EXTERNAL_STORAGES` and `USER_MNEMONICS` must be provided as space-separated strings.

* It is possible to provide more than one mnemonic per user through `USER_MNEMONICS` in case you have several mnemonics and do not know which of them is correct.
All of them will be tried out.

## Execution

To execute the script you have to call it in the following way:

```
./end-to-end-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
```

The following parameters are supported:

* **`<targetdir>`** - this is the target directory where the decrypted files get stored, the target directory has to already exist and should be empty as already-existing files will be skipped, make sure that there is enough space to store all decrypted files in the target directory

* **`<sourcedir>`** - this is the name of the source folder which shall be decrypted, the name of the source folder has to be either absolute or relative to the current working directory and the source folder needs to be located within the `DATADIRECTORY`, if this parameter is not provided then all files in the data directory will be decrypted

* **`<sourcefile>`** - this is the name of the source file which shall be decrypted, the name of the source file has to be either absolute or relative to the current working directory and the source file needs to be located within the `DATADIRECTORY`, if this parameter is not provided then all files in the data directory will be decrypted

The execution may take a lot of time, depending on the power of your computer and on the number and size of your files.
Make sure that the script is able to run without interruption.
As of now it does not have a resume feature.
On servers you can achieve this by starting the script within a `screen` session.

**Windows users:**
This script will **not** run on Windows.
Please use the Windows Subsystem for Linux instead.
