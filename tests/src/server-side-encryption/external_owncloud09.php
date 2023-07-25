<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class external_owncloud09 extends main
{
	const EXTERNAL_STORAGES = ["external_system"     => __DIR__."/../../data/server-side-encryption/external_09.0.0/external_system/",
	                           "admin/external_user" => __DIR__."/../../data/server-side-encryption/external_09.0.0/external_user/"];
	const INSTANCEID        = "ocfpxvgrexon";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "wIvlyCohkHpW9+wLcibHffmPZdDzbOP9zEstv5nMCsYIuMlO";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "external_09.0.0";
}
