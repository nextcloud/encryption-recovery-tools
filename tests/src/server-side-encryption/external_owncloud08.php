<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class external_owncloud08 extends main
{
	const EXTERNAL_STORAGES = ["external_system"     => __DIR__."/../../data/server-side-encryption/external_08.0.8/external_system/",
	                           "admin/external_user" => __DIR__."/../../data/server-side-encryption/external_08.0.8/external_user/"];
	const INSTANCEID        = "ocg45by1ynpf";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "SuK5YE7ENHlhW3TvqQ25SlHRyxknso0EuxH8dpiigQiIuf3C";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "external_08.0.8";
}
