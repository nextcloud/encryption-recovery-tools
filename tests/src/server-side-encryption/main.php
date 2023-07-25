<?php
/*

An inherited test looks like this:

>>>>> <classname>.php <<<<<

// use prepared test setup
include_once(__DIR__."/main.php");

final class <classname> extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "";
}

>>>>> <classname>.php <<<<<

*/

class main extends PHPUnit\Framework\TestCase
{
	// set this to true to debug problems
	const SHOW_OUTPUT=false;

	protected static function array_to_env($array) {
		$result = [];

		foreach ($array as $key => $value) {
			$result[] = "$key=$value";
		}

		return implode(" ", $result);
	}

	protected static function clear_dir($path) {
		if (is_dir($path)) {
			$content = scandir($path);
			foreach ($content as $content_item) {
				if (("." !== $content_item) && (".." !== $content_item)) {
					if (is_file(static::concat_path($path, $content_item))) {
						unlink(static::concat_path($path, $content_item));
					} elseif (is_dir(static::concat_path($path, $content_item))) {
						static::clear_dir(static::concat_path($path, $content_item));
					}
				}
			}
		}
	}

	protected static function compare_dir($original, $decrypted) {
		static::assertDirectoryExists($original);
		static::assertDirectoryExists($decrypted);

		if (is_dir($original) && is_dir($decrypted)) {
			$content = scandir($original);
			foreach ($content as $content_item) {
				if (("." !== $content_item) && (".." !== $content_item)) {
					if (is_file(static::concat_path($original, $content_item))) {
						static::assertFileEquals(static::concat_path($original,  $content_item),
						                         static::concat_path($decrypted, $content_item));
					} elseif (is_dir(static::concat_path($original, $content_item))) {
						static::compare_dir(static::concat_path($original, $content_item),
						                    static::concat_path($decrypted, $content_item));
					}
				}
			}
		}
	}

	protected static function concat_path($directory, $file) {
		// removing trailing slashes from $directory
		while ((0 < strlen($directory)) && ("/" === $directory[strlen($directory)-1])) {
			$directory = substr($directory, 0, -1);
		}

		// removing leading slashes from $file
		while ((0 < strlen($file)) && ("/" === $file[0])) {
			$file = substr($file, 1);
		}

		// concat $directory and $file with a slash
		return $directory."/".$file;
	}

	protected function setUp() : void {
		static::clear_dir(__DIR__."/../../tmp/");
	}

	protected function tearDown() : void {
		static::clear_dir(__DIR__."/../../tmp/");
	}

	public static function tearDownAfterClass() : void {
		// recreate .gitkeep file
		touch(__DIR__."/../../tmp/.gitkeep");
	}

