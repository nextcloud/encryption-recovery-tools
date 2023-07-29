<?php
final class config extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		define("TEST_VALUE_A", "test1");
		config("TEST_VALUE_A", "test2");
		self::assertSame("test1", TEST_VALUE_A);

		putenv("TEST_VALUE_B=test1");
		config("TEST_VALUE_B", "test2");
		self::assertSame("test1", TEST_VALUE_B);

		config("TEST_VALUE_C", "test1");
		config("TEST_VALUE_C", "test2");
		self::assertSame("test1", TEST_VALUE_C);

		$expected = ["test" => dirname(__DIR__)];
		putenv("EXTERNAL_STORAGES=test=".__DIR__."/../");
		config("EXTERNAL_STORAGES", []);
		self::assertSame($expected, EXTERNAL_STORAGES);

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
