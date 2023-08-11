<?php
final class searchInstanceIDs extends PHPUnit\Framework\TestCase {
	public function test() {
		putenv("TESTING=true");
		putenv("DATADIRECTORY=".__DIR__."/../../data/server-side-encryption/searchInstanceIDs/test/");

		include(__DIR__."/../../../server-side-encryption/recover.php");

		static::assertSame(["ocsm6z97mzli"], searchInstanceIDs());
	}

	public function test_config() {
		putenv("TESTING=true");
		putenv("DATADIRECTORY=".__DIR__."/../../data/server-side-encryption/searchInstanceIDs/test/");
		putenv("INSTANCEID=");

		include(__DIR__."/../../../server-side-encryption/recover.php");

		prepareConfig();

		static::assertSame(["ocsm6z97mzli"], INSTANCEID);
	}
}
