<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud21 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc6e7nkxo8fo";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "4Oah41r2tbWlF3hyBtDwdcA5N1bNh8mTczBEoBDsSvh0TpKC";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "21.0.0";
}
