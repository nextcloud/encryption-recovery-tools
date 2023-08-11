<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class trashbin_owncloud09 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocfpxvgrexon";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "wIvlyCohkHpW9+wLcibHffmPZdDzbOP9zEstv5nMCsYIuMlO";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "trashbin_09.0.0";
}
