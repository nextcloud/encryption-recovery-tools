# Changelog

## v28.0.0 (2024-01-19)

* introduce decryption support for Owncloud 7 and later
* introduce decryption support for the end-to-end encryption
* heavily improve the path handling
* introduce unit tests for most of the code
* introduce support for environment variables
* introduce help output for better user experience
* support array of values in `INSTANCEID`
* support array of values in `RECOVERY_PASSWORD`
* support array of values in `SECRET`
* support array of passwords per user in `USER_PASSWORDS`
* prevent execution under Windows
* implement RSA encryption with SHA-256 by hand to not introduce a dependency

## v27.0.0 (2023-07-12)

* The script now supports the updated encryption of Nextcloud 27 release.
* It also supports the increased PBKDF2 iteration count in case the new `hash2` key format is used.

## (2023-01-23)

* The script now also tries to recover files that broke during the execution of `./occ encryption:encrypt-all`.

## (2022-12-28)

* The script now supports the new binary encoding that was introduced with the Nextcloud 25 release.
* Furthermore, the code has been reworked and smaller improvements have been added.

## (2022-07-14)

* The script now includes a PHP-only implementation of RC4 so that files can be decrypted even when the legacy support of OpenSSL v3 is not enabled.
* [@fastlorenzo](https://github.com/fastlorenzo) has provided a patch so that the script now supports even older encrypted files.
* In order to use this feature you have to set the `SUPPORT_MISSING_HEADERS` configuration value to `true` as it may break files that are not encrypted.

## (2021-07-05)

* The script now has improved support for external storages as well as the updated encrypted JSON key format that is introduced with the Nextcloud 21 release.
* It also supports the decryption of single files and a failed encryption can be resumed by starting the script again.

## (2020-08-29)

* The script now has basic support for external storages as well as the encrypted JSON key format that is introduced with the Nextcloud 20 release.
