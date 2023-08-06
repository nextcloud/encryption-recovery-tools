<?php
final class getHomeDir extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		self::assertSame(posix_getpwuid(posix_getuid())["dir"], getHomeDir());
		self::assertSame(posix_getpwuid(posix_getuid())["dir"], getHomeDir(posix_getpwuid(posix_getuid())["name"]));
		self::assertSame(posix_getpwnam("root")["dir"],         getHomeDir("root"));
	}
}
