<?php
final class debugConfig extends PHPUnit\Framework\TestCase {
	protected static function generateTestOutput() {
		return "DEBUG: DATADIRECTORY = ".var_export(getcwd(), true).PHP_EOL.
		       "DEBUG: DEBUG_MODE = true".PHP_EOL.
		       "DEBUG: DEBUG_MODE_VERBOSE = true".PHP_EOL.
		       "DEBUG: EXTERNAL_STORAGES = ".var_export([], true).PHP_EOL.
		       "DEBUG: USER_MNEMONICS = ".var_export(["username" => ["mnemonic"]], true).PHP_EOL;
	}

	public function test_false() {
		define("TESTING",            true);
		define("DEBUG_MODE",         true);
		define("DEBUG_MODE_VERBOSE", false);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		prepareConfig();
		self::expectOutputString("");
		debugConfig();
	}

	public function test_true() {
		define("TESTING",            true);
		define("DEBUG_MODE",         true);
		define("DEBUG_MODE_VERBOSE", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		prepareConfig();
		self::expectOutputString(self::generateTestOutput());
		debugConfig();
	}
}
