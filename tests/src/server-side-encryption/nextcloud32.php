<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud32 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc0h50z5ilbu";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "IZv0hFlOBdbO9MEOcQMd6czty2bvU4SyMPDPphIxRDK8HFne";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "32.0.0";
}
