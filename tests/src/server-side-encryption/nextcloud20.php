<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud20 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "och9tobpvdn7";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "WSP9JEAX6aNnUUxVWyxum+HPFcMPcqYmTf6w2eKHCQbuO2UN";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "20.0.1";
}
