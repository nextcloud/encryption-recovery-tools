<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud31 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocmphv6n7mfc";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "xTnQZceVgHneA8OQ7nUcoCLioKkQiElsmzFZOBTv6tNW1WDp";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "31.0.0";
}
