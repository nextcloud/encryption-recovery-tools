# encryption-recovery-tools

## Server-Side Encryption

### Description

The script located at `./server-side-encryption/recover.php` can recover your precious files if you encrypted them with the **Nextcloud Server-Side Encryption** and still have access to the data directory and the Nextcloud configuration file (`config/config.php`).
It supports the master-key encryption, the user-key encryption and can even use the rescue key if it had been enabled as well as the public sharing key for files that had been publicly shared.

### Configuration

In order to use the script you have to configure the given values below:

* **`DATADIRECTORY`** - this is the location of the data directory of your Nextcloud instance, if you copied or moved your data directory then you have to set this value accordingly, this directory has to exist and contain the typical file structure of Nextcloud
* **`INSTANCEID`** - this is a value from the Nextcloud configuration file, there does not seem to be another way to retrieve this value, you can provide an array of values if you are uncertain which value is correct and all of them will be tried out
* **`SECRET`** - this is a value from the Nextcloud configuration file, there does not seem to be another way to retrieve this value, you can provide an array of values if you are uncertain which value is correct and all of them will be tried out
* **`RECOVERY_PASSWORD`** - this is the password for the recovery key, you can set this value if you activated the recovery feature of your Nextcloud instance, leave this value empty if you did not acticate the recovery feature of your Nextcloud instance, you can provide an array of values if you are uncertain which value is correct and all of them will be tried out
* **`USER_PASSWORDS`** - these are the passwords for the user keys, you have to set these values if you disabled the master key encryption of your Nextcloud instance, you do not have to set these values if you did not disable the master key encryption of your Nextcloud instance, each value represents a (username, password) pair and you can set as many pairs as necessary, you can provide an array of passwords per user if you are uncertain which oasswird is correct and all of them will be tried out
* **`EXTERNAL_STORAGES`** - these are the mount paths of external folders, you have to set these values if you used external storages within your Nextcloud instance, each value represents an (external storage, mount path) pair and you can set as many pairs as necessary, the external storage name has to be written as found in the `DATADIRECTORY/files_encryption/keys/files/` folder, if the external storage belongs to a specific user then the name has to contain the username followed by a slash followed by the external storage name as found in the `DATADIRECTORY/$username/files_encryption/keys/files/` folder, the external storage has to be mounted by yourself and the corresponding mount path has to be set
* **`SUPPORT_MISSING_HEADERS`** - this is a value that tells the script if you have encrypted files without headers, this configuration is only needed if you have data from a VERY old OwnCloud/Nextcloud instance, you probably should not set this value as it will break unencrypted files that may live alongside your encrypted files

### Environment Variables

All configuration values can alternatively be provided through environment variables and superseed the information provided within the script.
Lists like `EXTERNAL_STORAGES` and `USER_PASSWORDS` must be provided as space-separated strings.

It is possible to provide more than on password per user through `USER_PASSWORDS` in case you have several passwords and do not know which of them is correct. All of them will be tried out.

The values `INSTANCEID`, `RECOVERY_PASSWORD` and `SECRET` are handled as space-separated lists in case you have several values and do not know which of them is correct. All of them will be tried out.

### Execution

To execute the script you have to call it in the following way:

```
./server-side-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
```

The following parameters are supported:

* **`<targetdir>`** - this is the target directory where the decrypted files get stored, the target directory has to already exist and should be empty as already-existing files will be skipped, make sure that there is enough space to store all decrypted files in the target directory
* **`<sourcedir>`** - this is the name of the source folder which shall be decrypted, the name of the source folder has to be either absolute or relative to the current working directory, if this parameter is not provided then all files in the data directory will be decrypted
* **`<sourcefile>`** - this is the name of the source file which shall be decrypted, the name of the source file has to be either absolute or relative to the current working directory, if this parameter is not provided then all files in the data directory will be decrypted

The execution may take a lot of time, depending on the power of your computer and on the number and size of your files.
Make sure that the script is able to run without interruption.
As of now it does not have a resume feature.
On servers you can achieve this by starting the script within a `screen` session.

Also, the script currently does **not** support the decryption of files in the trashbin that have been deleted from external storage as Nextcloud creates zero byte files when deleting such a file instead of copying over its actual content.

**Windows users:**
This script will **not** run on Windows. Please use the Windows Subsystem for Linux instead.

## Testing

The compatibility with Nextcloud releases is tested with [PHPUnit](https://phpunit.de).
For every major release there is a separate set of files that has been generated by the corresponding Nextcloud release. To test the support of legacy encryption schemes, additional sets of files have been generated with older Owncloud releases. Due to their size the test data are located in the separate repository [nextcloud/server-side-encryption-testdata](https://github.com/nextcloud/server-side-encryption-testdata).

All test suites can be executed as follows:

```
./phpunit.sh
```

## License

The encryption-recovery-tools are licensed under the GNU Affero General Public License 3.0.
When you contribute content to this repository you acknowledge that you provide your contributions under the GNU Affero General Public License 3.0.

## Origins

The recovery tools were originally developed by [SysEleven](https://www.syseleven.de/) as the [nextcloud-tools](https://github.com/syseleven/nextcloud-tools) project but have since been moved to the [Nextcloud](https://nextcloud.com) Github space.
