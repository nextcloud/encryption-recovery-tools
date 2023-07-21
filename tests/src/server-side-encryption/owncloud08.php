<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class owncloud08 extends main
{
	const INSTANCEID        = "ocg45by1ynpf";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "SuK5YE7ENHlhW3TvqQ25SlHRyxknso0EuxH8dpiigQiIuf3C";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "08.0.8";
}
