<?php
final class printHelp extends PHPUnit\Framework\TestCase
{
	protected static function get_help_text() {
		$source = file(__DIR__."/../../../server-side-encryption/recover.php", FILE_IGNORE_NEW_LINES);

		// remove the shebang
		array_shift($source);

		$result  = [];
		$started = false;
		foreach ($source as $line) {
			$line = trim($line);
			
			if (!$started) {
				$started = (0 === strpos($line, "#"));
			}

			if ($started) {
				if (0 === strpos($line, "#")) {
					$result[] = substr($line, 2);
				} else {
					break;
				}
			}
		}

		return implode(PHP_EOL, $result).PHP_EOL;
	}

	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString(self::get_help_text());
		printHelp();
	}

	public function test_no_args() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString(self::get_help_text());
		main([__FILE__]);
	}

	public function test_h_arg() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString(self::get_help_text());
		main([__FILE__, "-h"]);
	}

	public function test_help_arg() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::expectOutputString(self::get_help_text());
		main([__FILE__, "--help"]);
	}
}
