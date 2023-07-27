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

		// check if the output is as expected
		ob_start();
		printHelp();
		$result = ob_get_contents();
		self::assertSame(self::get_help_text(), $result);
		ob_end_clean();

		// check if each line has max. 80 characters
		$result = explode(PHP_EOL, $result);
		foreach ($result as $line) {
			self::assertLessThanOrEqual(80, strlen($line));
		}
	}

	public function test_no_args() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		// check if the output is as expected
		ob_start();
		self::assertSame(0, main([__FILE__]));
		$result = ob_get_contents();
		self::assertSame(self::get_help_text(), $result);
		ob_end_clean();

		// check if each line has max. 80 characters
		$result = explode(PHP_EOL, $result);
		foreach ($result as $line) {
			self::assertLessThanOrEqual(80, strlen($line));
		}
	}

	public function test_h_arg() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		// check if the output is as expected
		ob_start();
		self::assertSame(0, main([__FILE__, "-h"]));
		$result = ob_get_contents();
		self::assertSame(self::get_help_text(), $result);
		ob_end_clean();

		// check if each line has max. 80 characters
		$result = explode(PHP_EOL, $result);
		foreach ($result as $line) {
			self::assertLessThanOrEqual(80, strlen($line));
		}
	}

	public function test_help_arg() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		// check if the output is as expected
		ob_start();
		self::assertSame(0, main([__FILE__, "--help"]));
		$result = ob_get_contents();
		self::assertSame(self::get_help_text(), $result);
		ob_end_clean();

		// check if each line has max. 80 characters
		$result = explode(PHP_EOL, $result);
		foreach ($result as $line) {
			self::assertLessThanOrEqual(80, strlen($line));
		}
	}
}
