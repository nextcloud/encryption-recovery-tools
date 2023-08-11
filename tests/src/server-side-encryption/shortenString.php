<?php
final class shortenString extends PHPUnit\Framework\TestCase {
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::assertSame("",      shortenString("",        5, "..."));
		self::assertSame("a",     shortenString("a",       5, "..."));
		self::assertSame("ab",    shortenString("ab",      5, "..."));
		self::assertSame("abc",   shortenString("abc",     5, "..."));
		self::assertSame("abcd",  shortenString("abcd",    5, "..."));
		self::assertSame("abcde", shortenString("abcde",   5, "..."));
		self::assertSame("a...f", shortenString("abcdef",  5, "..."));
		self::assertSame("a...g", shortenString("abcdefg", 5, "..."));
	}
}
