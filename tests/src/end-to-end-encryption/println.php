<?php
final class println extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString("test".PHP_EOL);
		println("test");
	}
}
