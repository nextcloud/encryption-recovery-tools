# Changelog

## v??.?.? (????-??-??)

* [Fix swapped GMP and ZLIB error messages (#64)](https://github.com/nextcloud/encryption-recovery-tools/pull/64)
* [add skipped integrity checking to README (#65)](https://github.com/nextcloud/encryption-recovery-tools/pull/65)
* [fix code indentations (#66)](https://github.com/nextcloud/encryption-recovery-tools/pull/66)

## v31.0.0 (2025-03-16)

* [add E2E improvements and Nextcloud31 test (#62)](https://github.com/nextcloud/encryption-recovery-tools/pull/62)

## v30.0.0 (2025-03-16)

* [add support for metadata format version 2.0 (#51)](https://github.com/nextcloud/encryption-recovery-tools/pull/51)
* [add skip\_files tests (#53)](https://github.com/nextcloud/encryption-recovery-tools/pull/53)
* [update CHANGELOG.md (#54)](https://github.com/nextcloud/encryption-recovery-tools/pull/54)
* [increase decryption block size (#55)](https://github.com/nextcloud/encryption-recovery-tools/pull/55)
* [improve config type checks (#58)](https://github.com/nextcloud/encryption-recovery-tools/pull/58)
* [add Nextcloud30 test (#60)](https://github.com/nextcloud/encryption-recovery-tools/pull/60)

## v29.0.0 (2024-05-31)

* [improve description of how to configure the recover.php scripts (#41)](https://github.com/nextcloud/encryption-recovery-tools/pull/41)
* [fix typo (#43)](https://github.com/nextcloud/encryption-recovery-tools/pull/43)
* [be more verbose on startup (#44)](https://github.com/nextcloud/encryption-recovery-tools/pull/44)
* [document debug mode (#45)](https://github.com/nextcloud/encryption-recovery-tools/pull/45)
* [align CHANGELOG.md with the release notes (#46)](https://github.com/nextcloud/encryption-recovery-tools/pull/46)
* [fix another typo (#47)](https://github.com/nextcloud/encryption-recovery-tools/pull/47)
* [improve startup sequence (#48)](https://github.com/nextcloud/encryption-recovery-tools/pull/48)
* [Nextcloud 29 release (#49)](https://github.com/nextcloud/encryption-recovery-tools/pull/49)
* [add Nextcloud29 test (#50)](https://github.com/nextcloud/encryption-recovery-tools/pull/50)

## v28.0.0 (2024-01-19)

* [Introduce tests (#9)](https://github.com/nextcloud/encryption-recovery-tools/pull/9)
* [add link of the test data repository to the readme (#11)](https://github.com/nextcloud/encryption-recovery-tools/pull/11)
* [heavily improve path handling and restructure code (#12)](https://github.com/nextcloud/encryption-recovery-tools/pull/12)
* [fix help text and README (#13)](https://github.com/nextcloud/encryption-recovery-tools/pull/13)
* [print configuration to verbose debug log (#15)](https://github.com/nextcloud/encryption-recovery-tools/pull/15)
* [Move testdata (#16)](https://github.com/nextcloud/encryption-recovery-tools/pull/16)
* [improve decryptPrivateKey (#17)](https://github.com/nextcloud/encryption-recovery-tools/pull/17)
* [support decryption infix #(18)](https://github.com/nextcloud/encryption-recovery-tools/pull/18)
* [support several values for INSTANCEID, SECRET, RECOVERY\_PASSWORD and USER\_PASSWORDS (#19)](https://github.com/nextcloud/encryption-recovery-tools/pull/19)
* [fix typo (#21)](https://github.com/nextcloud/encryption-recovery-tools/pull/21)
* [introduce end-to-end encryption support (#23)](https://github.com/nextcloud/encryption-recovery-tools/pull/23)
* [Fix default (#24)](https://github.com/nextcloud/encryption-recovery-tools/pull/24)
* [fix license text (#25)](https://github.com/nextcloud/encryption-recovery-tools/pull/25)
* [implement RSA decryption by hand to not be dependent on phpseclib (#26)](https://github.com/nextcloud/encryption-recovery-tools/pull/26)
* [fix .gitattributes (#28)](https://github.com/nextcloud/encryption-recovery-tools/pull/28)
* [support the decryption with multiple filekeys (#31)](https://github.com/nextcloud/encryption-recovery-tools/pull/31)
* [fix debug message typo (#33)](https://github.com/nextcloud/encryption-recovery-tools/pull/33)
* [fix description of sourcedir and sourcefile (#34)](https://github.com/nextcloud/encryption-recovery-tools/pull/34)
* [fix comment typo (#35)](https://github.com/nextcloud/encryption-recovery-tools/pull/35)
* [fix sonarcloud findings (#36)](https://github.com/nextcloud/encryption-recovery-tools/pull/36)
* [verified support for Nextcloud 28.0.0 (#37)](https://github.com/nextcloud/encryption-recovery-tools/pull/37)
* [update readme to 28.0.0 (#38)](https://github.com/nextcloud/encryption-recovery-tools/pull/38)

## v27.0.0 (2023-07-12)

* [updated readme and renamed rescue script (#2)](https://github.com/nextcloud/encryption-recovery-tools/pull/2)
* [Fix typo in documentation (#3)](https://github.com/nextcloud/encryption-recovery-tools/pull/3)
* [fixed typo and switched to one-sentence-per-line style in README.md (#6)](https://github.com/nextcloud/encryption-recovery-tools/pull/6)
* [overhaul server-side-encryption support (#7)](https://github.com/nextcloud/encryption-recovery-tools/pull/7)
* [fix PBKDF2 iteration selection (#8)](https://github.com/nextcloud/encryption-recovery-tools/pull/8)

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
