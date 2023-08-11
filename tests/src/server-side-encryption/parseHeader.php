<?php
final class parseHeader extends PHPUnit\Framework\TestCase {
	public function test_false() {
		define("TESTING",            true);
		define("DEBUG_MODE",         false);
		define("DEBUG_MODE_VERBOSE", false);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		// these are the default header values
		$minimal = [HEADER_CIPHER               => HEADER_CIPHER_LEGACY,
		            HEADER_ENCODING             => HEADER_ENCODING_BASE64,
		            HEADER_KEYFORMAT            => HEADER_KEYFORMAT_PASSWORD,
		            HEADER_OC_ENCRYPTION_MODULE => HEADER_OC_ENCRYPTION_MODULE_DEFAULT,
		            HEADER_SIGNED               => HEADER_VALUE_FALSE,
		            HEADER_USE_LEGACY_FILE_KEY  => HEADER_VALUE_FALSE];

		$result = parseHeader("", false);
		self::assertEqualsCanonicalizing([], $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":".
		                      HEADER_END, false);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      "::".
		                      HEADER_END, false);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":::".
		                      HEADER_END, false);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":".
		                      implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                      ":".
		                      HEADER_END, false);
		self::assertEqualsCanonicalizing($minimal, $result);

		$expected         = $minimal;
		$expected["test"] = "test";
		$result           = parseHeader(HEADER_BEGIN.
		                                ":".
		                                implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                                ":test:test:".
		                                HEADER_END, false);
		self::assertEqualsCanonicalizing($expected, $result);

		$expected                   = $minimal;
		$expected[HEADER_KEYFORMAT] = HEADER_KEYFORMAT_HASH;
		$result                     = parseHeader(HEADER_BEGIN.
		                                          ":".
		                                          implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                                          ":".
		                                          HEADER_KEYFORMAT.
		                                          ":".
		                                          HEADER_KEYFORMAT_HASH.
		                                          ":".
		                                          HEADER_END, false);
		self::assertEqualsCanonicalizing($expected, $result);
	}

	public function test_true() {
		define("TESTING",            true);
		define("DEBUG_MODE",         false);
		define("DEBUG_MODE_VERBOSE", false);

		include(__DIR__."/../../../server-side-encryption/recover.php");

		// these are the default header values
		$minimal = [HEADER_CIPHER               => HEADER_CIPHER_LEGACY,
		            HEADER_ENCODING             => HEADER_ENCODING_BASE64,
		            HEADER_KEYFORMAT            => HEADER_KEYFORMAT_PASSWORD,
		            HEADER_OC_ENCRYPTION_MODULE => HEADER_OC_ENCRYPTION_MODULE_DEFAULT,
		            HEADER_SIGNED               => HEADER_VALUE_FALSE,
		            HEADER_USE_LEGACY_FILE_KEY  => HEADER_VALUE_FALSE];

		$result = parseHeader("", true);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":".
		                      HEADER_END, true);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      "::".
		                      HEADER_END, true);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":::".
		                      HEADER_END, true);
		self::assertEqualsCanonicalizing($minimal, $result);

		$result = parseHeader(HEADER_BEGIN.
		                      ":".
		                      implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                      ":".
		                      HEADER_END, true);
		self::assertEqualsCanonicalizing($minimal, $result);

		$expected         = $minimal;
		$expected["test"] = "test";
		$result           = parseHeader(HEADER_BEGIN.
		                                ":".
		                                implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                                ":test:test:".
		                                HEADER_END, true);
		self::assertEqualsCanonicalizing($expected, $result);

		$expected                   = $minimal;
		$expected[HEADER_KEYFORMAT] = HEADER_KEYFORMAT_HASH;
		$result                     = parseHeader(HEADER_BEGIN.
		                                          ":".
		                                          implode(":", array_map(function($key, $value){return $key.":".$value;}, array_keys($minimal), $minimal)).
		                                          ":".
		                                          HEADER_KEYFORMAT.
		                                          ":".
		                                          HEADER_KEYFORMAT_HASH.
		                                          ":".
		                                          HEADER_END, true);
		self::assertEqualsCanonicalizing($expected, $result);
	}
}
