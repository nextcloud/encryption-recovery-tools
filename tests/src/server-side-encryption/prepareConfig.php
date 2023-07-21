<?php
final class prepareConfig extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		prepareConfig();

		// test user configuration
		self::assertTrue(defined("DATADIRECTORY"));
		self::assertTrue(defined("INSTANCEID"));
		self::assertTrue(defined("SECRET"));
		self::assertTrue(defined("RECOVERY_PASSWORD"));
		self::assertTrue(defined("USER_PASSWORDS"));
		self::assertTrue(defined("EXTERNAL_STORAGES"));
		self::assertTrue(defined("SUPPORT_MISSING_HEADERS"));
		self::assertTrue(defined("DEBUG_MODE"));
		self::assertTrue(defined("DEBUG_MODE_VERBOSE"));

		// test system definitions
		self::assertTrue(defined("BLOCKSIZE"));
		self::assertTrue(defined("CIPHER_SUPPORT"));
		self::assertTrue(defined("ENCRYPTION_INFIX"));
		self::assertTrue(defined("EXTERNAL_PREFIX"));
		self::assertTrue(defined("HEADER_BEGIN"));
		self::assertTrue(defined("HEADER_CIPHER"));
		self::assertTrue(defined("HEADER_END"));
		self::assertTrue(defined("HEADER_ENCODING"));
		self::assertTrue(defined("HEADER_KEYFORMAT"));
		self::assertTrue(defined("HEADER_OC_ENCRYPTION_MODULE"));
		self::assertTrue(defined("HEADER_SIGNED"));
		self::assertTrue(defined("HEADER_USE_LEGACY_FILE_KEY"));
		self::assertTrue(defined("HEADER_CIPHER_DEFAULT"));
		self::assertTrue(defined("HEADER_CIPHER_LEGACY"));
		self::assertTrue(defined("HEADER_ENCODING_BASE64"));
		self::assertTrue(defined("HEADER_ENCODING_BINARY"));
		self::assertTrue(defined("HEADER_KEYFORMAT_HASH"));
		self::assertTrue(defined("HEADER_KEYFORMAT_HASH2"));
		self::assertTrue(defined("HEADER_KEYFORMAT_PASSWORD"));
		self::assertTrue(defined("HEADER_OC_ENCRYPTION_MODULE_DEFAULT"));
		self::assertTrue(defined("HEADER_VALUE_FALSE"));
		self::assertTrue(defined("HEADER_VALUE_TRUE"));
		self::assertTrue(defined("META_ENCRYPTED"));
		self::assertTrue(defined("META_IV"));
		self::assertTrue(defined("META_SIGNATURE"));
		self::assertTrue(defined("META_IV_TAG"));
		self::assertTrue(defined("META_PADDING_TAG_LONG"));
		self::assertTrue(defined("META_PADDING_TAG_SHORT"));
		self::assertTrue(defined("META_SIGNATURE_TAG"));
		self::assertTrue(defined("REPLACE_RC4"));
	}
}
