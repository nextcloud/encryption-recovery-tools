<?php
final class prepareConfig extends PHPUnit\Framework\TestCase {
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		prepareConfig();

		// test user configuration
		self::assertTrue(defined("DATADIRECTORY"));
		self::assertTrue(defined("USER_MNEMONICS"));
		self::assertTrue(defined("EXTERNAL_STORAGES"));
		self::assertTrue(defined("DEBUG_MODE"));
		self::assertTrue(defined("DEBUG_MODE_VERBOSE"));

		// test system definitions
		self::assertTrue(defined("BLOCKSIZE"));
		self::assertTrue(defined("TAGSIZE"));
		self::assertTrue(defined("EXTERNAL_PREFIX"));
		self::assertTrue(defined("FILE_FILE"));
		self::assertTrue(defined("FILE_NAME"));
		self::assertTrue(defined("FILE_NAME_RAW"));
		self::assertTrue(defined("FILE_TRASHBIN"));
		self::assertTrue(defined("FILE_TRASHBIN_TIME"));
		self::assertTrue(defined("FILE_USERNAME"));
		self::assertTrue(defined("FILE_VERSION"));
		self::assertTrue(defined("FILE_VERSION_TIME"));
		self::assertTrue(defined("KEY_FILE"));
		self::assertTrue(defined("KEY_MNEMONICS"));
		self::assertTrue(defined("KEY_NAME"));
		self::assertTrue(defined("METADATA_CHECKSUM"));
		self::assertTrue(defined("METADATA_ENCRYPTED"));
		self::assertTrue(defined("METADATA_FILENAME"));
		self::assertTrue(defined("METADATA_FILES"));
		self::assertTrue(defined("METADATA_IV"));
		self::assertTrue(defined("METADATA_KEY"));
		self::assertTrue(defined("METADATA_METADATA"));
		self::assertTrue(defined("METADATA_METADATAKEY"));
		self::assertTrue(defined("METADATA_MIMETYPE"));
		self::assertTrue(defined("METADATA_TAG"));
		self::assertTrue(defined("METADATA_VERSION"));
	}
}
