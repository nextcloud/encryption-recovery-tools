<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud17 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocylm8kslj2z";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "LHEysTv3jUMy+hji1Ow9a2okINzSSJiZOjhR1DFWtrnSZ2Lt";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "17.0.0";
}
