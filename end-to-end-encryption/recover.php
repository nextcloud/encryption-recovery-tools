#!/usr/bin/env php
<?php

	# ./end-to-end-encryption/recover.php
	#
	# Copyright (c) 2023,      Yahe <hello@yahe.sh>
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
	# INSTANCEID              this is a value from the Nextcloud configuration file,
	# (REQUIRED)              there does not seem to be another way to retrieve this
	#                         value
	#
	# SECRET                  this is a value from the Nextcloud configuration file,
	# (REQUIRED)              there does not seem to be another way to retrieve this
	#                         value
	#
	# USER_MNEMONICS          these are the mnemonics for the user keys that have been
	# (REQUIRED)              set by the Nextcloud client when creating the end-to-end
	#                         encryption keys of the users, each value represents a
	#                         (username, password) pair and you can set as many pairs
	#                         as necessary
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
	#
	# environment variables:
	# ======================
	#
	# All configuration values can alternatively be provided through environment
	# variables and superseed the information provided within the script. Lists like
	# EXTERNAL_STORAGES and USER_MNEMONICS must be provided as space-separated
	# strings.
	#
	# Example: if two user mnemonicss shall be provided through an environment
	#          variable then the corresponding value has to be set as:
	#
	#          USER_MNEMONICS="user1=mnemonic1 user2=mnemonic2"
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
	config("DATADIRECTORY", "~/github/encryption-recovery-tools/tests/data/end-to-end-encryption/e2e/data/");
	config("INSTANCEID",    "ocv57gqqtmlg");
	config("SECRET",        "z14f8YV8qL0v+mUMo6EGUVhbWYarZWSys7Xc0qpEUJDGixcW");

