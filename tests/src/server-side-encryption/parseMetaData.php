<?php
final class parseMetaData extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING",            true);
		define("DEBUG_MODE",         false);
		define("DEBUG_MODE_VERBOSE", false);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		$expected = [META_ENCRYPTED => "",
		             META_IV        => "",
		             META_SIGNATURE => false];
		$result   = parseMetaData("");
		self::assertEqualsCanonicalizing($expected, $result);

		$expected = [META_ENCRYPTED => "",
		             META_IV        => str_repeat("0", 16),
		             META_SIGNATURE => str_repeat("0", 64)];
		$result   = parseMetaData(META_IV_TAG.
		                          str_repeat("0", 16).
		                          META_SIGNATURE_TAG.
		                          str_repeat("0", 64).
		                          META_PADDING_TAG_LONG);
		self::assertEqualsCanonicalizing($expected, $result);

		$expected = [META_ENCRYPTED => "test",
		             META_IV        => str_repeat("0", 16),
		             META_SIGNATURE => str_repeat("0", 64)];
		$result   = parseMetaData("test".
		                          META_IV_TAG.
		                          str_repeat("0", 16).
		                          META_SIGNATURE_TAG.
		                          str_repeat("0", 64).
		                          META_PADDING_TAG_LONG);
		self::assertEqualsCanonicalizing($expected, $result);

		$expected = [META_ENCRYPTED => "",
		             META_IV        => str_repeat("0", 16),
		             META_SIGNATURE => false];
		$result   = parseMetaData(META_IV_TAG.
		                          str_repeat("0", 16).
		                          META_PADDING_TAG_SHORT);
		self::assertEqualsCanonicalizing($expected, $result);

		$expected = [META_ENCRYPTED => "test",
		             META_IV        => str_repeat("0", 16),
		             META_SIGNATURE => false];
		$result   = parseMetaData("test".
		                          META_IV_TAG.
		                          str_repeat("0", 16).
		                          META_PADDING_TAG_SHORT);
		self::assertEqualsCanonicalizing($expected, $result);
	}
}