	public function test() {
		if (is_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/test/")) {
			putenv("TESTING=".           "true");
			putenv("DATADIRECTORY=".     __DIR__."/../../data/server-side-encryption/".static::VERSION."/test/");
			putenv("DEBUG_MODE=".        "true");
			putenv("DEBUG_MODE_VERBOSE="."true");
			putenv("EXTERNAL_STORAGES=". static::array_to_env(static::EXTERNAL_STORAGES));
			putenv("INSTANCEID=".        static::INSTANCEID);
			putenv("RECOVERY_PASSWORD=". static::RECOVERY_PASSWORD);
			putenv("SECRET=".            static::SECRET);
			putenv("USER_PASSWORDS=".    static::array_to_env(static::USER_PASSWORDS));

			include(__DIR__."/../../../server-side-encryption/recover.php");

			if (!static::SHOW_OUTPUT) {
				ob_start();
			}
			$result = main([__FILE__, __DIR__."/../../tmp/"]);
			if (!static::SHOW_OUTPUT) {
				ob_end_clean();
			}

			static::assertSame(0, $result);
			static::compare_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
			                    __DIR__."/../../tmp/");
		} else {
			// prevent risky test rating
			static::assertTrue(true);
		}
	}

	public function test_master() {
		if (is_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/master/")) {
			putenv("TESTING=".           "true");
			putenv("DATADIRECTORY=".     __DIR__."/../../data/server-side-encryption/".static::VERSION."/master/");
			putenv("DEBUG_MODE=".        "true");
			putenv("DEBUG_MODE_VERBOSE="."true");
			putenv("EXTERNAL_STORAGES=". static::array_to_env(static::EXTERNAL_STORAGES));
			putenv("INSTANCEID=".        static::INSTANCEID);
			putenv("RECOVERY_PASSWORD=". static::RECOVERY_PASSWORD);
			putenv("SECRET=".            static::SECRET);
			putenv("USER_PASSWORDS=".    static::array_to_env(static::USER_PASSWORDS));

			include(__DIR__."/../../../server-side-encryption/recover.php");

			if (!static::SHOW_OUTPUT) {
				ob_start();
			}
			$result = main([__FILE__, __DIR__."/../../tmp/"]);
			if (!static::SHOW_OUTPUT) {
				ob_end_clean();
			}

			static::assertSame(0, $result);
			static::compare_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
			                    __DIR__."/../../tmp/");
		} else {
			// prevent risky test rating
			static::assertTrue(true);
		}
	}

	public function test_pubshare() {
		if (is_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/pubshare/")) {
			putenv("TESTING=".           "true");
			putenv("DATADIRECTORY=".     __DIR__."/../../data/server-side-encryption/".static::VERSION."/pubshare/");
			putenv("DEBUG_MODE=".        "true");
			putenv("DEBUG_MODE_VERBOSE="."true");
			putenv("EXTERNAL_STORAGES=". static::array_to_env(static::EXTERNAL_STORAGES));
			putenv("INSTANCEID=".        static::INSTANCEID);
			putenv("RECOVERY_PASSWORD=". static::RECOVERY_PASSWORD);
			putenv("SECRET=".            static::SECRET);
			putenv("USER_PASSWORDS=".    static::array_to_env(static::USER_PASSWORDS));

			include(__DIR__."/../../../server-side-encryption/recover.php");

			if (!static::SHOW_OUTPUT) {
				ob_start();
			}
			$result = main([__FILE__, __DIR__."/../../tmp/"]);
			if (!static::SHOW_OUTPUT) {
				ob_end_clean();
			}

			static::assertSame(0, $result);
			static::compare_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
			                    __DIR__."/../../tmp/");
		} else {
			// prevent risky test rating
			static::assertTrue(true);
		}
	}

	public function test_recovery() {
		if (is_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/recovery/")) {
			putenv("TESTING=".           "true");
			putenv("DATADIRECTORY=".     __DIR__."/../../data/server-side-encryption/".static::VERSION."/recovery/");
			putenv("DEBUG_MODE=".        "true");
			putenv("DEBUG_MODE_VERBOSE="."true");
			putenv("EXTERNAL_STORAGES=". static::array_to_env(static::EXTERNAL_STORAGES));
			putenv("INSTANCEID=".        static::INSTANCEID);
			putenv("RECOVERY_PASSWORD=". static::RECOVERY_PASSWORD);
			putenv("SECRET=".            static::SECRET);
			putenv("USER_PASSWORDS=".    static::array_to_env(static::USER_PASSWORDS));

			include(__DIR__."/../../../server-side-encryption/recover.php");

			if (!static::SHOW_OUTPUT) {
				ob_start();
			}
			$result = main([__FILE__, __DIR__."/../../tmp/"]);
			if (!static::SHOW_OUTPUT) {
				ob_end_clean();
			}

			static::assertSame(0, $result);
			static::compare_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
			                    __DIR__."/../../tmp/");
		} else {
			// prevent risky test rating
			static::assertTrue(true);
		}
	}

	public function test_user() {
		if (is_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/user/")) {
			putenv("TESTING=".           "true");
			putenv("DATADIRECTORY=".     __DIR__."/../../data/server-side-encryption/".static::VERSION."/user/");
			putenv("DEBUG_MODE=".        "true");
			putenv("DEBUG_MODE_VERBOSE="."true");
			putenv("EXTERNAL_STORAGES=". static::array_to_env(static::EXTERNAL_STORAGES));
			putenv("INSTANCEID=".        static::INSTANCEID);
			putenv("RECOVERY_PASSWORD=". static::RECOVERY_PASSWORD);
			putenv("SECRET=".            static::SECRET);
			putenv("USER_PASSWORDS=".    static::array_to_env(static::USER_PASSWORDS));

			include(__DIR__."/../../../server-side-encryption/recover.php");

			if (!static::SHOW_OUTPUT) {
				ob_start();
			}
			$result = main([__FILE__, __DIR__."/../../tmp/"]);
			if (!static::SHOW_OUTPUT) {
				ob_end_clean();
			}

			static::assertSame(0, $result);
			static::compare_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
			                    __DIR__."/../../tmp/");
		} else {
			// prevent risky test rating
			static::assertTrue(true);
		}
	}
}
