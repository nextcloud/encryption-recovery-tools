<?php
final class concatPath extends PHPUnit\Framework\TestCase {
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::assertSame("a/b", concatPath("a", "b"));
		self::assertSame("a/b", concatPath("a/", "b"));
		self::assertSame("a/b", concatPath("a", "/b"));
		self::assertSame("a/b", concatPath("a/", "/b"));
		self::assertSame("a/b", concatPath("a//", "b"));
		self::assertSame("a/b", concatPath("a", "//b"));
		self::assertSame("a/b", concatPath("a//", "//b"));
	}
}
