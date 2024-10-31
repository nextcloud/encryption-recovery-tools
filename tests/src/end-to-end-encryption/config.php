<?php
final class config extends PHPUnit\Framework\TestCase
{
	public function test_config() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		config("TEST_VALUE", "test1");
		config("TEST_VALUE", "test2");
		self::assertSame("test1", TEST_VALUE);

		$expected = ["test" => dirname(__DIR__)];
		putenv("EXTERNAL_STORAGES=test=".__DIR__."/../");
		config("EXTERNAL_STORAGES", []);
		self::assertSame($expected, EXTERNAL_STORAGES);

		// check default USER_MNEMONICS value
		$expected = ["username" => ["mnemonic"]];
		self::assertSame($expected, USER_MNEMONICS);
	}

	public function test_define() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		define("TEST_VALUE", "test1");
		config("TEST_VALUE", "test2");
		self::assertSame("test1", TEST_VALUE);
	}

	public function test_putenv() {
		define("TESTING", true);

		// prepare USER_MNEMONICS test
		putenv("USER_MNEMONICS=test1=test1 test2=test2 test2=test3");

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		putenv("TEST_VALUE=test1");
		config("TEST_VALUE", "test2");
		self::assertSame("test1", TEST_VALUE);

		putenv("DEBUG_MODE=false");
		config("DEBUG_MODE", true);
		self::assertSame(false, DEBUG_MODE);

		$expected = ["test" => dirname(__DIR__)];
		putenv("EXTERNAL_STORAGES=test=".__DIR__."/../");
		config("EXTERNAL_STORAGES", []);
		self::assertSame($expected, EXTERNAL_STORAGES);

		// execute USER_MNEMONICS test
		$expected = ["test1" => ["test1"],
		             "test2" => ["test2", "test3"]];
		config("USER_MNEMONICS", []);
		self::assertSame($expected, USER_MNEMONICS);
	}

	public function test_putenv_overwrite_float() {
		define("TESTING", true);

		$expected = 1.2;
		putenv("VERSION_12=K");

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		self::assertSame($expected, VERSION_12);
	}

	public function test_putenv_overwrite_int() {
		define("TESTING", true);

		$expected1 = 8192;
		putenv("BLOCKSIZE=K");

		$expected2 = 16;
		putenv("TAGSIZE=K");

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		self::assertSame($expected1, BLOCKSIZE);
		self::assertSame($expected2, TAGSIZE);
	}
}
