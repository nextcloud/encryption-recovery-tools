<?php
final class config extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		define("TEST_VALUE_A", "test1");
		config("TEST_VALUE_A", "test2");
		self::assertSame("test1", TEST_VALUE_A);

		config("TEST_VALUE_B", "test1");
		config("TEST_VALUE_B", "test2");
		self::assertSame("test1", TEST_VALUE_B);
	}
}
