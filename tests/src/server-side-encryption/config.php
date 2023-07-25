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

		$expected = ["test1" => "test1",
		             "test2" => "test2"];
		putenv("USER_PASSWORDS=test1=test1 test2=test2");
		config("USER_PASSWORDS", []);
		self::assertSame($expected, USER_PASSWORDS);

		putenv("SUPPORT_MISSING_HEADERS=true");
		config("SUPPORT_MISSING_HEADERS", false);
		self::assertSame(true, SUPPORT_MISSING_HEADERS);
	}
}
