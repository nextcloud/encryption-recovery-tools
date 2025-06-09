#!/usr/bin/env php
<?php

	# ./end-to-end-encryption/recover.php
	#
	# Copyright (c) 2023-2025, Yahe <hello@yahe.sh>
	# Copyright (c) 2019-2023, SysEleven GmbH
	# All rights reserved.
	#
	#
	# usage:
	# ======
	#
	# ./end-to-end-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
	#
	#
	# description:
	# ============
	#
	# This script can recover your precious files if you encrypted them with the
	# Nextcloud End-to-End Encryption and still have access to the data directory
	# and the user mnemonics.
	#
	#
	# configuration:
	# ==============
	#
	# In order to use the script you have to configure the given values below:
	#
	# DATADIRECTORY           this is the location of the data directory of your
	# (REQUIRED)              Nextcloud instance, if you copied or moved your data
	#                         directory then you have to set this value accordingly,
	#                         this directory has to exist and contain the typical file
	#                         structure of Nextcloud
	#
	# USER_MNEMONICS          these are the mnemonics for the user keys that have been
	# (REQUIRED)              set by the Nextcloud client when creating the end-to-end
	#                         encryption keys of the users, each value represents a
	#                         (username, mnemonic) pair and you can set as many pairs
	#                         as necessary, you can provide an array of mnemonics per
	#                         user if you are uncertain which mnemonic is correct and
	#                         all of them will be tried out
	#
	#                         Example: if the username was "beispiel" and the mnemonic
	#                                  of that user was "example" then the value has
	#                                  to be set as:
	#
	#                                  config("USER_MNEMONICS",
	#                                         ["beispiel" => "example"]);
	#
	# EXTERNAL_STORAGES       these are the mount paths of external folders, you have
	# (OPTIONAL)              to set these values if you used external storages within
	#                         your Nextcloud instance, each value represents an
	#                         (external storage, mount path) pair and you can set as
	#                         many pairs as necessary, the external storage name has
	#                         to be written as found in the
	#                         "DATADIRECTORY/files_encryption/keys/files/" folder, if
	#                         the external storage belongs to a specific user then the
	#                         name has to contain the username followed by a slash
	#                         followed by the external storage name as found in the
	#                         "DATADIRECTORY/$username/files_encryption/keys/files/"
	#                         folder, the external storage has to be mounted by
	#                         yourself and the corresponding mount path has to be set
	#
	#                         Example: if the external storage name was "sftp" and you
	#                                  mounted the corresponding SFTP folder as
	#                                  "/mnt/sshfs" then the value has to be set as:
	#
	#                                  config("EXTERNAL_STORAGES",
	#                                         ["sftp" => "/mnt/sshfs"]);
	#
	#                         Example: if the external storage name was "sftp", the
	#                                  external storage belonged to the user "admin"
	#                                  and you mounted the corresponding SFTP folder
	#                                  as "/mnt/sshfs" then the value has to be set
	#                                  as:
	#
	#                                  config("EXTERNAL_STORAGES",
	#                                         ["admin/sftp" => "/mnt/sshfs"]);
	#
	# DEBUG_MODE              this is a boolean (true|false) option to enable debug
	# (OPTIONAL)              output that is more verbose than the default output,
	#                         the debug mode will make the output less readable
	#
	# DEBUG_MODE_VERBOSE      this is a boolean (true|false) option to enable verbose
	# (OPTIONAL)              debug output that is even more verbose than the debug
	#                         output, the verbose debug mode will make the output even
	#                         less readable, to enable DEBUG_MODE_VERBOSE you also
	#                         have to enable DEBUG_MODE
	#
	#
	# script source settings:
	# =======================
	#
	# The configuration can be done directly within the script source. Scroll down to
	# the "USER CONFIGURATION" section within the script source. Configuration values
	# set via environment variables take precedence over values set in the script
	# source.
	#
	#
	# environment variables:
	# ======================
	#
	# All configuration values can alternatively be provided through environment
	# variables and take precedence over settings provided within the script source.
	# When using environment variables then the following information need to be taken
	# into account:
	#
	# * Lists like  EXTERNAL_STORAGES and USER_MNEMONICS must be provided as
	#   space-separated strings.
	#
	#   Example: if two user mnemonicss shall be provided through an environment
	#            variable then the corresponding value has to be set as:
	#
	#            USER_MNEMONICS="user1=mnemonic1 user2=mnemonic2"
	#
	# * It is possible to provide more than one mnemonic per user through
	#   USER_MNEMONICS in case you have several mnemonicss and do not know which of
	#   them is correct. All of them will be tried out.
	#
	#   Example: if two mnemonics for the same user shall be provided through an
	#            environment variable then the corresponding value has to be set as:
	#
	#            USER_MNEMONICS="user=mnemonic1 user=mnemonic2"
	#
	#
	# execution:
	# ==========
	#
	# To execute the script you have to call it in the following way:
	#
	# ./end-to-end-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
	#
	# The following parameters are supported:
	#
	# <targetdir>  this is the target directory where the decrypted files get stored,
	# (REQUIRED)   the target directory has to already exist and should be empty as
	#              already-existing files will be skipped, make sure that there is
	#              enough space to store all decrypted files in the target directory
	#
	# <sourcedir>  this is the name of the source folder which shall be decrypted, the
	# (OPTIONAL)   name of the source folder has to be either absolute or relative to
	#              the current working directory and the source folder needs to be
	#              located within the DATADIRECTORY, if this parameter is not provided
	#              then all files in the data directory will be decrypted
	#
	# <sourcefile> this is the name of the source file which shall be decrypted, the
	# (OPTIONAL)   name of the source file has to be either absolute or relative to
	#              the current working directory and the source file needs to be
	#              located within the DATADIRECTORY, if this parameter is not provided
	#              then all files in the data directory will be decrypted
	#
	# The execution may take a lot of time, depending on the power of your computer
	# and on the number and size of your files. Make sure that the script is able to
	# run without interruption. As of now it does not have a resume feature. On
	# servers you can achieve this by starting the script within a screen session.
	#
	# Windows users: This script will not run on Windows. Please use the Windows
	#                Subsystem for Linux instead.

	// ===== USER CONFIGURATION =====

	// nextcloud definitions - you can get these values from `config/config.php`
	config("DATADIRECTORY", "");

	// user mnemonic definition,
	// replace "username" with the actual usernames
	// and "mnemonic" with the actual mnemonics,
	// you can add or remove entries as necessary
	config("USER_MNEMONICS", ["username" => "mnemonic",
	                          "username" => "mnemonic",
	                          "username" => "mnemonic"]);

	// external storage definition,
	// replace "storage" with the actual external storage names
	// and "/mountpath" with the actual external storage mount paths,
	// you can add or remove entries as necessary
	// config("EXTERNAL_STORAGES", ["storage" => "/mountpath",
	//                              "storage" => "/mountpath",
	//                              "storage" => "/mountpath"]);

	// debug mode definitions
	// config("DEBUG_MODE",         false);
	// config("DEBUG_MODE_VERBOSE", false);

	##### DO NOT EDIT BELOW THIS LINE #####

	// ===== SYSTEM DEFINITIONS =====

	// encryption definitions
	config("BLOCKSIZE", 8192);
	config("TAGSIZE",     16);

	// prefix of decrypted external storages
	config("EXTERNAL_PREFIX", "EXTERNAL_");

	// file entries
	config("FILE_FILE",          "file");
	config("FILE_NAME",          "name");
	config("FILE_NAME_RAW",      "name_raw");
	config("FILE_TRASHBIN",      "trashbin");
	config("FILE_TRASHBIN_TIME", "trashbin_time");
	config("FILE_USERNAME",      "username");
	config("FILE_VERSION",       "version");
	config("FILE_VERSION_TIME",  "version_number");

	// key entries
	config("KEY_FILE",      "file");
	config("KEY_MNEMONICS", "mnemonics");
	config("KEY_NAME",      "name");

	// intermediate entries
	config("INTERMEDIATE_FILENAME", "filename");
	config("INTERMEDIATE_KEY",      "key");
	config("INTERMEDIATE_NONCE",	"nonce");

	// metadata entries
	config("METADATA_CHECKSUM",             "checksum");
	config("METADATA_CIPHERTEXT",           "ciphertext");
	config("METADATA_DIRECTORY_A",          "httpd/unix-directory");
	config("METADATA_DIRECTORY_B",          "inode/directory");
	config("METADATA_ENCRYPTED",            "encrypted");
	config("METADATA_ENCRYPTEDMETADATAKEY", "encryptedMetadataKey");
	config("METADATA_FILENAME",             "filename");
	config("METADATA_FILES",                "files");
	config("METADATA_FOLDERS",              "folders");
	config("METADATA_IV",                   "initializationVector");
	config("METADATA_KEY",                  "key");
	config("METADATA_METADATA",             "metadata");
	config("METADATA_METADATAKEY",          "metadataKey");
	config("METADATA_METADATAKEYS",         "metadataKeys");
	config("METADATA_MIMETYPE",             "mimetype");
	config("METADATA_NONCE",                "nonce");
	config("METADATA_TAG",                  "authenticationTag");
	config("METADATA_USERID",               "userId");
	config("METADATA_USERS",                "users");
	config("METADATA_VERSION",              "version");

	config("VERSION_1",  1);
	config("VERSION_12", 1.2);
	config("VERSION_20", "2.0");

	// ===== HELPER FUNCTIONS =====

	// only define a constant if it does not exist
	function config($key, $value) {
		if (!defined($key)) {
			// overwrite config with environment variable if it is set
			if (false !== getenv($key)) {
				// handle specific environment variables differently
				switch ($key) {
					// handle as integers
					case "BLOCKSIZE":
					case "TAGSIZE":
					case "VERSION_1":
						$tmp = filter_var(getenv($key), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
						if ((null !== $tmp) && (0 < $tmp)) {
							$value = $tmp;
						}
						break;

					// handle as arrays
					case "EXTERNAL_STORAGES":
						$value   = [];
						$entries = explode(" ", getenv($key));
						foreach ($entries as $entry) {
							if (false !== strpos($entry, "=")) {
								$left         = substr($entry, 0, strpos($entry, "="));
								$right        = substr($entry, strpos($entry, "=")+1);
								$value[$left] = $right;
							}
						}
						break;

					// handle as booleans
					case "DEBUG_MODE":
					case "DEBUG_MODE_VERBOSE":
						$tmp = filter_var(getenv($key), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
						if (null !== $tmp) {
							$value = $tmp;
						}
						break;

					// handle user mnemonics specifically
					case "USER_MNEMONICS":
						$value   = [];
						$entries = explode(" ", getenv($key));
						foreach ($entries as $entry) {
							if (false !== strpos($entry, "=")) {
								$left  = substr($entry, 0, strpos($entry, "="));
								$right = substr($entry, strpos($entry, "=")+1);
								if (array_key_exists($left, $value)) {
									$value[$left][] = $right;
								} else {
									$value[$left] = [$right];
								}
							}
						}
						break;

					// handle as float
					case "VERSION_12":
						$tmp = filter_var(getenv($key), FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
						if ((null !== $tmp) && (0 < $tmp)) {
							$value = $tmp;
						}
						break;

					default:
						$value = getenv($key);
				}
			}

			// normalize values
			switch ($key) {
				case "DATADIRECTORY":
					$value = normalizePath($value);
					break;

				case "EXTERNAL_STORAGES":
					foreach ($value as $name => $path) {
						$value[$name] = normalizePath($path);
					}
					break;

				case "USER_MNEMONICS":
					$value = array_change_key_case($value);
					foreach ($value as $name => $mnemonic) {
						if (!is_array($value[$name])) {
							$value[$name] = [$mnemonic];
						}

						// cleanup mnemonics
						foreach ($value[$name] as $mnemonic_key => $mnemonic_value) {
							$value[$name][$mnemonic_key] = preg_replace("@\s+@", "", strtolower($mnemonic_value));
						}
					}
					break;

				default:
					// by default we don't normalize the value
			}

			// finally define the constant
			define($key, $value);
		}
	}

	// concatenate path pieces fixing leading and trailing slashes
	function concatPath($directory, $file) {
		// removing trailing slashes from $directory
		while ((0 < strlen($directory)) && ("/" === $directory[strlen($directory)-1])) {
			$directory = substr($directory, 0, -1);
		}

		// removing leading slashes from $file
		while ((0 < strlen($file)) && ("/" === $file[0])) {
			$file = substr($file, 1);
		}

		// concat $directory and $file with a slash
		return $directory."/".$file;
	}

	// convert a GCM nonce to a CTR counter
	function convertGCMtoCTR($nonce, $key, $algo) {
		$result = null;

		// check special case first
		if (0x0C === strlen($nonce)) {
			$result = $nonce."\x00\x00\x00\x01";
		} else {
			// produce GHASH of the nonce
			$subkey = openssl_encrypt("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",
			                          $algo,
			                          $key,
			                          OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
			if (false !== $subkey) {
				// store for later use
				$noncelen = strlen($nonce);

				// pad nonce to 128 bit block
				if (0x00 !== ($noncelen % 0x10)) {
					$nonce = $nonce.str_repeat("\x00", 0x10 - ($noncelen % 0x10));
				}

				// append zero padding
				$nonce = $nonce."\x00\x00\x00\x00\x00\x00\x00\x00";

				// append 64-bit nonce length
				$nonce = $nonce."\x00\x00\x00\x00".pack("N", ($noncelen << 0x03));

				// actual GHASH calculation
				$result = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
				for ($i = 0x00; $i < strlen($nonce)/0x10; $i++) {
					$block  = $result ^ substr($nonce, $i * 0x10, 0x10);
					$tmp    = "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00";
					$tmpkey = $subkey;

					// execute the multiplication
					for ($index = 0x00; $index < strlen($block); $index++) {
						for ($bit = 0x07; $bit >= 0x00; $bit--) {
							// store for later use
							$adder = (ord($tmpkey[strlen($tmpkey)-0x01]) & 0x01);
							$mixer = ((ord($block[$index]) >> $bit) & 0x01);

							// merge tmpkey into tmp,
							// do this in a loop for constant time
							for ($byte = 0x00; $byte < strlen($tmp); $byte++) {
								$tmp[$byte] = chr(ord($tmp[$byte]) ^ (ord($tmpkey[$byte]) * $mixer));
							}

							// shift least significant bit out of the tmpkey,
							// afterwards mix the adder into tmpkey,
							// do this in constant time
							$shifted = 0x00;
							for ($byte = 0x00; $byte < strlen($tmpkey); $byte++) {
								$tmpval        = (ord($tmpkey[$byte]) & 0x01);
								$tmpkey[$byte] = chr((($shifted << 0x07) & 0x80) | ((ord($tmpkey[$byte]) >> 0x01) & 0x7F));
								$shifted       = $tmpval;
							}
							$tmpkey[0x00] = chr(ord($tmpkey[0x00]) ^ (0xE1 * $adder));
						}
					}

					$result = $tmp;
				}
			}
		}

		// we need to increment the counter once because we do not need
		// the inital GCM block that is only used for the authentication tag
		if (null !== $result) {
			$result = incrementCounter($result);
		}

		return $result;
	}

	// print messages only if the debug mode is active
	function debug($string) {
		if (DEBUG_MODE) {
			println("DEBUG: $string");
		}
	}

	// print the configuration to the verbose debug log
	function debugConfig() {
		if (DEBUG_MODE_VERBOSE) {
			debug("DATADIRECTORY = ".var_export(DATADIRECTORY, true));
			debug("DEBUG_MODE = ".var_export(DEBUG_MODE, true));
			debug("DEBUG_MODE_VERBOSE = ".var_export(DEBUG_MODE_VERBOSE, true));
			debug("EXTERNAL_STORAGES = ".var_export(EXTERNAL_STORAGES, true));
			debug("USER_MNEMONICS = ".var_export(USER_MNEMONICS, true));
		}
	}

	// parse a metadata file and try to decrypt it
	function decryptMetaDate($json, $privatekeys, $metadatakeys = []) {
		$result = false;

		if (is_array($json)) {
			// check if this is a know metadata format version
			$version = extractMetaDataVersion($json);

			if (0 < $version) {
				// try to find the metadatakeys in this specific metadata file
				$metadatakeys = array_merge($metadatakeys, extractMetaDataKeys($json, 1)); // format version 1 has one key per metadata file

				// try to decrypt the metadata key
				$keys = [];
				foreach ($metadatakeys as $element) {
					// prepare single key
					$key = false;

					foreach ($privatekeys as $privatekey) {
						$key = rsaDecrypt($element,
						                  $privatekey,
						                  "sha256");
						if (false !== $key) {
							// format version 1 used additional encoding
							if (1 === $version) {
								// yes, this really is base64-encoded several times
								$key = base64_decode(base64_decode($key));
							}
							$keys[] = $key;
						} else {
							debug("metadata key could not be decrypted...");
						}

						// exit the lopp
						if (false !== $key) {
							break;
						}
					}
				}

				// proceed if we decrypted at least one metadata key
				if (0 < count($keys)) {
					switch ($version) {
						case 1:
							$result = decryptMetaDateV1($json, $keys);
							break;

						case 2:
							$result = decryptMetaDateV2($json, $keys);
							break;

						default:
							// keep $result as it is
					}
				} else {
					debug("metadata keys could not be decrypted");
				}
			} else {
				debug("metadata have unknown version");
			}
		} else {
			debug("metadata are not JSON encoded");
		}

		return $result;
	}

	// parse a format version 1 metadata file and try to decrypt it
	function decryptMetaDateV1($json, $keys) {
		$result = [];

		if (is_array($json)) {
			if (array_key_exists(METADATA_FILES, $json) &&
			    is_array($json[METADATA_FILES])) {
				foreach ($json[METADATA_FILES] as $filename => $element) {
					if (array_key_exists(METADATA_ENCRYPTED, $element) &&
					    array_key_exists(METADATA_IV,        $element)) {
						// extract parts of the metadata
						$parts = null;
						if (false !== strpos($element[METADATA_ENCRYPTED], "|")) {
							$parts = explode("|", $element[METADATA_ENCRYPTED]);
						} elseif (false !== strpos($element[METADATA_ENCRYPTED], "fA==")) {
							$parts = explode("fA==", $element[METADATA_ENCRYPTED]);
						}

						// we at least need two parts
						if ((is_array($parts)) && (2 <= count($parts))) {
							// try all metadata keys
							foreach ($keys as $key) {
								// parse the metadata structure
								$ciphertext = substr(base64_decode($parts[0]), 0, -TAGSIZE);
								$nonce      = base64_decode($parts[1]);
								$tag        = substr(base64_decode($parts[0]), -TAGSIZE);

								// migrate GCM nonce to CTR counter,
								// we don't use GCM so that broken
								// integrity data do not break the
								// decryption
								$nonce = convertGCMtoCTR($nonce, $key, "aes-128-ecb");

								// decrypt metadata
								$metadata = openssl_decrypt($ciphertext,
								                            "aes-128-ctr",
								                            $key,
								                            OPENSSL_RAW_DATA,
								                            $nonce);
								if (false !== $metadata) {
									$metadata = base64_decode($metadata);
									if (false !== $metadata) {
										$metadata = json_decode($metadata, true, 2, JSON_OBJECT_AS_ARRAY);

										// check the structure of the decrypted metadata
										if (is_array($metadata) &&
										    array_key_exists(METADATA_FILENAME, $metadata) &&
										    array_key_exists(METADATA_MIMETYPE, $metadata)) {
											// check if this is a folder
											if ((METADATA_DIRECTORY_A !== $metadata[METADATA_MIMETYPE]) &&
											    (METADATA_DIRECTORY_B !== $metadata[METADATA_MIMETYPE])) {
												// we need the key of the file
												if (array_key_exists(METADATA_KEY, $metadata)) {
													$result[$filename] = [INTERMEDIATE_FILENAME => $metadata[METADATA_FILENAME],
													                      INTERMEDIATE_KEY      => base64_decode($metadata[METADATA_KEY]),
													                      INTERMEDIATE_NONCE    => base64_decode($element[METADATA_IV])];
												} else {
													debug("decrypted file entry does not contain a key");
												}
											} else {
												$result[$filename] = [INTERMEDIATE_FILENAME => $metadata[METADATA_FILENAME]];
											}

											// continue with the next file
											if (array_key_exists($filename, $result)) {
												break;
											}
										} else {
											debug("decrypted metadata have wrong structure");
										}
									} else {
										debug("decrypted metadata are not base64-encoded");
									}
								} else {
									debug("metadata could not be decrypted: ".openssl_error_string());
								}
							}
						} else {
							debug("encrypted metadata have wrong structure");
						}
					} else {
						debug("metadata file entry has wrong structure");
					}
				}
			} else {
				debug("metadata file list has wrong structure");
			}
		} else {
			debug("metadata are not JSON encoded");
		}


		return $result;
	}

	// parse a format version 2 metadata file and try to decrypt it
	function decryptMetaDateV2($json, $keys) {
		$result = [];

		if (is_array($json)) {
			if (array_key_exists(METADATA_METADATA,   $json) &&
			    array_key_exists(METADATA_CIPHERTEXT, $json[METADATA_METADATA]) &&
			    array_key_exists(METADATA_NONCE,      $json[METADATA_METADATA])) {
				// extract parts of the metadata
				$parts = explode("|", $json[METADATA_METADATA][METADATA_CIPHERTEXT]);

				// we at least need two parts
				if ((is_array($parts)) && (2 <= count($parts))) {
					// try all metadata keys
					foreach ($keys as $key) {
						// parse the metadata structure
						$ciphertext = substr(base64_decode($parts[0]), 0, -TAGSIZE);
						$nonce      = base64_decode($parts[1]);
						$tag        = substr(base64_decode($parts[0]), -TAGSIZE);

						// migrate GCM nonce to CTR counter,
						// we don't use GCM so that broken
						// integrity data do not break the
						// decryption
						$nonce = convertGCMtoCTR($nonce, $key, "aes-128-ecb");

						// decrypt metadata
						$metadata = openssl_decrypt($ciphertext,
						                            "aes-128-ctr",
						                            $key,
						                            OPENSSL_RAW_DATA,
						                            $nonce);
						if (false !== $metadata) {
							// GZIP-decompress metadata
							$metadata = gzdecode($metadata);
							if (false !== $metadata) {
								$metadata = json_decode($metadata, true, 4, JSON_OBJECT_AS_ARRAY);

								// check the structure of the decrypted metadata
								if (is_array($metadata) &&
								    array_key_exists(METADATA_FILES, $metadata) &&
								    is_array($metadata[METADATA_FILES]) &&
								    array_key_exists(METADATA_FOLDERS, $metadata) &&
								    is_array($metadata[METADATA_FOLDERS])) {
									// handle the file entries
									foreach ($metadata[METADATA_FILES] as $filename => $element) {
										if (array_key_exists(METADATA_FILENAME, $element) &&
										    array_key_exists(METADATA_KEY,      $element) &&
										    array_key_exists(METADATA_NONCE,    $element)) {
											$result[$filename] = [INTERMEDIATE_FILENAME => $element[METADATA_FILENAME],
											                      INTERMEDIATE_KEY      => base64_decode($element[METADATA_KEY]),
											                      INTERMEDIATE_NONCE    => base64_decode($element[METADATA_NONCE])];
										}
									}

									// handle the folder entries
									foreach ($metadata[METADATA_FOLDERS] as $filename => $element) {
										$result[$filename] = [INTERMEDIATE_FILENAME => $element];
									}

									// we got everything there is
									break;
								} else {
									debug("decrypted metadata have wrong structure");
								}
							} else {
								debug("decrypted metadata are not GZIP compressed");
							}
						} else {
							debug("metadata could not be decrypted: ".openssl_error_string());
						}
					}
				} else {
					debug("encrypted metadata have wrong structure");
				}
			} else {
				debug("metadata entry has wrong structure");
			}
		} else {
			debug("metadata are not JSON encoded");
		}

		return $result;
	}

	// try to decrypt all available metadata files
	function decryptMetaData($privatekeys) {
		$result = [];

		$files = searchMetaData();

		// try to find the metadatakeys in all metadata files
		$metadatakeys = [];
		foreach ($files as $filename) {
			$file = file_get_contents($filename);
			if (false !== $file) {
				$json         = json_decode($file, true, 4, JSON_OBJECT_AS_ARRAY);
				$metadatakeys = array_merge($metadatakeys, extractMetaDataKeys($json, 2)); // format version 2 has one metadata file with all keys
			}
		}

		foreach ($files as $filename) {
			$file = file_get_contents($filename);
			if (false !== $file) {
				$json     = json_decode($file, true, 4, JSON_OBJECT_AS_ARRAY);
				$metadate = decryptMetaDate($json, $privatekeys, $metadatakeys);
				if (false !== $metadate) {
					$result = array_merge($result, $metadate);

					debug("loaded metadata from $filename");
				}
			}
		}

		return $result;
	}

	// parse a private key file and try to decrypt it
	function decryptPrivateKey($file, $mnemonic) {
		$result = false;

		// extract parts of the private key format
		$parts = null;
		if (false !== strpos($file, "|")) {
			$parts = explode("|", $file);
		} elseif (false !== strpos($file, "fA==")) {
			$parts = explode("fA==", $file);
		}

		// we at least need three parts
		if ((is_array($parts)) && (3 <= count($parts))) {
			// parse the private key structure
			$ciphertext = substr(base64_decode($parts[0]), 0, -TAGSIZE);
			$nonce      = base64_decode($parts[1]);
			$salt       = base64_decode($parts[2]);
			$tag        = substr(base64_decode($parts[0]), -TAGSIZE);

			// try to decrypt private key with different methods
			$methods = [["algorithm" => "sha256", "iterations" => 600000],
                                    ["algorithm" => "sha1",   "iterations" => 600000],
                                    ["algorithm" => "sha1",   "iterations" =>   1024]];
                        foreach ($methods as $method) {
				// take method parameters
				$algorithm  = $method["algorithm"];
				$iterations = $method["iterations"];

				// derive actual secret
				$secret = hash_pbkdf2($algorithm,
				                      $mnemonic,
				                      $salt,
				                      $iterations,
				                      32,
				                      true);

				// migrate GCM nonce to CTR counter,
				// we don't use GCM so that broken
				// integrity data do not break the
				// decryption
				$counter = convertGCMtoCTR($nonce, $secret, "aes-256-ecb");

				// decrypt private key
				$privatekey = openssl_decrypt($ciphertext,
				                              "aes-256-ctr",
				                              $secret,
				                              OPENSSL_RAW_DATA,
				                              $counter);
				if (false !== $privatekey) {
					// base64-decode again just for good measure
					$privatekey = base64_decode($privatekey);
					if (false !== $privatekey) {
						$res = openssl_pkey_get_private($privatekey);
						if (is_resource($res) || ($res instanceof OpenSSLAsymmetricKey)) {
							$sslInfo = openssl_pkey_get_details($res);
							if (array_key_exists("key", $sslInfo)) {
								$result = $privatekey;
							}
						} else {
							debug("decrypted content is not a privatekey");
						}
					} else {
						debug("decrypted content is not base64-encoded");
					}
				} else {
					debug("privatekey could not be decrypted: ".openssl_error_string());
				}

				// take a shortcut
				if (false !== $result) {
					break;
				}
                        }

			// if we do not have a result then print a debug message
			if (false === $result) {
				debug("privatekey is encrypted with an unsupported method");
			}
		} else {
			debug("privatekey file has wrong structure");
		}

		return $result;
	}

	// try to decrypt all available private keys
	function decryptPrivateKeys() {
		$result = [];

		$keys = array_merge(searchSystemKeys(),
		                    searchUserKeys());
		foreach ($keys as $key) {
			$file = file_get_contents($key[KEY_FILE]);
			if (false !== $file) {
				foreach ($key[KEY_MNEMONICS] as $mnemonic) {
					$privatekey = decryptPrivateKey($file, $mnemonic);
					if (false !== $privatekey) {
						$result[$key[KEY_NAME]] = $privatekey;

						debug("loaded private key for ".$key[KEY_NAME]);
					}
				}
			}
		}

		return $result;
	}

	// try to find the metadatakeys in the metadata file
	function extractMetaDataKeys($json, $version) {
		$result = [];

		if (is_array($json)) {
			switch ($version) {
				case 1:
					if (array_key_exists(METADATA_METADATA, $json) &&
					    is_array($json[METADATA_METADATA])) {
						if (array_key_exists(METADATA_METADATAKEY, $json[METADATA_METADATA])) {
							$result[] = base64_decode($json[METADATA_METADATA][METADATA_METADATAKEY]);
						} elseif (array_key_exists(METADATA_METADATAKEYS, $json[METADATA_METADATA]) &&
						        is_array($json[METADATA_METADATA][METADATA_METADATAKEYS])) {
							foreach ($json[METADATA_METADATA][METADATA_METADATAKEYS] as $element) {
								$result[] = base64_decode($element);
							}
						}
                                        }
					break;

				case 2:
					if (array_key_exists(METADATA_USERS, $json) &&
					    is_array($json[METADATA_USERS])) {
						foreach ($json[METADATA_USERS] as $user) {
							if (array_key_exists(METADATA_ENCRYPTEDMETADATAKEY, $user)) {
								$result[] = base64_decode($user[METADATA_ENCRYPTEDMETADATAKEY]);
							}
						}
					}
					break;

				default:
					// keep $result as it is
			}
		}

		return $result;
	}

	// get the rough structure version of a metadata file
	function extractMetaDataVersion($json) {
		$result = 0;

		if (is_array($json)) {
			if (array_key_exists(METADATA_VERSION, $json) &&
			    (VERSION_20 === $json[METADATA_VERSION])) {
				$result = 2;
			} else {
				if (array_key_exists(METADATA_METADATA, $json) &&
				    array_key_exists(METADATA_VERSION,  $json[METADATA_METADATA]) &&
				    ((VERSION_1  === $json[METADATA_METADATA][METADATA_VERSION]) ||
				     (VERSION_12 === $json[METADATA_METADATA][METADATA_VERSION]))) {
					$result = 1;
				}
			}
		}

		return $result;
	}

	// get the home directory of the current user
	function getHomeDir($username = null) {
		$result = "";

		$pwuid = (null === $username) ? posix_getpwuid(posix_getuid()) : posix_getpwnam($username);
		if (is_array($pwuid) && array_key_exists("dir", $pwuid)) {
			$result = $pwuid["dir"];
		}

		return $result;
	}

	// increment a CTR counter
	function incrementCounter($counter, $increment = 0x01) {
		$result = $counter;

		if (is_string($result) && is_int($increment) && (0x00 <= $increment)) {
			// add increment to the result
			for ($index = strlen($result)-0x01; $index >= 0x00; $index--) {
				$tmp            = ((ord($result[$index]) + $increment) >> 0x08);
				$result[$index] = chr((ord($result[$index]) + $increment) & 0xFF);
				$increment      = $tmp;
			}
		}

		return $result;
	}

	// normalize path
	function normalizePath($path, $trailing_slash = false) {
		// define some placeholders
		$current  = ".";
		$empty    = "";
		$previous = "..";
		$slash    = "/";
		$tilde    = "~";

		// preset $result
		$result = $path;

		// an empty string is interpreted as the current working dir
		if (0 === strlen($path)) {
			$path = getcwd();
		}

		// prepare $path as array
		$path = explode($slash, $path);
		if (0 < count($path)) {
			// prepare $cwd as empty array
			$cwd = [];

			// check if the starts with a home name
			if (1 === preg_match("@^~(?<username>.+)$@", $path[0], $matches)) {
				$cwd = explode($slash, getHomeDir($matches["username"]));
			} else {
				switch ($path[0]) {
					case $current:
						$cwd = explode($slash, getcwd());
						break;

					case $empty:
						array_push($cwd, $empty);
						break;

					case $previous:
						$cwd = explode($slash, getcwd());
						array_pop($cwd);
						break;

					case $tilde:
						$cwd = explode($slash, getHomeDir());
						break;

					default:
						$cwd = explode($slash, getcwd());
						array_push($cwd, $path[0]);
				}
			}

			// normalize $path
			for ($index = 1; $index < count($path); $index++) {
				switch ($path[$index]) {
					case $current:
						break;

					case $empty:
						break;

					case $previous:
						array_pop($cwd);
						break;

					default:
						array_push($cwd, $path[$index]);
				}
			}

			// make sure that we are at least in the root directory
			while (2 > count($cwd)) {
				array_unshift($cwd, $empty);
			}

			if ($trailing_slash) {
				if ((0 < count($cwd)) && ($empty !== $cwd[count($cwd)-1])) {
					array_push($cwd, $empty);
				}
			}

			$result = implode($slash, $cwd);
		}

		return $result;
	}

	// try to parse the filename
	function parseFilename($filename, $source_name = null, $source_path = null) {
		$result = [];

		// do we handle the data directory or an external storage
		if ((null === $source_name) || (null === $source_path)) {
			if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files/(?<filename>.+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["filename"],
				           FILE_NAME_RAW      => $matches["filename"],
				           FILE_TRASHBIN      => false,
				           FILE_TRASHBIN_TIME => "",
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => false,
				           FILE_VERSION_TIME  => ""];
			} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files_trashbin/files/(?<foldername>[^/]+)\.d(?<trashbintime>[0-9]+)/(?<filename>.+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["foldername"].".d".$matches["trashbintime"]."/".$matches["filename"],
				           FILE_NAME_RAW      => $matches["foldername"].".d".$matches["trashbintime"]."/".$matches["filename"],
				           FILE_TRASHBIN      => true,
				           FILE_TRASHBIN_TIME => $matches["trashbintime"],
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => false,
				           FILE_VERSION_TIME  => ""];
			} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files_trashbin/files/(?<filename>.+)\.d(?<trashbintime>[0-9]+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["filename"].".d".$matches["trashbintime"],
				           FILE_NAME_RAW      => $matches["filename"],
				           FILE_TRASHBIN      => true,
				           FILE_TRASHBIN_TIME => $matches["trashbintime"],
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => false,
				           FILE_VERSION_TIME  => ""];
			} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files_trashbin/versions/(?<foldername>[^/]+)\.d(?<trashbintime>[0-9]+)/(?<filename>.+)\.v(?<versionnumber>[0-9]+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["foldername"].".d".$matches["trashbintime"]."/".$matches["filename"],
				           FILE_NAME_RAW      => $matches["foldername"].".d".$matches["trashbintime"]."/".$matches["filename"],
				           FILE_TRASHBIN      => true,
				           FILE_TRASHBIN_TIME => $matches["trashbintime"],
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => true,
				           FILE_VERSION_TIME  => $matches["versionnumber"]];
			} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files_trashbin/versions/(?<filename>.+)\.v(?<versionnumber>[0-9]+)\.d(?<trashbintime>[0-9]+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["filename"].".d".$matches["trashbintime"],
				           FILE_NAME_RAW      => $matches["filename"],
				           FILE_TRASHBIN      => true,
				           FILE_TRASHBIN_TIME => $matches["trashbintime"],
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => true,
				           FILE_VERSION_TIME  => $matches["versionnumber"]];
			} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[^/]+)/files_versions/(?<filename>.+)\.v(?<versionnumber>[0-9]+)$@", $filename, $matches)) {
				$result = [FILE_FILE          => $filename,
				           FILE_NAME          => $matches["filename"],
				           FILE_NAME_RAW      => $matches["filename"],
				           FILE_TRASHBIN      => false,
				           FILE_TRASHBIN_TIME => "",
				           FILE_USERNAME      => $matches["username"],
				           FILE_VERSION       => true,
				           FILE_VERSION_TIME  => $matches["versionnumber"]];
			}
		} else {
			$foldername = "";
			$username   = "";

			// do we handle a user-specific external storage
			if (false === strpos($source_name, "/")) {
				$foldername = $source_name;
			} else {
				$foldername = substr($source_name, strpos($source_name, "/")+1);
				$username   = substr($source_name, 0, strpos($source_name, "/"));
			}

			$result = [FILE_FILE          => $filename,
			           FILE_NAME          => concatPath($foldername, substr($filename, strlen($source_path))),
			           FILE_NAME_RAW      => concatPath($foldername, substr($filename, strlen($source_path))),
			           FILE_TRASHBIN      => false,
			           FILE_TRASHBIN_TIME => "",
			           FILE_USERNAME      => $username,
			           FILE_VERSION       => false,
			           FILE_VERSION_TIME  => ""];
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("parsed = ".var_export($result, true));
		}

		return $result;
	}

	// make sure that all configuration values exist
	function prepareConfig() {
		// nextcloud definitions
		config("DATADIRECTORY", getcwd());

		// user mnemonic definition
		config("USER_MNEMONICS", []);

		// external storage definition
		config("EXTERNAL_STORAGES", []);

		// debug mode definitions
		config("DEBUG_MODE",         false);
		config("DEBUG_MODE_VERBOSE", false);
	}

	// prepare all source paths
	function prepareSources($sources) {
		$result = [];

		// set sources to all items in the data directory
		if ((null === $sources) || (0 === count($sources))) {
			// do a scandir to flatten the execution a bit,
			// this way not the whole file structure will have
			// to be pulled into the memory at once
			$sources = recursiveScandir(DATADIRECTORY, false);
		}

		// clean-up the sources
		foreach ($sources as $source) {
			// normalize all sources
			$source = normalizePath($source);

			// only handle non-empty sources
			if (0 < strlen($source)) {
				// only add source to result if it exists
				if (is_file($source) || is_dir($source)) {
					$result["\0".count($result)] = $source;
				} else {
					println("WARNING: SOURCE PATH $source DOES NOT EXIST");
				}
			}
		}

		// add external storage folders as sources
		foreach (EXTERNAL_STORAGES as $key => $value) {
			// normalize all sources
			$value = normalizePath($value);

			if (is_dir($value)) {
				$result[$key] = $value;
			} else {
				println("WARNING: EXTERNAL STORAGE $value DOES NOT EXIST");
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("sources = ".var_export($result, true));
		}

		return $result;
	}

	// print help text
	function printHelp() {
		// load our own source code
		$source = file(__FILE__, FILE_IGNORE_NEW_LINES);

		// iterate over the source lines
		$started = false;
		foreach ($source as $line) {
			// remove trailing and leading whitespace
			$line = trim($line);

			// check if the help comment starts
			if (!$started) {
				// help comment starts with a hash sign and is not a shebang
				$started = (0 === strpos($line, "#")) && (1 !== strpos($line, "!"));
			}

			// print all lines that start with a hash sign
			if ($started) {
				if (0 === strpos($line, "#")) {
					// remove the hash sign
					$line = substr($line, 1);

					// check if the trimmed line is empty
					if (0 === strlen(trim($line))) {
						println("");
					} else {
						// otherwise we expect the next character to be a whitespace,
						// we don't print other lines so that lines from the the help
						// can be commented out (e.g. through "##")
						if (0 === strpos($line, " ")) {
							// remove the whitespace and print the line
							println(substr($line, 1));
						}
					}
				} else {
					// break with the first line that differs
					break;
				}
			}
		}
	}

	// print messages with a line break
	function println($string) {
		print($string.PHP_EOL);
	}

	// scan a folder and optionally scan it recursively
	function recursiveScandir($path, $recursive = true) {
		$result = [];

		if (is_dir($path)) {
			$content = scandir($path);
			foreach ($content as $content_item) {
				if (("." !== $content_item) && (".." !== $content_item)) {
					if (is_file(normalizePath($path."/".$content_item))) {
						$result[] = normalizePath($path."/".$content_item);
					} elseif (is_dir(normalizePath($path."/".$content_item))) {
						if ($recursive) {
							$result = array_merge($result, recursiveScandir(normalizePath($path."/".$content_item)));
						} else {
							$result[] = normalizePath($path."/".$content_item);
						}
					}
				}
			}
		}

		return $result;
	}

	// decrypt RSA blob with OAEP unpadding
	function rsaDecrypt($ciphertext, $privatekey, $algo = "sha256") {
		$result = false;

		// parse RSA key
		$key = openssl_pkey_get_private($privatekey);
		if (false !== $key) {
			try {
				// get RSA key details
				$details = openssl_pkey_get_details($key);
				if (false !== $details) {
					if (array_key_exists("rsa", $details) &&
					    array_key_exists("d",   $details["rsa"]) &&
					    array_key_exists("n",   $details["rsa"])) {
						// get big number representations
						$c = gmp_import($ciphertext,          strlen($ciphertext),          GMP_BIG_ENDIAN);
						$d = gmp_import($details["rsa"]["d"], strlen($details["rsa"]["d"]), GMP_BIG_ENDIAN);
						$n = gmp_import($details["rsa"]["n"], strlen($details["rsa"]["n"]), GMP_BIG_ENDIAN);

						// decrypt content
						$tmp = gmp_powm($c, $d, $n);
						$tmp = gmp_export($tmp, strlen($details["rsa"]["n"]), GMP_BIG_ENDIAN);

						// unpad message
						$result = rsaOAEP($tmp, $algo);
					}
				}
			} finally {
				// prevent deprecation notice in PHP 8.0 and above
				if (0 > version_compare(PHP_VERSION, "8.0.0")) {
					openssl_free_key($key);
				}
			}
		}

		return $result;
	}

	// mask generation function
	function rsaMGF1($seed, $length, $algo = "sha256") {
		$result = false;

		// parameter check
		if (0 < $length) {
			$result = "";

			$hashLength = strlen(hash($algo, "", true));
			for ($counter = 0; $counter < ceil($length / $hashLength); $counter++) {
				$result .= hash($algo, $seed.pack("N", $counter), true);
			}

			// get the requested length
			$result = substr($result, 0, $length);
		}

		return $result;
	}

	// optimal asymmetric encryption padding
	function rsaOAEP($content, $algo = "sha256", $oaepLabel = "") {
		$result = false;

		// check that the first byte is zero
		if ((1 < strlen($content)) &&
		    ("\x00" === $content[0])) {
			$hashLength = strlen(hash($algo, "", true));

			// parse message
			$maskedSeed = substr($content, 1, $hashLength);
			$maskedDB   = substr($content, $hashLength+1);

			// derive seed from maskedSeed and maskedDB
			$seedMask = rsaMGF1($maskedDB, $hashLength);
			$seed     = $maskedSeed ^ $seedMask;

			// unmask actual data
			$dbMask = rsaMGF1($seed, strlen($maskedDB));
			$db     = $maskedDB ^ $dbMask;

			// parse the unmasked content
			$hash = substr($db, 0, $hashLength);
			if (hash_equals($hash, hash($algo, $oaepLabel, true))) {
				$tmp = substr($db, $hashLength);
				$tmp = ltrim($tmp, "\x00");

				// check that the first byte is one
				if ((1 < strlen($tmp)) &&
				    ("\x01" === $tmp[0])) {
					$result = substr($tmp, 1);
				}
			}
		}

		return $result;
	}

	// test different filename structures for the metadata files
	function searchMetaData() {
		$result = [];

		$folderlist = recursiveScandir(DATADIRECTORY, false);
		foreach ($folderlist as $foldername) {
			if (is_dir($foldername)) {
				if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/appdata_[0-9A-Za-z]+$@", $foldername)) {
					// potential metadata path
					$metadatapath = normalizePath($foldername."/end_to_end_encryption/meta-data/");

					$filelist = recursiveScandir($metadatapath, true);
					foreach ($filelist as $filename) {
						if (is_file($filename)) {
							if (1 === preg_match("@^".preg_quote($metadatapath, "@")."/.+/meta\.data$@", $filename)) {
								$result[] = $filename;
							}
						}
					}
				}
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("metadata = ".var_export($result, true));
		}

		return $result;
	}

	// test different filename structures for the system keys
	function searchSystemKeys() {
		// there currently are no system keys,
		// could come back when recovery key support is added
		return [];
	}

	// test different filename structures for the user keys
	function searchUserKeys() {
		$result = [];

		$folderlist = recursiveScandir(DATADIRECTORY, false);
		foreach ($folderlist as $foldername) {
			if (is_dir($foldername)) {
				if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/appdata_[0-9a-z]+$@", $foldername)) {
					// potential key path
					$keypath = normalizePath($foldername."/end_to_end_encryption/private-keys/");

					$filelist = recursiveScandir($keypath, false);
					foreach ($filelist as $filename) {
						if (is_file($filename)) {
							if (1 === preg_match("@^".preg_quote($keypath, "@")."/(?<username>[0-9A-Za-z\.\-\_\@]+)\.private\.key$@", $filename, $matches)) {
								if (array_key_exists(strtolower($matches["username"]), USER_MNEMONICS)) {
									$result[] = [KEY_FILE      => $filename,
									             KEY_MNEMONICS => USER_MNEMONICS[strtolower($matches["username"])],
									             KEY_NAME      => $matches["username"]];
								}
							}
						}
					}
				}
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("userkeys = ".var_export($result, true));
		}

		return $result;
	}

	// shorten a string with a filler
	function shortenString($string, $length, $filler = "...") {
		$result = $string;

		// check if it makes sense to shorten the string
		if ((strlen($result) > $length) && (strlen($filler) < $length)) {
			$result = substr_replace($result, $filler, ceil($length - strlen($filler)) / 2, -floor(($length - strlen($filler)) / 2));
		}

		return $result;
	}

	// ===== MAIN FUNCTIONS =====

	// try to copy a file
	function copyFile($filename, $targetname) {
		// try to set file times later on
		$fileatime = fileatime($filename);
		$filemtime = filemtime($filename);

		$result = copy($filename, $targetname);

		// try to set file times
		if ($result && (false !== $filemtime)) {
			// fix access time if necessary
			if (false === $fileatime) {
				$fileatime = time();
			}

			touch($targetname, $filemtime, $fileatime);
		}

		return $result;
	}

	// try to decrypt a file
	function decryptFile($filename, $metadata, $targetname) {
		$result = false;

		if ((array_key_exists(INTERMEDIATE_KEY,   $metadata)) &&
		    (array_key_exists(INTERMEDIATE_NONCE, $metadata))) {
			// try to set file times later on
			$fileatime = fileatime($filename);
			$filemtime = filemtime($filename);

			$sourcefile = fopen($filename,   "r");
			$targetfile = fopen($targetname, "w");
			try {
				$result = true;

				$block      = "";
				$buffer     = "";
				$key        = $metadata[INTERMEDIATE_KEY];
				$nonce      = convertGCMtoCTR($metadata[INTERMEDIATE_NONCE], $metadata[INTERMEDIATE_KEY], "aes-128-ecb");
				$plain      = "";
				$sourcesize = filesize($filename);
				$targetsize = 0;
				$tmp        = "";
				do {
					$tmp = fread($sourcefile, BLOCKSIZE);
					if (false !== $tmp) {
						$buffer .= $tmp;

						while (BLOCKSIZE <= strlen($buffer)) {
							$block  = substr($buffer, 0, BLOCKSIZE);
							$buffer = substr($buffer, BLOCKSIZE);

							// check if we are decrypting the last block
							if ((0 === strlen($buffer)) && (($targetsize + strlen($block)) === $sourcesize)) {
								// remove the last few bytes as these
								// are yet another E2E tag
								$block = substr($block, 0, -TAGSIZE);
							}

							$plain = openssl_decrypt($block,
							                         "aes-128-ctr",
							                         $key,
							                         OPENSSL_RAW_DATA,
							                         $nonce);
							if (false !== $plain) {
								// write fails when fewer bytes than string length are written
								$result     = $result && (strlen($plain) === fwrite($targetfile, $plain));
								$targetsize = $targetsize + strlen($block);
							} else {
								// decryption failed
								$result = false;
							}

							// increment counter,
							// 16 = AES-128 block length
							$nonce = incrementCounter($nonce, BLOCKSIZE/16);
						}
					}
				} while (!feof($sourcefile));

				// decrypt trailing blocks
				while (0 < strlen($buffer)) {
					$block  = substr($buffer, 0, BLOCKSIZE);
					$buffer = substr($buffer, BLOCKSIZE);

					// check if we are decrypting the last block
					if ((0 === strlen($buffer)) && (($targetsize + strlen($block)) === $sourcesize)) {
						// remove the last few bytes as these
						// are yet another E2E tag
						$block = substr($block, 0, -TAGSIZE);
					}

					$plain = openssl_decrypt($block,
					                         "aes-128-ctr",
					                         $key,
					                         OPENSSL_RAW_DATA,
					                         $nonce);
					if (false !== $plain) {
						// write fails when fewer bytes than string length are written
						$result     = $result && (strlen($plain) === fwrite($targetfile, $plain));
						$targetsize = $targetsize + strlen($block);
					} else {
						// decryption failed
						$result = false;
					}

					// increment counter,
					// 16 = AES-128 block length
					$nonce = incrementCounter($nonce, BLOCKSIZE/16);
				}
			} finally {
				fclose($sourcefile);
				fclose($targetfile);
			}

			// try to set file times
			if ($result && (false !== $filemtime)) {
				// fix access time if necessary
				if (false === $fileatime) {
					$fileatime = time();
				}

				touch($targetname, $filemtime, $fileatime);
			}
		}

		return $result;
	}

	// iterate over the file lists and try to decrypt the files
	function decryptFiles($targetdir, $sourcepaths = null) {
		$result = true;

		// print some text so that users do not wait in front of a blank screen
		println("INFO: decrypting private keys...");

		// try to find and decrypt all available private keys
		$privatekeys = decryptPrivateKeys();
		if (0 >= count($privatekeys)) {
			println("WARNING: COULD NOT DECRYPT ANY PRIVATE KEY");
		}

		// print some text so that users do not wait in front of a blank screen
		println("INFO: decrypting meta data...");

		// try to decrypt as many metadata as possible
		$metadata = decryptMetaData($privatekeys);
		if (0 >= count($metadata)) {
			println("WARNING: COULD NOT DECRYPT ANY META DATA");
		}

		// print some text so that users do not wait in front of a blank screen
		println("INFO: preparing sources, this could take a while...");

		// collect all file sources
		$sources = prepareSources($sourcepaths);
		foreach ($sources as $source_name => $source_path) {
			// normalize $source_name
			if ("\0" === $source_name[0]) {
				$source_name = null;
			}

			// get the filelist in-time
			$filelist = null;
			if (is_file($source_path)) {
				$filelist = [$source_path];
			} else {
				$filelist = recursiveScandir($source_path);
			}

			foreach ($filelist as $filename) {
				debug("filename = $filename");

				if (is_file($filename)) {
					// generate target filename
					$targetname = null;

					// prepare path reconstruction
					$foldername = "";
					$filemeta   = null;
					$subpath    = [];
					$username   = "";
					if (null === $source_name) {
						$subpath = explode("/", substr($filename, strlen(DATADIRECTORY)));
					} else {
						// do we handle a user-specific external storage
						if (false === strpos($source_name, "/")) {
							$foldername = $source_name;
						} else {
							$foldername = substr($source_name, strpos($source_name, "/")+1);
							$username   = substr($source_name, 0, strpos($source_name, "/"));
						}
						$subpath = explode("/", substr($filename, strlen($source_path)));
					}

					// execute path reconstruction by trying to identify
					// the original path components in the metadats
					foreach ($subpath as $index => $element) {
						// do some structural checks
						if (array_key_exists($element,              $metadata) &&
						    array_key_exists(INTERMEDIATE_FILENAME, $metadata[$element])) {
							// handle directories
							if ($index < (count($subpath)-1)) {
								// folders don't have a key or nonce
								if (!array_key_exists(INTERMEDIATE_KEY,   $metadata[$element]) &&
								    !array_key_exists(INTERMEDIATE_NONCE, $metadata[$element])) {
									$subpath[$index] = $metadata[$element][INTERMEDIATE_FILENAME];
								}
							} else {
								// files have a key and nonce
								if (array_key_exists(INTERMEDIATE_KEY,   $metadata[$element]) &&
								    array_key_exists(INTERMEDIATE_NONCE, $metadata[$element])) {
									$filemeta        = $metadata[$element];
									$subpath[$index] = $metadata[$element][INTERMEDIATE_FILENAME];
								}
							}
						}
					}

					// finalize path reconstruction
					if (null === $source_name) {
						$targetname = normalizePath($targetdir."/".implode("/", $subpath));
					} else {
						$targetname = normalizePath($targetdir."/".$username."/".EXTERNAL_PREFIX.$foldername."/".implode("/", $subpath));
					}
					debug("targetname = $targetname");

					// only proceed if the target does not already exist
					// or if the existing file does not have any content
					if ((!is_file($targetname)) || (0 >= filesize($targetname))) {
						// retrieve filename elements
						$parsed = parseFilename($filename, $source_name, $source_path);
						if (0 < count($parsed)) {
							// we haven't succeeded yet
							$success = false;

							// try to recursively create the target subfolder
							if (!is_dir(dirname($targetname))) {
								mkdir(dirname($targetname), 0777, true);
							}

							// only try to decrypt when we found corresponding metadata
							if (null !== $filemeta) {
								debug("trying to decrypt file...");

								$success = decryptFile($filename, $filemeta, $targetname);
							} else {
								debug("trying to copy file...");

								$success = copyFile($filename, $targetname);
							}

							debug("success = ".($success ? "true" : "false"));
							if ($success) {
								println("DONE: $filename");
							} else {
								// we failed but created a file,
								// discard the broken file
								if (is_file($targetname)) {
									unlink($targetname);
								}
								println("ERROR: $filename FAILED");
							}

							// update result
							$result = ($result && $success);
						} else {
							debug("skipping this file because the filename structure is unknown...");
						}
					} else {
						println("SKIP: $targetname ALREADY EXISTS");
					}
				} else {
					debug("skipping this item because it is not a file...");
				}
			}
		}

		return $result;
	}

	// ===== MAIN ENTRYPOINT =====

	// handle the parameters
	function main($arguments) {
		$result = 0;

		// check if we are expected to print the help
		$printHelp = (1 >= count($arguments));
		if (!$printHelp) {
			foreach ($arguments as $argument) {
				$printHelp = (("-h" === $argument) || ("--help" === $argument));
				if ($printHelp) {
					break;
				}
			}
		}

		// check if need to show the help instead
		if (!$printHelp) {
			// prevent executiong on Windows, we will need function calls
			// and path identification that are only tested on Linux and macOS
			if ("Windows" !== PHP_OS_FAMILY) {
				// prevent execution if GMP extension is not loaded,
				// we need this for our re-implementation of RSA
				if (extension_loaded("gmp")) {
					// prevent execution if ZLIB extension is not loaded,
					// we need this for the format version 2 of the metadata
					if (extension_loaded("zlib")) {
						// prepare configuration values if not set
						prepareConfig();

						debug("debug mode enabled");
						debugConfig();

						// we want to work with an empty stat cache
						clearstatcache(true);

						if (is_dir(DATADIRECTORY)) {
							$targetdir = null;
							if (2 <= count($arguments)) {
								$targetdir = normalizePath($arguments[1]);
							}

							$sourcepaths = [];
							if (3 <= count($arguments)) {
								$sourcepaths = array_slice($arguments, 2);
								foreach ($sourcepaths as $key => $value) {
									$sourcepaths[$key] = normalizePath($value);
								}
							}

							if ((null !== $targetdir) && is_dir($targetdir)) {
								if (decryptFiles($targetdir, $sourcepaths)) {
									debug("exiting");
								} else {
									println("ERROR: AN ERROR OCCURED DURING THE DECRYPTION");
									$result = 6;
								}
							} else {
								println("ERROR: TARGETDIR NOT GIVEN OR DOES NOT EXIST");
								$result = 5;
							}
						} else {
							println("ERROR: DATADIRECTORY DOES NOT EXIST");
							$result = 4;
						}
					} else {
						println("ERROR: MANDATORY ZLIB EXTENSION IS NOT LOADED");
						$result = 3;
					}
				} else {
					println("ERROR: MANDATORY GMP EXTENSION IS NOT LOADED");
					$result = 2;
				}
			} else {
				println("ERROR: DO NOT EXECUTE ON WINDOWS, USE THE WINDOWS SUBSYSTEM FOR LINUX INSTEAD");
				$result = 1;
			}
		} else {
			printHelp();
		}

		return $result;
	}

	// do not execute main() when we are in TESTING mode
	if ((!defined("TESTING")) && (!getenv("TESTING"))) {
		// main entrypoint
		exit(main($argv));
	}

