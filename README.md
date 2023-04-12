# encryption-recovery-tools

## Server-Side Encryption

### Introduction

This script located at `./server-side-encryption/recover.php` can save your precious files in cases where you encrypted them with the **Nextcloud Server Side Encryption** and still have access to the data directory and the Nextcloud configuration file (`config/config.php`). This script is able to decrypt locally stored files within the data directory. It supports master-key encrypted files, user-key encrypted files and can also use a rescue key (if enabled) and the public sharing key if files had been publicly shared.

### Updates

**2023-01-23:** The script now also tries to recover files that broke during the execution of `./occ encryption:encrypt-all`.

**2022-12-28:** The script now supports the new binary encoding that was introduced with the Nextcloud 25 release. Furthermore, the code has been reworked and smaller improvements have been added.

**2022-07-14:** The script now includes a PHP-only implementation of RC4 so that files can be decrypted even when the legacy support of OpenSSL v3 is not enabled.

**2022-07-14:** [@fastlorenzo](https://github.com/fastlorenzo) has provided a patch so that the script now supports even older encrypted files. In order to use this feature you have to set the `SUPPORT_MISSING_HEADERS` configuration value to `true` as it may break files that are not encrypted.

**2021-07-05:** The script now has improved support for external storages as well as the updated encrypted JSON key format that is introduced with the Nextcloud 21 release. It also supports the decryption of single files and a failed encryption can be resumed by starting the script again.

**2020-08-29:** The script now has basic support for external storages as well as the encrypted JSON key format that is introduced with the Nextcloud 20 release.

### Configuration

#### Nextcloud Definitions

The Nextcloud definitions are values that you have to copy from the Nextcloud configuration file (`config/config.php`). The names of the values are equal to the ones found in the Nextcloud configuration file.

* **`DATADIRECTORY`** - this is the location of the data directory of your Nextcloud instance, if you copied or moved your data directory then you have to set this value accordingly, this directory has to exist and contain the typical file structure of Nextcloud
* **`INSTANCEID`** - this is a value from the Nextcloud configuration file, there does not seem to be another way to retrieve this value
* **`SECRET`** - this is a value from the Nextcloud configuration file, there does not seem to be another way to retrieve this value

#### Custom Definitions

The custom definitions define how the script works internally. These are the supported configuration values:

* **`RECOVERY_PASSWORD`** - this is the password for the recovery key, you can set this value if you activated the recovery feature of your Nextcloud instance, leave this value empty if you did not acticate the recovery feature of your Nextcloud instance
* **`USER_PASSWORDS`** - these are the passwords for the user keys, you have to set these values if you disabled the master key encryption of your Nextcloud instance, you do not have to set these values if you did not disable the master key encryption of your Nextcloud instance, each value represents a (username, password) pair and you can set as many pairs as necessary
* **`EXTERNAL_STORAGES`** - these are the mount paths of external folders, you have to set these values if you used external storages within your Nextcloud instance, each value represents an (external storage, mount path) pair and you can set as many pairs as necessary, the external storage name has to be written as found in the `DATADIRECTORY/files_encryption/keys/files/` folder, if the external storage belongs to a specific user then the name has to contain the username followed by a slash followed by the external storage name as found in the `DATADIRECTORY/$username/files_encryption/keys/files/` folder, the external storage has to be mounted by yourself and the corresponding mount path has to be set
* **`SUPPORT_MISSING_HEADERS`** - this is a value that tells the script if you have encrypted files without headers, this configuration is only needed if you have data from a VERY old OwnCloud/Nextcloud instance, you probably should not set this value as it will break unencrypted files that may live alongside your encrypted files

#### Execution

To execute the script you have to call it in the following way:

```
./server-side-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
```

* **`<targetdir>`** - this is the target directory where the decrypted files get stored, the target directory has to already exist and should be empty as already-existing files will be skipped, make sure that there is enough space to store all decrypted files in the target directory
* **`<sourcedir>`** - this is the name of the source folder which shall be decrypted, the name of the source folder has to be either absolute or relative to the `DATADIRECTORY, if this parameter is not provided then all files in the data directory will be decrypted
* **`<sourcefile>`** - this is the name of the source file which shall be decrypted, the name of the source file has to be either absolute or relative to the `DATADIRECTORY`, if this parameter is not provided then all files in the data directory will be decrypted

The execution may take a lot of time, depending on the power of your computer and on the number and size of your files. Make sure that the script is able to run without interruption. As of now it does not have a resume feature. On servers you can achieve this by starting the script within a `screen` session.

Also, the script currently does **not** support the decryption of files in the trashbin that have been deleted from external storage as Nextcloud creates zero byte files when deleting such a file instead of copying over its actual content.

**Windows users:** This script heavily relies on pattern matching which assumes that forward slashes (`/`) are used as the path separators instead of backslashes (`/`). When providing paths to the script either in the configuration or through the command line then please make sure to replace all backslashes with forward slashes.

## License

These tools are licensed under the GNU Affero General Public License 3.0. When you contribute content to this repository you acknowledge that you provide your contributions under the GNU Affero General Public License 3.0.

## Origins

The recovery tools were originally developed by [SysEleven](https://www.syseleven.de/) as the [nextcloud-tools](https://github.com/syseleven/nextcloud-tools) project but have since been moved to the [Nextcloud](https://nextcloud.com) Github space.
