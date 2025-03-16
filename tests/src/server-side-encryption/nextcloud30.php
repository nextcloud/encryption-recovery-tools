<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud30 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocdg3nll78ws";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "uzF4QV35Cseak4zt3bzEbRV3suOpkc/MV3GZFV2WUH9U/xbu";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "30.0.0";
}
