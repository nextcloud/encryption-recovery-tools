<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class skip_files extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oco0q04i4djv";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "kiYNlIetbTQwn328L/GCuo/FrCHFhvbkOr32IiCI88PRjtGm";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "skip_files";

	protected static function copy_dir($original, $tmp) {
		if (is_dir($original) && is_dir($tmp)) {
			$content = scandir($original);
			foreach ($content as $content_item) {
				if (("." !== $content_item) && (".." !== $content_item)) {
					// copy the file
					if (is_file(static::concat_path($original, $content_item))) {
						copy(static::concat_path($original, $content_item),
						     static::concat_path($tmp, $content_item));
					} elseif (is_dir(static::concat_path($original, $content_item))) {
						// create the tmp sub-folder
						if (!is_dir(static::concat_path($tmp, $content_item))) {
							mkdir(static::concat_path($tmp, $content_item));
						}

						// copy the folder
						static::copy_dir(static::concat_path($original, $content_item),
						                 static::concat_path($tmp, $content_item));
					}
				}
			}
		}
	}

	protected function setUp() : void {
		static::clear_dir(__DIR__."/../../tmp/");

		// ensure that the target files already exist
		static::copy_dir(__DIR__."/../../data/server-side-encryption/".static::VERSION."/original/",
		                 __DIR__."/../../tmp/");
	}
}