config("USER_MNEMONICS", ["admin" => "member arm belt cute depend pull borrow rigid thank humble space illness"]);
config("DEBUG_MODE", true);
config("DEBUG_MODE_VERBOSE", true);

	// user password definition,
	// replace "username" with the actual usernames and "password" with the actual passwords,
	// you can add or remove entries as necessary
	config("USER_MNEMONICS", ["username" => "mnemonic",
	                          "username" => "mnemonic",
	                          "username" => "mnemonic"]);

	// external storage definition,
	// replace "storage" with the actual external storage names and "/mountpath" with the actual external storage mount paths,
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
	config("BLOCKSIZE", 1024);
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

	// metadata entries
	config("METADATA_CHECKSUM",    "checksum");
	config("METADATA_ENCRYPTED",   "encrypted");
	config("METADATA_FILENAME",    "filename");
	config("METADATA_FILES",       "files");
	config("METADATA_IV",          "initializationVector");
	config("METADATA_KEY",         "key");
	config("METADATA_METADATA",    "metadata");
	config("METADATA_METADATAKEY", "metadataKey");
	config("METADATA_MIMETYPE",    "mimetype");
	config("METADATA_TAG",         "authenticationTag");
	config("METADATA_VERSION",     "version");

	// ===== HELPER FUNCTIONS =====

	// we need a specific implementation of RSA that is not provided by PHP
	require_once(__DIR__."/vendor/autoload.php");
	use phpseclib3\Crypt\RSA;

	// only define a constant if it does not exist
	function config($key, $value) {
		if (!defined($key)) {
			// overwrite config with environment variable if it is set
			if (getenv($key)) {
				// handle specific environment variables differently
				switch ($key) {
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
						$value = filter_var(getenv($key), FILTER_VALIDATE_BOOLEAN);
						break;

					// handle strings that could be an array
					case "INSTANCEID":
					case "SECRET":
						$value = explode(" ", getenv($key));
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
				case "SECRET":
					if (!is_array($value)) {
						$value = [$value];
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
	function convertGCMtoCTR($iv, $key, $algo) {
		$result = null;

		// check special case first
		if (0x0C === strlen($iv)) {
			$result = $iv.hex2bin("00000002");
		} else {
			// produce GHASH of the nonce
			$subkey = openssl_encrypt(hex2bin("00000000000000000000000000000000"),
			                          $algo,
			                          $key,
			                          OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
			if (false !== $subkey) {
				// store for later use
				$ivlen = strlen($iv);

				// pad iv to 128 bit block
				if (0x00 !== ($ivlen % 0x10)) {
					$iv = $iv.str_repeat(hex2bin("00"), 0x10 - ($ivlen % 0x10));
				}

				// append zero padding
				$iv = $iv.hex2bin("0000000000000000");

				// append 64-bit iv length
				$iv = $iv.hex2bin("00000000").pack("N", ($ivlen << 0x03));

				// actual GHASH calculation
				$result = hex2bin("00000000000000000000000000000000");
				for ($i = 0x00; $i < strlen($iv)/0x10; $i++) {
					$block  = $result ^ substr($iv, $i * 0x10, 0x10);
					$tmp    = hex2bin("00000000000000000000000000000000");
					$tmpkey = $subkey;

					// execute the multipliation
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

				// add 0x01 to the result
				$remainder = 0x01;
				for ($index = strlen($result)-0x01; $index >= 0x00; $index--) {
					$tmp            = (((ord($result[$index]) + $remainder) >> 0x08) & 0xFF);
					$result[$index] = chr((ord($result[$index]) + $remainder) & 0xFF);
					$remainder      = $tmp;
				}
			}
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
			debug("INSTANCEID = ".var_export(INSTANCEID, true));
			debug("SECRET = ".var_export(SECRET, true));
			debug("USER_MNEMONICS = ".var_export(USER_MNEMONICS, true));
		}
	}

	// parse a metadata file and try to decrypt it
	function decryptMetaDate($file, $privatekeys) {
		$result = false;

		$json = json_decode($file,
		                    true,
		                    4,
		                    JSON_OBJECT_AS_ARRAY);
		if (is_array($json) &&
		    array_key_exists(METADATA_FILES,    $json) &&
		    array_key_exists(METADATA_METADATA, $json) &&
		    is_array($json[METADATA_FILES])            &&
		    is_array($json[METADATA_METADATA])) {
			// try to decrypt the metadata key
			$key = null;
			foreach ($privatekeys as $privatekey) {
				if (array_key_exists(METADATA_METADATAKEY, $json[METADATA_METADATA])) {
					$tmp = base64_decode($json[METADATA_METADATA][METADATA_METADATAKEY]);
					if (false !== $tmp) {
						$tmp = RSA::loadPrivateKey($privatekey)
						       ->withPadding(RSA::ENCRYPTION_OAEP)
						       ->withHash("sha256")
						       ->withMGFHash("sha256")
						       ->decrypt($tmp);
						if (false !== $tmp) {
							// yes, this really is base64-encoded several times
							$key = base64_decode(base64_decode($tmp));
						} else {
							debug("metadata key could not be decrypted...");
						}
					}
				}

				// exit the lopp
				if (null !== $key) {
					break;
				}
			}

			if (null !== $key) {
				$result = [];

				foreach ($json[METADATA_FILES] as $filename => $file) {
					if (array_key_exists(METADATA_ENCRYPTED, $file) &&
					    array_key_exists(METADATA_IV,        $file) &&
					    array_key_exists(METADATA_TAG,       $file)) {
						// extract parts of the metadata
						$parts = null;
						if (false !== strpos($file[METADATA_ENCRYPTED], "|")) {
							$parts = explode("|", $file[METADATA_ENCRYPTED]);
						}

						// we at least need two parts
						if ((is_array($parts)) && (2 <= count($parts))) {
							// parse the metadata structure
							$ciphertext = substr(base64_decode($parts[0]), 0, -TAGSIZE);
							$iv         = base64_decode($parts[1]);
							$tag        = substr(base64_decode($parts[0]), -TAGSIZE);

							// migrate GCM nonce to CTR counter,
							// we don't use GCM so that broken
							// integrity data do not break the
							// decryption
							$iv = convertGCMtoCTR($iv, $key, "aes-128-ecb");

							// decrypt metadata
							$metadata = openssl_decrypt($ciphertext,
							                            "aes-128-ctr",
							                            $key,
							                            OPENSSL_RAW_DATA,
							                            $iv);
							if (false !== $metadata) {
								$metadata = base64_decode($metadata);
								if (false !== $metadata) {
									var_dump($metadata);
								} else {
									debug("decrypted metadata are not base64-encoded");
								}
							} else {
								debug("metadata could not be decrypted: ".openssl_error_string());
							}
						} else {
							debug("encrypted metadata have wrong structure");
						}
					} else {
						debug("metadata file entry has wrong structure");
					}
				}
			} else {
				debug("metadata key could not be decrypted");
			}
		} else {
			debug("metadata file has wrong structure");
		}

		return $result;
	}

	// try to decrypt all available metadata files
	function decryptMetaData($privatekeys) {
		$result = [];

		$files = searchMetaData();
		foreach ($files as $filename) {
			$file = file_get_contents_try_json($filename);
			if (false !== $file) {
				$metadate = decryptMetaDate($file, $privatekeys);
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
			$iv         = base64_decode($parts[1]);
			$salt       = base64_decode($parts[2]);
			$tag        = substr(base64_decode($parts[0]), -TAGSIZE);

			// derive actual secret
			$mnemonic = hash_pbkdf2("sha1",
			                        $mnemonic,
			                        $salt,
			                        1024,
			                        32,
			                        true);

			// migrate GCM nonce to CTR counter,
			// we don't use GCM so that broken
			// integrity data do not break the
			// decryption
			$iv = convertGCMtoCTR($iv, $mnemonic, "aes-256-ecb");

			// decrypt private key
			$privatekey = openssl_decrypt($ciphertext,
			                              "aes-256-ctr",
			                              $mnemonic,
			                              OPENSSL_RAW_DATA,
			                              $iv);
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

	// make sure that all configuration values exist
	function prepareConfig() {
		// nextcloud definitions
		config("DATADIRECTORY", getcwd());
		config("INSTANCEID",    []);
		config("SECRET",        []);

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

	// test different filename structures for the metadata files
	function searchMetaData() {
		$result = [];

		foreach (INSTANCEID as $instanceid) {
			// potential metadata path
			$metadatapath = normalizePath(DATADIRECTORY."/appdata_".$instanceid."/end_to_end_encryption/meta-data/");

			$filelist = recursiveScandir($metadatapath, true);
			foreach ($filelist as $filename) {
				if (is_file($filename)) {
					if (1 === preg_match("@^".preg_quote($metadatapath, "@")."/.+/meta\.data$@", $filename)) {
						$result[] = $filename;
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

		foreach (INSTANCEID as $instanceid) {
			// potential key path
			$keypath = normalizePath(DATADIRECTORY."/appdata_".$instanceid."/end_to_end_encryption/private-keys/");

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

		// try to decrypt as many metadata as possible
		$metadata = decryptMetaData($privatekeys);
		if (0 >= count($metadata)) {
			println("WARNING: COULD NOT DECRYPT ANY META DATA");
		}

/*

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
*/

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

	// do not execute main() when we in TESTING mode
	if ((!defined("TESTING")) && (!getenv("TESTING"))) {
		// main entrypoint
		exit(main($argv));
	}

