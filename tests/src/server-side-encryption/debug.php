<?php
final class debug extends PHPUnit\Framework\TestCase {
	public function test_false() {
		define("TESTING",    true);
		define("DEBUG_MODE", false);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString("");
		debug("test");
	}

	public function test_true() {
		define("TESTING",    true);
		define("DEBUG_MODE", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString("DEBUG: test".PHP_EOL);
		debug("test");
	}
}
