<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud28 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc8qzi8ijyoz";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "xwyBF/7MXFxdpB5xls4PJai1V9SB8j1m7lGOF2hJn3tUaClx";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "28.0.0";
}
