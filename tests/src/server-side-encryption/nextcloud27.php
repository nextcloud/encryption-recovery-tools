<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud27 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc8pf91y7iwm";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "NIZs4ASNBZir11IqR1UYr+TiNl1yNPebbY3611L3RtfcSgrh";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "27.0.0";
}
