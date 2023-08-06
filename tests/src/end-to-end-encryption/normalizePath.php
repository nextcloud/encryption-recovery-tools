<?php
final class normalizePath extends PHPUnit\Framework\TestCase
{
	public function test_false() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		self::assertSame(getcwd(),                                               normalizePath("",               false));
		self::assertSame("/",                                                    normalizePath("/",              false));
		self::assertSame("/",                                                    normalizePath("//",             false));
		self::assertSame("/",                                                    normalizePath("/.",             false));
		self::assertSame("/",                                                    normalizePath("/./",            false));
		self::assertSame("/",                                                    normalizePath("/./.",           false));
		self::assertSame("/",                                                    normalizePath("/././",          false));
		self::assertSame("/",                                                    normalizePath("/..",            false));
		self::assertSame("/",                                                    normalizePath("/../",           false));
		self::assertSame("/",                                                    normalizePath("/../..",         false));
		self::assertSame("/",                                                    normalizePath("/../../",        false));
		self::assertSame("/test",                                                normalizePath("/test",          false));
		self::assertSame("/test",                                                normalizePath("/test/",         false));
		self::assertSame("/test",                                                normalizePath("/test/.",        false));
		self::assertSame("/test",                                                normalizePath("/test/./",       false));
		self::assertSame("/",                                                    normalizePath("/test/..",       false));
		self::assertSame("/",                                                    normalizePath("/test/../",      false));
		self::assertSame("/test",                                                normalizePath("/./test",        false));
		self::assertSame("/test",                                                normalizePath("/./test/",       false));
		self::assertSame("/test",                                                normalizePath("/../test",       false));
		self::assertSame("/test",                                                normalizePath("/../test/",      false));
		self::assertSame(getcwd()."/test",                                       normalizePath("test",           false));
		self::assertSame(getcwd()."/test",                                       normalizePath("test/",          false));
		self::assertSame(getcwd(),                                               normalizePath(".",              false));
		self::assertSame(getcwd(),                                               normalizePath("./",             false));
		self::assertSame(getcwd()."/test",                                       normalizePath("./test",         false));
		self::assertSame(getcwd()."/test",                                       normalizePath("./test/",        false));
		self::assertSame(dirname(getcwd()),                                      normalizePath("..",             false));
		self::assertSame(dirname(getcwd()),                                      normalizePath("../",            false));
		self::assertSame(dirname(getcwd())."/test",                              normalizePath("../test",        false));
		self::assertSame(dirname(getcwd())."/test",                              normalizePath("../test/",       false));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"],                  normalizePath("~",              false));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"],                  normalizePath("~/",             false));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/test",          normalizePath("~/test",         false));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/test",          normalizePath("~/test/",        false));
		self::assertSame(dirname(posix_getpwuid(posix_getuid())["dir"])."/test", normalizePath("~/../test",      false));
		self::assertSame(dirname(posix_getpwuid(posix_getuid())["dir"])."/test", normalizePath("~/../test/",     false));
		self::assertSame(posix_getpwnam("root")["dir"],                          normalizePath("~root",          false));
		self::assertSame(posix_getpwnam("root")["dir"],                          normalizePath("~root/",         false));
		self::assertSame(posix_getpwnam("root")["dir"]."/test",                  normalizePath("~root/test",     false));
		self::assertSame(posix_getpwnam("root")["dir"]."/test",                  normalizePath("~root/test/",    false));
		self::assertSame(dirname(posix_getpwnam("root")["dir"])."/test",         normalizePath("~root/../test",  false));
		self::assertSame(dirname(posix_getpwnam("root")["dir"])."/test",         normalizePath("~root/../test/", false));
	}

	public function test_true() {
		define("TESTING", true);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		self::assertSame(getcwd()."/",                                            normalizePath("",               true));
		self::assertSame("/",                                                     normalizePath("/",              true));
		self::assertSame("/",                                                     normalizePath("//",             true));
		self::assertSame("/",                                                     normalizePath("/.",             true));
		self::assertSame("/",                                                     normalizePath("/./",            true));
		self::assertSame("/",                                                     normalizePath("/./.",           true));
		self::assertSame("/",                                                     normalizePath("/././",          true));
		self::assertSame("/",                                                     normalizePath("/..",            true));
		self::assertSame("/",                                                     normalizePath("/../",           true));
		self::assertSame("/",                                                     normalizePath("/../..",         true));
		self::assertSame("/",                                                     normalizePath("/../../",        true));
		self::assertSame("/test/",                                                normalizePath("/test",          true));
		self::assertSame("/test/",                                                normalizePath("/test/",         true));
		self::assertSame("/test/",                                                normalizePath("/test/.",        true));
		self::assertSame("/test/",                                                normalizePath("/test/./",       true));
		self::assertSame("/",                                                     normalizePath("/test/..",       true));
		self::assertSame("/",                                                     normalizePath("/test/../",      true));
		self::assertSame("/test/",                                                normalizePath("/./test",        true));
		self::assertSame("/test/",                                                normalizePath("/./test/",       true));
		self::assertSame("/test/",                                                normalizePath("/../test",       true));
		self::assertSame("/test/",                                                normalizePath("/../test/",      true));
		self::assertSame(getcwd()."/test/",                                       normalizePath("test",           true));
		self::assertSame(getcwd()."/test/",                                       normalizePath("test/",          true));
		self::assertSame(getcwd()."/",                                            normalizePath(".",              true));
		self::assertSame(getcwd()."/",                                            normalizePath("./",             true));
		self::assertSame(getcwd()."/test/",                                       normalizePath("./test",         true));
		self::assertSame(getcwd()."/test/",                                       normalizePath("./test/",        true));
		self::assertSame(dirname(getcwd())."/",                                   normalizePath("..",             true));
		self::assertSame(dirname(getcwd())."/",                                   normalizePath("../",            true));
		self::assertSame(dirname(getcwd())."/test/",                              normalizePath("../test",        true));
		self::assertSame(dirname(getcwd())."/test/",                              normalizePath("../test/",       true));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/",               normalizePath("~",              true));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/",               normalizePath("~/",             true));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/test/",          normalizePath("~/test",         true));
		self::assertSame(posix_getpwuid(posix_getuid())["dir"]."/test/",          normalizePath("~/test/",        true));
		self::assertSame(dirname(posix_getpwuid(posix_getuid())["dir"])."/test/", normalizePath("~/../test",      true));
		self::assertSame(dirname(posix_getpwuid(posix_getuid())["dir"])."/test/", normalizePath("~/../test/",     true));
		self::assertSame(posix_getpwnam("root")["dir"]."/",                       normalizePath("~root",          true));
		self::assertSame(posix_getpwnam("root")["dir"]."/",                       normalizePath("~root/",         true));
		self::assertSame(posix_getpwnam("root")["dir"]."/test/",                  normalizePath("~root/test",     true));
		self::assertSame(posix_getpwnam("root")["dir"]."/test/",                  normalizePath("~root/test/",    true));
		self::assertSame(dirname(posix_getpwnam("root")["dir"])."/test/",         normalizePath("~root/../test",  true));
		self::assertSame(dirname(posix_getpwnam("root")["dir"])."/test/",         normalizePath("~root/../test/", true));
	}
}
