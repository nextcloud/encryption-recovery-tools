<?php
final class config extends PHPUnit\Framework\TestCase {
	public function test_config() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		config("TEST_VALUE", "test1");
		config("TEST_VALUE", "test2");
		self::assertSame("test1", TEST_VALUE);

		$expected = ["test"];
		config("INSTANCEID", "test");
		self::assertSame($expected, INSTANCEID);

		$expected = ["test"];
		config("RECOVERY_PASSWORD", "test");
		self::assertSame($expected, RECOVERY_PASSWORD);

		$expected = ["test1" => ["test1"],
		             "test2" => ["test2", "test3"]];
		config("USER_PASSWORDS", ["test1" => "test1",
		                          "test2" => ["test2", "test3"]]);
		self::assertSame($expected, USER_PASSWORDS);
	}

	public function test_define() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		define("TEST_VALUE", "test1");
		config("TEST_VALUE", "test2");
		self::assertSame("test1", TEST_VALUE);
	}

	public function test_putenv() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

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

		$expected = ["test"];
		putenv("INSTANCEID=test");
		config("INSTANCEID", "");
		self::assertSame($expected, INSTANCEID);

		$expected = ["test1", "test2"];
		putenv("RECOVERY_PASSWORD=test1 test2");
		config("RECOVERY_PASSWORD", "");
		self::assertSame($expected, RECOVERY_PASSWORD);

		putenv("SUPPORT_MISSING_HEADERS=true");
		config("SUPPORT_MISSING_HEADERS", false);
		self::assertSame(true, SUPPORT_MISSING_HEADERS);

		$expected = ["test1" => ["test1"],
		             "test2" => ["test2", "test3"]];
		putenv("USER_PASSWORDS=test1=test1 test2=test2 test2=test3");
		config("USER_PASSWORDS", []);
		self::assertSame($expected, USER_PASSWORDS);
	}
}
