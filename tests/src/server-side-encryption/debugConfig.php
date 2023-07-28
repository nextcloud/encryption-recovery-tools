<?php
final class debugConfig extends PHPUnit\Framework\TestCase
{
	protected static function generateTestOutput() {
		return "DEBUG: DATADIRECTORY = ".var_export(getcwd(), true).PHP_EOL.
		       "DEBUG: DEBUG_MODE = true".PHP_EOL.
		       "DEBUG: DEBUG_MODE_VERBOSE = true".PHP_EOL.
		       "DEBUG: EXTERNAL_STORAGES = array (".PHP_EOL.
		       ")".PHP_EOL.
		       "DEBUG: INSTANCEID = ''".PHP_EOL.
		       "DEBUG: RECOVERY_PASSWORD = ''".PHP_EOL.
		       "DEBUG: SECRET = ''".PHP_EOL.
		       "DEBUG: SUPPORT_MISSING_HEADERS = false".PHP_EOL.
		       "DEBUG: USER_PASSWORDS = array (".PHP_EOL.
		       ")".PHP_EOL;
	}

	public function test_false() {
		define("TESTING",            true);
		define("DEBUG_MODE",         true);
		define("DEBUG_MODE_VERBOSE", false);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		prepareConfig();
		self::expectOutputString("");
		debugConfig();
	}

	public function test_true() {
		define("TESTING",            true);
		define("DEBUG_MODE",         true);
		define("DEBUG_MODE_VERBOSE", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		prepareConfig();
		self::expectOutputString(self::generateTestOutput());
		debugConfig();
	}
}
