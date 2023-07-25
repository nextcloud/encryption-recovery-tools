<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class versions_owncloud09 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocfpxvgrexon";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "wIvlyCohkHpW9+wLcibHffmPZdDzbOP9zEstv5nMCsYIuMlO";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "versions_09.0.0";
}
