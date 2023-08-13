#!/usr/bin/env php
<?php

	# ./server-side-encryption/recover.php
	#
	# Copyright (c) 2023,      Yahe <hello@yahe.sh>
	# Copyright (c) 2019-2023, SysEleven GmbH
	# All rights reserved.
	#
	#
	# usage:
	# ======
	#
	# ./server-side-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
	#
	#
	# description:
	# ============
	#
	# This script can recover your precious files if you encrypted them with the
	# Nextcloud Server-Side Encryption and still have access to the data directory
	# and the Nextcloud configuration file (config/config.php). It supports the
	# master-key encryption, the user-key encryption and can even use the rescue key
	# if it had been enabled as well as the public sharing key for files that had been
	# publicly shared.
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
	# SECRET                  this is a value from the Nextcloud configuration file,
	# (REQUIRED)              there does not seem to be another way to retrieve this
	#                         value, you can provide an array of values if you are
	#                         uncertain which value is correct and all of them will
	#                         be tried out
	#
	# INSTANCEID              this is a value from the Nextcloud configuration file,
	# (OPTIONAL)              if no value is provided then the script will try to
	#                         guess the correct value based on the existing "appdata"
	#                         folders, you can provide an array of values if you are
	#                         uncertain which value is correct and all of them will
	#                         be tried out
	#
	# RECOVERY_PASSWORD       this is the password for the recovery key, you can set
	# (OPTIONAL)              this value if you activated the recovery feature of your
	#                         Nextcloud instance, leave this value empty if you did
	#                         not acticate the recovery feature of your Nextcloud
	#                         instance, you can provide an array of values if you are
	#                         uncertain which value is correct and all of them will
	#                         be tried out
	#
	# USER_PASSWORDS          these are the passwords for the user keys, you have to
	# (OPTIONAL)              set these values if you disabled the master key
	#                         encryption of your Nextcloud instance, you do not have
	#                         to set these values if you did not disable the master
	#                         key encryption of your Nextcloud instance, each value
	#                         represents a (username, password) pair and you can set
	#                         as many pairs as necessary, you can provide an array of
	#                         passwords per user if you are uncertain which password
	#                         is correct and all of them will be tried out
	#
	#                         Example: if the username was "beispiel" and the password
	#                                  of that user was "example" then the value has
	#                                  to be set as:
	#
	#                                  config("USER_PASSWORDS",
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
	# SUPPORT_MISSING_HEADERS this is a value that tells the script if you have
	# (OPTIONAL)              encrypted files without headers, this configuration is
	#                         only needed if you have data from a VERY old Owncloud
	#                         instance, you probably should not set this value as it
	#                         will break unencrypted files that may live alongside
	#                         your encrypted files
	#
	#
	# environment variables:
	# ======================
	#
	# All configuration values can alternatively be provided through environment
	# variables and superseed the information provided within the script. Lists like
	# EXTERNAL_STORAGES and USER_PASSWORDS must be provided as space-separated
	# strings.
	#
	# Example: if two user passwords shall be provided through an environment
	#          variable then the corresponding value has to be set as:
	#
	#          USER_PASSWORDS="user1=password1 user2=password2"
	#
	# It is possible to provide more than one password per user through USER_PASSWORDS
	# in case you have several passwords and do not know which of them is correct.
	# All of them will be tried out.
	#
	# Example: if two passwords for the same user shall be provided through an
	#          environment variable then the corresponding value has to be set as:
	#
	#          USER_PASSWORDS="user=password1 user=password2"
	#
	# The values INSTANCEID, RECOVERY_PASSWORD and SECRET are handled as
	# space-separated lists in case you have several values and do not know which of
	# them is correct. All of them will be tried out.
	#
	#
	# execution:
	# ==========
	#
	# To execute the script you have to call it in the following way:
	#
	# ./server-side-encryption/recover.php <targetdir> [<sourcedir>|<sourcefile>]*
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
	#              the current working directory, if this parameter is not provided
	#              then all files in the data directory will be decrypted
	#
	# <sourcefile> this is the name of the source file which shall be decrypted, the
	# (OPTIONAL)   name of the source file has to be either absolute or relative to
	#              the current working directory, if this parameter is not provided
	#              then all files in the data directory will be decrypted
	#
	# The execution may take a lot of time, depending on the power of your computer
	# and on the number and size of your files. Make sure that the script is able to
	# run without interruption. As of now it does not have a resume feature. On
	# servers you can achieve this by starting the script within a screen session.
	#
	# Also, the script currently does not support the decryption of files in the
	# trashbin that have been deleted from external storage as Nextcloud creates zero
	# byte files when deleting such a file instead of copying over its actual content.
	#
	# Windows users: This script will not run on Windows. Please use the Windows
	#                Subsystem for Linux instead.

	// ===== USER CONFIGURATION =====

	// nextcloud definitions - you can get these values from `config/config.php`
	config("DATADIRECTORY", "");
	config("SECRET",        "");

	// instanceid definition
	// config("INSTANCEID", "");

	// recovery password definition
	// config("RECOVERY_PASSWORD", "");

	// user password definition,
	// replace "username" with the actual usernames and "password" with the actual passwords,
	// you can add or remove entries as necessary
	// config("USER_PASSWORDS", ["username" => "password",
	//                           "username" => "password",
	//                           "username" => "password"]);

	// external storage definition,
	// replace "storage" with the actual external storage names and "/mountpath" with the actual external storage mount paths,
	// you can add or remove entries as necessary
	// config("EXTERNAL_STORAGES", ["storage" => "/mountpath",
	//                              "storage" => "/mountpath",
	//                              "storage" => "/mountpath"]);

	// missing headers definition,
	// this should only be set to TRUE if you have really old encrypted files that do not contain encryption headers,
	// in most cases this will rather break unencrypted files that may live alongside your encrypted files
	// config("SUPPORT_MISSING_HEADERS", false);

	// debug mode definitions
	// config("DEBUG_MODE",         false);
	// config("DEBUG_MODE_VERBOSE", false);

	##### DO NOT EDIT BELOW THIS LINE #####

	// ===== SYSTEM DEFINITIONS =====

	// block size definitions
	config("BLOCKSIZE", 8192);

	// list of supported ciphers
	config("CIPHER_SUPPORT", ["AES-256-CTR" => 32,
	                          "AES-128-CTR" => 16,
	                          "AES-256-CFB" => 32,
	                          "AES-128-CFB" => 16]);

	// filename parts used by encryption:decrypt-all and encryption:encrypt-all
	config("DECRYPTION_INFIX",  ".decrypted.");
	config("ENCRYPTION_INFIX",  ".encrypted.");
	config("ENCRYPTION_SUFFIX", ".part");

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

	// header entries
	config("HEADER_BEGIN",                "HBEGIN");
	config("HEADER_CIPHER",               "cipher");
	config("HEADER_END",                  "HEND");
	config("HEADER_ENCODING",             "encoding");
	config("HEADER_KEYFORMAT",            "keyFormat");
	config("HEADER_OC_ENCRYPTION_MODULE", "oc_encryption_module");
	config("HEADER_SIGNED",               "signed");
	config("HEADER_USE_LEGACY_FILE_KEY",  "useLegacyFileKey");

	// header values
	config("HEADER_CIPHER_DEFAULT",               "AES-256-CTR");
	config("HEADER_CIPHER_LEGACY",                "AES-128-CFB");
	config("HEADER_ENCODING_BASE64",              "base64");
	config("HEADER_ENCODING_BINARY",              "binary");
	config("HEADER_KEYFORMAT_HASH",               "hash");
	config("HEADER_KEYFORMAT_HASH2",              "hash2");
	config("HEADER_KEYFORMAT_PASSWORD",           "password");
	config("HEADER_OC_ENCRYPTION_MODULE_DEFAULT", "OC_DEFAULT_MODULE");
	config("HEADER_VALUE_FALSE",                  "false");
	config("HEADER_VALUE_TRUE",                   "true");

	// key entrie s
	config("KEY_FILE",      "file");
	config("KEY_ID",        "id");
	config("KEY_NAME",      "name");
	config("KEY_PASSWORDS", "passwords");

	// meta entries
	config("META_ENCRYPTED", "encrypted");
	config("META_IV",        "iv");
	config("META_SIGNATURE", "signature");

	// meta entries tags
	config("META_IV_TAG",            "00iv00");
	config("META_PADDING_TAG_LONG",  "xxx");
	config("META_PADDING_TAG_SHORT", "xx");
	config("META_SIGNATURE_TAG",     "00sig00");

	// ===== HELPER FUNCTIONS =====

	// only define a constant if it does not exist
	function config($key, $value) {
		if (!defined($key)) {
			// overwrite config with environment variable if it is set
			if (false !== getenv($key)) {
				// handle specific environment variables differently
				switch ($key) {
					// handle as arrays
					case "CIPHER_SUPPORT":
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
					case "SUPPORT_MISSING_HEADERS":
						$value = filter_var(getenv($key), FILTER_VALIDATE_BOOLEAN);
						break;

					// handle instanceid specifically
					case "INSTANCEID":
						$value = explode(" ", getenv($key));
						if ((1 === count($value)) && (0 === strlen($value[0]))) {
							$value = null;
						}
						break;

					// handle strings that could be an array
					case "RECOVERY_PASSWORD":
					case "SECRET":
						$value = explode(" ", getenv($key));
						break;

					// handle user password specifically
					case "USER_PASSWORDS":
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

				case "INSTANCEID":
					if (null === $value) {
						$value = searchInstanceIDs();
					} elseif (!is_array($value)) {
						$value = [$value];
					}
					break;

				case "RECOVERY_PASSWORD":
				case "SECRET":
					if (!is_array($value)) {
						$value = [$value];
					}
					break;

				case "USER_PASSWORDS":
					$value = array_change_key_case($value);
					foreach ($value as $name => $password) {
						if (!is_array($value[$name])) {
							$value[$name] = [$password];
						}
					}
					break;
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
			debug("INSTANCEID = ".var_export(INSTANCEID, true));
			debug("RECOVERY_PASSWORD = ".var_export(RECOVERY_PASSWORD, true));
			debug("SECRET = ".var_export(SECRET, true));
			debug("SUPPORT_MISSING_HEADERS = ".var_export(SUPPORT_MISSING_HEADERS, true));
			debug("USER_PASSWORDS = ".var_export(USER_PASSWORDS, true));
		}
	}

	// decrypted JSON-wrapped blobs
	function decryptJson($file) {
		$result = false;

		$parts     = explode("|", $file);
		$partCount = count($parts);

		if (($partCount >= 3) && ($partCount <= 4)) {
			// we only proceed if all strings are hexadecimal
			$proceed = true;
			foreach ($parts as $part) {
				$proceed = ($proceed && ctype_xdigit($part));
			}

			if ($proceed) {
				foreach (SECRET as $secret) {
					$ciphertext = hex2bin($parts[0]);
					$iv         = $parts[1];

					if ($partCount === 4) {
						$version = $parts[3];
						if (intval($version) >= 2) {
							$iv = hex2bin($iv);
						}
						if (intval($version) === 3) {
							$secret = hash_hkdf("sha512",
							                    $secret);
							$secret = substr($secret, 0, 32);
						}
					}

					$secret = hash_pbkdf2("sha1",
					                      $secret,
					                      "phpseclib",
					                      1000,
					                      16,
					                      true);
					$json   = openssl_decrypt($ciphertext,
					                          "aes-128-cbc",
					                          $secret,
					                          OPENSSL_RAW_DATA,
					                          $iv);
					if (false !== $json) {
						$json = json_decode($json, true);
						if (is_array($json)) {
							if (DEBUG_MODE_VERBOSE) {
								debug("json = ".var_export($json, true));
							}

							if (array_key_exists("key", $json)) {
								$result = base64_decode($json["key"]);
							} else {
								debug("decrypted json does not contain key field");
							}
						} else {
							debug("decrypted json has wrong structure");
						}
					} else {
						debug("json could not be decrypted: ".openssl_error_string());
					}

					// exit the loop
					if (false !== $result) {
						break;
					}
				}
			} else {
				debug("json file is not hex-encoded");
			}
		} else {
			debug("json file has wrong structure");
		}

		return $result;
	}

	// parse a private key file and try to decrypt it
	function decryptPrivateKey($file, $password, $keyid) {
		$result = false;

		$header = parseHeader($file, SUPPORT_MISSING_HEADERS);

		// strip header to parse meta data
		$meta = $file;
		if (substr($meta, 0, strlen(HEADER_BEGIN)) === HEADER_BEGIN) {
			$meta = substr($meta, strpos($meta, HEADER_END)+strlen(HEADER_END));
		}
		$meta = parseMetaData($meta);

		if (is_array($header)                           &&
		    is_array($meta)                             &&
		    array_key_exists(HEADER_CIPHER,    $header) &&
		    array_key_exists(HEADER_ENCODING,  $header) &&
		    array_key_exists(HEADER_KEYFORMAT, $header) &&
		    array_key_exists(META_ENCRYPTED,   $meta)   &&
		    array_key_exists(META_IV,          $meta)) {
			// check if we need to generate the password hash
			$iterations = 0;
			switch ($header[HEADER_KEYFORMAT]) {
				case HEADER_KEYFORMAT_HASH:
					$iterations = 100000;
					break;

				case HEADER_KEYFORMAT_HASH2:
					$iterations = 600000;
					break;
			}

			// iterate over the potential combinations
			foreach (INSTANCEID as $instanceid) {
				foreach (SECRET as $secret) {
					// create a working copy of the password
					$pass = $password;

					// if we need to generate the password then do it via PBKDF2 that matches the
					// required key length for the given cipher and the chosen iterations count
					if (0 < $iterations) {
						// required before PHP 8.2
						$salt = hash("sha256", $keyid.$instanceid.$secret, true);
						if ((false !== $salt) && array_key_exists(strtoupper($header[HEADER_CIPHER]), CIPHER_SUPPORT)) {
							$pass = hash_pbkdf2("sha256",
							                    $pass,
							                    $salt,
							                    $iterations,
							                    CIPHER_SUPPORT[strtoupper($header[HEADER_CIPHER])],
							                    true);
						}

						// usable starting with PHP 8.2
						// if ((false !== $salt) && (false !== openssl_cipher_key_length($header[HEADER_CIPHER]))) {
						// 	$pass = hash_pbkdf2("sha256",
						// 	                    $pass,
						// 	                    $salt,
						// 	                    $iterations,
						// 	                    openssl_cipher_key_length($header[HEADER_CIPHER]),
						// 	                    true);
						// }
					}

					$privatekey = openssl_decrypt($meta[META_ENCRYPTED],
					                              $header[HEADER_CIPHER],
					                              $pass,
					                              (HEADER_ENCODING_BINARY === $header[HEADER_ENCODING]) ? OPENSSL_RAW_DATA : 0,
					                              $meta[META_IV]);
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
						debug("privatekey could not be decrypted: ".openssl_error_string());
					}

					// exit the loop
					if (false !== $result) {
						break;
					}
				}

				// exit the loop
				if (false !== $result) {
					break;
				}
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
			$file = file_get_contents_try_json($key[KEY_FILE]);
			if (false !== $file) {
				foreach ($key[KEY_PASSWORDS] as $password) {
					$privatekey = decryptPrivateKey($file, $password, $key[KEY_ID]);
					if (false !== $privatekey) {
						$result[$key[KEY_NAME]] = $privatekey;

						debug("loaded private key for ".$key[KEY_NAME]);
					}
				}
			}
		}

		return $result;
	}

	// try to find and decrypt the secret key for the parsed filename
	function decryptSecretKey($parsed, $privatekeys) {
		$result = null;

		// retrieve all potential key material
		$filekeys  = searchFileKeys($parsed);
		$sharekeys = searchShareKeys($parsed, array_keys($privatekeys));

		foreach ($sharekeys as $keyname => $sharekeynames) {
			foreach ($sharekeynames as $sharekeyname) {
				$sharekey = file_get_contents_try_json($sharekeyname);
				if (false !== $sharekey) {
					// try to decrypt the sharekey
					if (openssl_private_decrypt($sharekey,
					                            $intermediate,
					                            $privatekeys[$keyname],
					                            OPENSSL_PKCS1_PADDING)) {
						// try to decrypt legacy file key first
						foreach ($filekeys as $filekeyname) {
							$filekey = file_get_contents_try_json($filekeyname);
							if (false !== $filekey) {
								$tmpkey = rc4($filekey, $intermediate);
								if (false !== $tmpkey) {
									$result = $tmpkey;
								} else {
									debug("secretkey could not be decrypted from legacy file key...");
								}
							} else {
								debug("filekey could not be read from file...");
							}

							// exit the loop
							if (null !== $result) {
								break;
							}
						}
					} else {
						debug("openssl_private_decrypt() failed: ".openssl_error_string());
						debug("sharekey could not be decrypted as intermediate key...");
					}

					// try to decrypt the new share key second,
					// we also do this when there is a file key in case it is a leftover
					if (null === $result) {
						if (openssl_private_decrypt($sharekey,
						                            $tmpkey,
						                            $privatekeys[$keyname],
						                            OPENSSL_PKCS1_OAEP_PADDING)) {
							$result = $tmpkey;
						} else {
							debug("openssl_private_decrypt() failed: ".openssl_error_string());
							debug("sharekey could not be decrypted as secret key...");
						}
					}
				} else {
					debug("sharekey could not be read from file...");
				}

				// exit the loop
				if (null !== $result) {
					break;
				}
			}

			// exit the loop
			if (null !== $result) {
				break;
			}
		}

		return $result;
	}

	// read a file and automagically try to decrypt it in case it is a JSON-wrapped blob
	function file_get_contents_try_json($filename) {
		$result = file_get_contents($filename);

		if (false !== $result) {
			$tmp = decryptJson($result);
			if (false !== $tmp) {
				$result = $tmp;
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
			$cwd = array();

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

	// try to parse the file header
	function parseHeader($file, $supportMissingHeaders) {
		$result = [];

		if ((0 === strpos($file, HEADER_BEGIN)) && (false !== strpos($file, HEADER_END))) {
			// prepare default values
			$result[HEADER_CIPHER]               = HEADER_CIPHER_LEGACY;
			$result[HEADER_ENCODING]             = HEADER_ENCODING_BASE64;
			$result[HEADER_KEYFORMAT]            = HEADER_KEYFORMAT_PASSWORD;
			$result[HEADER_OC_ENCRYPTION_MODULE] = HEADER_OC_ENCRYPTION_MODULE_DEFAULT;
			$result[HEADER_SIGNED]               = HEADER_VALUE_FALSE;
			$result[HEADER_USE_LEGACY_FILE_KEY]  = HEADER_VALUE_TRUE;

			// extract content between HBEGIN and HEND
			$header = substr($file, strlen(HEADER_BEGIN), strpos($file, HEADER_END)-strlen(HEADER_BEGIN));

			// get array from header
			$exploded = explode(":", $header);

			// unset leading and trailing empty entries
			// which stem from the separators after
			// HBEGIN and before HEND
			array_pop($exploded);
			array_shift($exploded);

			while (0 < count($exploded)) {
				$key   = array_shift($exploded);
				$value = array_shift($exploded);

				// we do not set empty values
				if ((0 < strlen($key ?? "")) && (0 < strlen($value ?? ""))) {
					$result[$key] = $value;
				}
			}
		} elseif ($supportMissingHeaders) {
			// prepare default values
			$result[HEADER_CIPHER]               = HEADER_CIPHER_LEGACY;
			$result[HEADER_ENCODING]             = HEADER_ENCODING_BASE64;
			$result[HEADER_KEYFORMAT]            = HEADER_KEYFORMAT_PASSWORD;
			$result[HEADER_OC_ENCRYPTION_MODULE] = HEADER_OC_ENCRYPTION_MODULE_DEFAULT;
			$result[HEADER_SIGNED]               = HEADER_VALUE_FALSE;
			$result[HEADER_USE_LEGACY_FILE_KEY]  = HEADER_VALUE_TRUE;

			debug("key is using legacy format, setting default values...");
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("header = ".var_export($result, true));
		}

		return $result;
	}

	// try to parse the block
	//
	// the structure WITH signature is:
	//
	// 00iv00................00sig00...
	// ................................
	// .............................xxx
	//
	// the structure WITHOUT signature is:
	//
	// 00iv00................xx
	//
	function parseMetaData($file) {
		$result = [];

		// check if there is a signature in the block
		if (false !== strpos(substr($file, -74), META_SIGNATURE_TAG)) {
			// remove the long padding from the block
			if (META_PADDING_TAG_LONG === substr($file, -3)) {
				$file = substr($file, 0, -3);
			}
			$meta = substr($file, -93);

			$result[META_ENCRYPTED] = substr($file, 0, -93);
			$result[META_IV]        = substr($meta, strlen(META_IV_TAG), 16);
			$result[META_SIGNATURE] = substr($meta, 22+strlen(META_SIGNATURE_TAG));

		} else {
			// remove the short padding from the block
			if (META_PADDING_TAG_SHORT === substr($file, -2)) {
				$file = substr($file, 0, -2);
			}
			$meta = substr($file, -22);

			$result[META_ENCRYPTED] = substr($file, 0, -22);
			$result[META_IV]        = substr($meta, -16);
			$result[META_SIGNATURE] = false;
		}

		if (DEBUG_MODE_VERBOSE) {
			// prepare array for debugging
			$debug_result = [META_ENCRYPTED => shortenString(bin2hex($result[META_ENCRYPTED]), 131, "...")." (".strlen($result[META_ENCRYPTED])." bytes)",
			                 META_IV        => bin2hex($result[META_IV]),
			                 META_SIGNATURE => $result[META_SIGNATURE]];
			debug("meta = ".var_export($debug_result, true));
		}

		return $result;
	}

	// make sure that all configuration values exist
	function prepareConfig() {
		// nextcloud definitions
		config("DATADIRECTORY", getcwd());
		config("SECRET",        []);

		// instanceid definition,
		// will populate the value with the
		// result from searchInstanceIDs()
		config("INSTANCEID", null);

		// recovery password definition
		config("RECOVERY_PASSWORD", []);

		// user password definition
		config("USER_PASSWORDS", []);

		// external storage definition
		config("EXTERNAL_STORAGES", []);

		// missing headers definition
		config("SUPPORT_MISSING_HEADERS", false);

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

	// hands-down implementation of RC4
	function rc4($data, $secret) {
		$result = false;

		// initialize $state
		$state = [];
		for ($i = 0x00; $i <= 0xFF; $i++) {
			$state[$i] = $i;
		}

		// mix $secret into $state
		$indexA = 0x00;
		$indexB = 0x00;
		for ($i = 0x00; $i <= 0xFF; $i++) {
			$indexB = ($indexB + ord($secret[$indexA]) + $state[$i]) % 0x100;

			$tmp            = $state[$i];
			$state[$i]      = $state[$indexB];
			$state[$indexB] = $tmp;

			$indexA = ($indexA + 0x01) % strlen($secret);
		}

		// decrypt $data with $state
		$indexA = 0x00;
		$indexB = 0x00;
		$result = "";
		for ($i = 0x00; $i < strlen($data); $i++) {
			$indexA = ($indexA + 0x01) % 0x100;
			$indexB = ($state[$indexA] + $indexB) % 0x100;

			$tmp            = $state[$indexA];
			$state[$indexA] = $state[$indexB];
			$state[$indexB] = $tmp;

			$result .= chr(ord($data[$i]) ^ $state[($state[$indexA] + $state[$indexB]) % 0x100]);
		}

		return $result;
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

	// test different filename structures for the filekey
	function searchFileKeys($parsed) {
		$result = [];

		if (is_array($parsed)) {
			if (array_key_exists(FILE_NAME,          $parsed) &&
			    array_key_exists(FILE_NAME_RAW,      $parsed) &&
			    array_key_exists(FILE_TRASHBIN,      $parsed) &&
			    array_key_exists(FILE_TRASHBIN_TIME, $parsed) &&
			    array_key_exists(FILE_USERNAME,      $parsed)) {
				// set trashbin path
				$trashbin = ($parsed[FILE_TRASHBIN]) ? "files_trashbin" : "";

				$filekeys = [[DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/keys/".$trashbin."/files/", $parsed[FILE_NAME],     "/OC_DEFAULT_MODULE/fileKey"],
				             [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/keys/".$trashbin."/",       $parsed[FILE_NAME],     "/fileKey"],
				             [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/".$trashbin."/keyfiles/",   $parsed[FILE_NAME],     ".key"],
				             [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/keyfiles/",                    $parsed[FILE_NAME],     ".key"],
				             [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/keyfiles/",                    $parsed[FILE_NAME_RAW], ".key.d".$parsed[FILE_TRASHBIN_TIME]],
				             [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/keys/",                        $parsed[FILE_NAME],     "/fileKey"]];

				foreach ($filekeys as $filekey) {
					// try default locations
					if (is_file(normalizePath(implode("", $filekey)))) {
						$result[] = normalizePath(implode("", $filekey));
					}

					// check if we can find a file with the encryption suffix
					if (is_file(normalizePath($filekey[0].$filekey[1].ENCRYPTION_SUFFIX.$filekey[2]))) {
						$result[] = normalizePath($filekey[0].$filekey[1].ENCRYPTION_SUFFIX.$filekey[2]);
					}

					// check if we can find a folder with the decryption or encryption infix
					$filelist = recursiveScandir(dirname(normalizePath($filekey[0].$filekey[1])), false);
					foreach ($filelist as $filename) {
						if (1 === preg_match("@^".preg_quote(normalizePath($filekey[0].$filekey[1].DECRYPTION_INFIX), "@")."[0-9]+$@", $filename, $matches)) {
							if (is_file(normalizePath($filename.$filekey[2]))) {
								$result[] = normalizePath($filename.$filekey[2]);
							}
						} elseif (1 === preg_match("@^".preg_quote(normalizePath($filekey[0].$filekey[1].ENCRYPTION_INFIX), "@")."[0-9]+$@", $filename, $matches)) {
							if (is_file(normalizePath($filename.$filekey[2]))) {
								$result[] = normalizePath($filename.$filekey[2]);
							}
						}
					}
				}
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("filekeys = ".var_export($result, true));
		}

		return $result;
	}

	// search for appdata folders to identify instanceids
	function searchInstanceIDs() {
		$result = [];

		$folderlist = recursiveScandir(DATADIRECTORY, false);
		foreach ($folderlist as $foldername) {
			if (is_dir($foldername)) {
				if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/appdata_(?<instanceid>[0-9A-Za-z]+)$@", $foldername, $matches)) {
					$result[] = $matches["instanceid"];
				}
			}
		}

		return $result;
	}

	// test different filename structures for the sharekey
	function searchShareKeys($parsed, $keynames) {
		$result = [];

		if (is_array($parsed) && is_array($keynames)) {
			if (array_key_exists(FILE_NAME,          $parsed) &&
			    array_key_exists(FILE_NAME_RAW,      $parsed) &&
			    array_key_exists(FILE_TRASHBIN,      $parsed) &&
			    array_key_exists(FILE_TRASHBIN_TIME, $parsed) &&
			    array_key_exists(FILE_USERNAME,      $parsed)) {
				// set trashbin path
				$trashbin = ($parsed[FILE_TRASHBIN]) ? "files_trashbin" : "";

				foreach ($keynames as $keyname) {
					// prepare result
					$result[$keyname] = [];

					$sharekeys = [[DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/keys/".$trashbin."/files/", $parsed[FILE_NAME],     "/OC_DEFAULT_MODULE/".$keyname.".shareKey"],
					              [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/keys/".$trashbin."/",       $parsed[FILE_NAME],     "/".$keyname.".shareKey"],
					              [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/files_encryption/".$trashbin."/share-keys/", $parsed[FILE_NAME],     ".".$keyname.".shareKey"],
					              [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/share-keys/",                  $parsed[FILE_NAME],     ".".$keyname.".shareKey"],
					              [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/share-keys/",                  $parsed[FILE_NAME_RAW], ".".$keyname.".shareKey.d".$parsed[FILE_TRASHBIN_TIME]],
					              [DATADIRECTORY."/".$parsed[FILE_USERNAME]."/".$trashbin."/keys/",                        $parsed[FILE_NAME],     "/".$keyname.".shareKey"]];

					foreach ($sharekeys as $sharekey) {
						// try default locations
						if (is_file(normalizePath(implode("", $sharekey)))) {
							$result[$keyname][] = normalizePath(implode("", $sharekey));
						}

						// check if we can find a file with the encryption suffix
						if (is_file(normalizePath($sharekey[0].$sharekey[1].ENCRYPTION_SUFFIX.$sharekey[2]))) {
							$result[$keyname][] = normalizePath($sharekey[0].$sharekey[1].ENCRYPTION_SUFFIX.$sharekey[2]);
						}

						// check if we can find a folder with the decryption or encryption infix
						$filelist = recursiveScandir(dirname(normalizePath($sharekey[0].$sharekey[1])), false);
						foreach ($filelist as $filename) {
							if (1 === preg_match("@^".preg_quote(normalizePath($sharekey[0].$sharekey[1].DECRYPTION_INFIX), "@")."[0-9]+$@", $filename, $matches)) {
								if (is_file(normalizePath($filename.$sharekey[2]))) {
									$result[$keyname][] = normalizePath($filename.$sharekey[2]);
								}
							} elseif (1 === preg_match("@^".preg_quote(normalizePath($sharekey[0].$sharekey[1].ENCRYPTION_INFIX), "@")."[0-9]+$@", $filename, $matches)) {
								if (is_file(normalizePath($filename.$sharekey[2]))) {
									$result[$keyname][] = normalizePath($filename.$sharekey[2]);
								}
							}
						}
					}
				}
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("sharekeys = ".var_export($result, true));
		}

		return $result;
	}

	// test different filename structures for the system keys
	function searchSystemKeys() {
		$result = [];

		$systemdirs = [normalizePath(DATADIRECTORY."/files_encryption/OC_DEFAULT_MODULE/"),
		               normalizePath(DATADIRECTORY."/files_encryption/"),
		               normalizePath(DATADIRECTORY."/owncloud_private_key/")];

		foreach ($systemdirs as $systemdir) {
			$filelist = recursiveScandir($systemdir, false);
			foreach ($filelist as $filename) {
				if (is_file($filename)) {
					if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/files_encryption/(OC_DEFAULT_MODULE/)?(?<keyname>master_[0-9a-z]+)\.privateKey$@", $filename, $matches)) {
						$result[] = [KEY_FILE      => $filename,
						             KEY_ID        => $matches["keyname"],
						             KEY_NAME      => $matches["keyname"],
						             KEY_PASSWORDS => SECRET];
					} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/files_encryption/(OC_DEFAULT_MODULE/)?(?<keyname>pubShare_[0-9a-z]+)\.privateKey$@", $filename, $matches)) {
						$result[] = [KEY_FILE      => $filename,
						             KEY_ID        => "",
						             KEY_NAME      => $matches["keyname"],
						             KEY_PASSWORDS => [""]];
					} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/files_encryption/(OC_DEFAULT_MODULE/)?(?<keyname>recovery(Key)?_[0-9a-z]+)\.privateKey$@", $filename, $matches)) {
						$result[] = [KEY_FILE      => $filename,
						             KEY_ID        => "",
						             KEY_NAME      => $matches["keyname"],
						             KEY_PASSWORDS => RECOVERY_PASSWORD];
					} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/owncloud_private_key/(?<keyname>pubShare_[0-9a-z]+)\.private\.key$@", $filename, $matches)) {
						$result[] = [KEY_FILE      => $filename,
						             KEY_ID        => "",
						             KEY_NAME      => $matches["keyname"],
						             KEY_PASSWORDS => [""]];
					} elseif (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/owncloud_private_key/(?<keyname>recovery(Key)?_[0-9a-z]+)\.private\.key$@", $filename, $matches)) {
						$result[] = [KEY_FILE      => $filename,
						             KEY_ID        => "",
						             KEY_NAME      => $matches["keyname"],
						             KEY_PASSWORDS => RECOVERY_PASSWORD];
					}
				}
			}
		}

		if (DEBUG_MODE_VERBOSE) {
			debug("systemkeys = ".var_export($result, true));
		}

		return $result;
	}

	// test different filename structures for the user keys
	function searchUserKeys() {
		$result = [];

		$filelist = recursiveScandir(DATADIRECTORY, false);
		foreach ($filelist as $filename) {
			if (is_dir($filename)) {
				if (1 === preg_match("@^".preg_quote(DATADIRECTORY, "@")."/(?<username>[0-9A-Za-z\.\-\_\@]+)$@", $filename, $matches)) {
					if (array_key_exists(strtolower($matches["username"]), USER_PASSWORDS)) {
						$userfiles = [normalizePath(DATADIRECTORY."/".$matches["username"]."/files_encryption/OC_DEFAULT_MODULE/".$matches["username"].".privateKey"),
						              normalizePath(DATADIRECTORY."/".$matches["username"]."/files_encryption/".$matches["username"].".privateKey"),
						              normalizePath(DATADIRECTORY."/".$matches["username"]."/files_encryption/".$matches["username"].".private.key")];

						foreach ($userfiles as $userfile) {
							if (is_file($userfile)) {
								$result[] = [KEY_FILE      => $userfile,
								             KEY_ID        => $matches["username"],
								             KEY_NAME      => $matches["username"],
								             KEY_PASSWORDS => USER_PASSWORDS[strtolower($matches["username"])]];
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

	// check if a file has a header and if not copy it to the target
	function copyFile($filename, $targetname) {
		$result = false;

		// try to set file times later on
		$fileatime = fileatime($filename);
		$filemtime = filemtime($filename);

		// we will not try to copy encrypted files
		$isplain = false;

		$sourcefile = fopen($filename, "r");
		try {
			$buffer = "";
			$tmp    = "";
			do {
				$tmp = fread($sourcefile, BLOCKSIZE);
				if (false !== $tmp) {
					$buffer .= $tmp;
				}
			} while ((BLOCKSIZE > strlen($buffer)) && (!feof($sourcefile)));

			// check if the source file does not start with a header
			$header  = parseHeader(substr($buffer, 0, BLOCKSIZE), false);
			$isplain = (0 === count($header));
		} finally {
			fclose($sourcefile);
		}

		if ($isplain) {
			$result = copy($filename, $targetname);

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

	// decrypt a single file block
	function decryptBlock($header, $block, $secretkey) {
		$result = false;

		$meta = parseMetaData($block);

		if (is_array($header) && is_array($meta)) {
			if (array_key_exists(HEADER_CIPHER,   $header) &&
			    array_key_exists(HEADER_ENCODING, $header) &&
			    array_key_exists(META_ENCRYPTED,  $meta)   &&
			    array_key_exists(META_IV,         $meta)) {
				$output = openssl_decrypt($meta[META_ENCRYPTED],
				                          $header[HEADER_CIPHER],
				                          $secretkey,
				                          (HEADER_ENCODING_BINARY === $header[HEADER_ENCODING]) ? OPENSSL_RAW_DATA : 0,
				                          $meta[META_IV]);
				if (false !== $output) {
					$result = $output;
				} else {
					debug("block could not be decrypted: ".openssl_error_string());
				}
			}
		}

		return $result;
	}

	// try to decrypt a file
	function decryptFile($filename, $secretkey, $targetname) {
		$result = false;

		// try to set file times later on
		$fileatime = fileatime($filename);
		$filemtime = filemtime($filename);

		$sourcefile = fopen($filename,   "r");
		$targetfile = fopen($targetname, "w");
		try {
			$result = true;

			$block  = "";
			$buffer = "";
			$first  = true;
			$header = null;
			$plain  = "";
			$tmp    = "";
			do {
				$tmp = fread($sourcefile, BLOCKSIZE);
				if (false !== $tmp) {
					$buffer .= $tmp;

					while (BLOCKSIZE <= strlen($buffer)) {
						$block  = substr($buffer, 0, BLOCKSIZE);
						$buffer = substr($buffer, BLOCKSIZE);

						// the first block contains the header
						if ($first) {
							$first  = false;
							$header = parseHeader($block, SUPPORT_MISSING_HEADERS);
						} else {
							$plain = decryptBlock($header, $block, $secretkey);
							if (false !== $plain) {
								// write fails when fewer bytes than string length are written
								$result = $result && (strlen($plain) === fwrite($targetfile, $plain));
							} else {
								// decryption failed
								$result = false;
							}
						}
					}
				}
			} while (!feof($sourcefile));

			// decrypt trailing blocks
			while (0 < strlen($buffer)) {
				$block  = substr($buffer, 0, BLOCKSIZE);
				$buffer = substr($buffer, BLOCKSIZE);

				// the file only has 1 block, parsing the header before decryption
				if ($first) {
					$first  = false;
					$header = parseHeader($block, SUPPORT_MISSING_HEADERS);
				}

				$plain = decryptBlock($header, $block, $secretkey);
				if (false !== $plain) {
					// write fails when fewer bytes than string length are written
					$result = $result && (strlen($plain) === fwrite($targetfile, $plain));
				} else {
					// decryption failed
					$result = false;
				}
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

		return $result;
	}

	// iterate over the file lists and try to decrypt the files
	function decryptFiles($targetdir, $sourcepaths = null) {
		$result = true;

		// try to find and decrypt all available private keys
		$privatekeys = decryptPrivateKeys();
		if (0 >= count($privatekeys)) {
			println("WARNING: COULD NOT DECRYPT ANY PRIVATE KEY");
		}

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
					if (null === $source_name) {
						$targetname = normalizePath($targetdir."/".substr($filename, strlen(DATADIRECTORY)));
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

						$targetname = normalizePath($targetdir."/".$username."/".EXTERNAL_PREFIX.$foldername."/".substr($filename, strlen($source_path)));
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

							// try to find and decrypt the fitting secret key
							$secretkey = decryptSecretKey($parsed, $privatekeys);
							debug("secretkey = ".((null !== $secretkey) ? "available" : "unavailable"));

							// if the file provides all relevant key material then we try to decrypt it
							if (null !== $secretkey) {
								debug("trying to decrypt file...");

								$success = decryptFile($filename, $secretkey, $targetname);
							} else {
								debug("cannot decrypt this file...");
							}

							// if the file does not provide all relevant key material or if the file
							// could not be decrypted (maybe due to missing headers) we try to copy it,
							// but we copy it only if it does not contain an encryption header and only
							// if the decryption hasn't created a file already or if the created file
							// does not have any content
							if ((!$success) && ((!is_file($targetname)) || (0 >= filesize($targetname)))) {
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
							debug("skipping this file because the filename structure is not unknown...");
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

		// prevent executiong on Windows, we will need function calls
		// and path identification that are only tested on Linux
		if ("Windows" !== PHP_OS_FAMILY) {
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
						if (!decryptFiles($targetdir, $sourcepaths)) {
							println("ERROR: AN ERROR OCCURED DURING THE DECRYPTION");
							$result = 4;
						}
					} else {
						println("ERROR: TARGETDIR NOT GIVEN OR DOES NOT EXIST");
						$result = 3;
					}
				} else {
					println("ERROR: DATADIRECTORY DOES NOT EXIST");
					$result = 2;
				}

				debug("exiting");
			} else {
				printHelp();
			}
		} else {
			println("ERROR: DO NOT EXECUTE ON WINDOWS, USE THE WINDOWS SUBSYSTEM FOR LINUX INSTEAD");
			$result = 1;
		}

		return $result;
	}

	// do not execute main() when we are in TESTING mode
	if ((!defined("TESTING")) && (!getenv("TESTING"))) {
		// main entrypoint
		exit(main($argv));
	}

