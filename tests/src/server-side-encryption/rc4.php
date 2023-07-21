<?php
final class rc4 extends PHPUnit\Framework\TestCase
{
	public function test_decrypt() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::assertSame("The quick brown fox jumps over the lazy dog.",
		                 rc4(hex2bin("dabb872d80cac3802b538daa15faa1559f8d54f1e494375266fee68ce471b89b4ce1ff0ff57e626f36fea7a1"),
		                     hex2bin("07f3da64cfff8bf6da2e36268fd36be695df262a0c3906ca673a63b9da4a6c67")));
	}

	public function test_encrypt() {
		define("TESTING", true);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		self::assertSame("dabb872d80cac3802b538daa15faa1559f8d54f1e494375266fee68ce471b89b4ce1ff0ff57e626f36fea7a1",
		                 bin2hex(rc4("The quick brown fox jumps over the lazy dog.",
		                             hex2bin("07f3da64cfff8bf6da2e36268fd36be695df262a0c3906ca673a63b9da4a6c67"))));
	}
}
