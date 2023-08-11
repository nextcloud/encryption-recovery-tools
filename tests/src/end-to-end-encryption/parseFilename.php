<?php
final class parseFilename extends PHPUnit\Framework\TestCase
{
	public function test() {
		define("TESTING",       true);
                define("DATADIRECTORY", __DIR__);

		include(__DIR__."/../../../end-to-end-encryption/recover.php");

		prepareConfig();

		$filename = normalizePath(__DIR__."/test1/files/test2");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2",
		             FILE_NAME_RAW      => "test2",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files/test2/test3");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2/test3",
		             FILE_NAME_RAW      => "test2/test3",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/files/test2.d12345/test3");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345/test3",
		             FILE_NAME_RAW      => "test2.d12345/test3",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/files/test2.d12345/test3/test4");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345/test3/test4",
		             FILE_NAME_RAW      => "test2.d12345/test3/test4",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/files/test2.d12345");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345",
		             FILE_NAME_RAW      => "test2",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/versions/test2.d12345/test3.v98765");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345/test3",
		             FILE_NAME_RAW      => "test2.d12345/test3",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => true,
		             FILE_VERSION_TIME  => "98765"];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/versions/test2.d12345/test3/test4.v98765");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345/test3/test4",
		             FILE_NAME_RAW      => "test2.d12345/test3/test4",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => true,
		             FILE_VERSION_TIME  => "98765"];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_trashbin/versions/test2.v98765.d12345");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2.d12345",
		             FILE_NAME_RAW      => "test2",
		             FILE_TRASHBIN      => true,
		             FILE_TRASHBIN_TIME => "12345",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => true,
		             FILE_VERSION_TIME  => "98765"];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_versions/test2.v98765");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2",
		             FILE_NAME_RAW      => "test2",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => true,
		             FILE_VERSION_TIME  => "98765"];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath(__DIR__."/test1/files_versions/test2/test3.v98765");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "test2/test3",
		             FILE_NAME_RAW      => "test2/test3",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "test1",
		             FILE_VERSION       => true,
		             FILE_VERSION_TIME  => "98765"];
		self::assertSame($expected, parseFilename($filename, null, null));

		$filename = normalizePath("/mnt/sftp/test1");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "external/test1",
		             FILE_NAME_RAW      => "external/test1",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, "external", "/mnt/sftp/"));

		$filename = normalizePath("/mnt/sftp/test1/test2");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "external/test1/test2",
		             FILE_NAME_RAW      => "external/test1/test2",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, "external", "/mnt/sftp/"));

		$filename = normalizePath("/mnt/sftp/test1");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "external/test1",
		             FILE_NAME_RAW      => "external/test1",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "user",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, "user/external", "/mnt/sftp/"));

		$filename = normalizePath("/mnt/sftp/test1/test2");
		$expected = [FILE_FILE          => $filename,
		             FILE_NAME          => "external/test1/test2",
		             FILE_NAME_RAW      => "external/test1/test2",
		             FILE_TRASHBIN      => false,
		             FILE_TRASHBIN_TIME => "",
		             FILE_USERNAME      => "user",
		             FILE_VERSION       => false,
		             FILE_VERSION_TIME  => ""];
		self::assertSame($expected, parseFilename($filename, "user/external", "/mnt/sftp/"));
	}
}
