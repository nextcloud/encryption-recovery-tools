<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud33 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocjci1hnonwe";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "b4lZfh0aa7FZEktZfDVLS1LH8ldfkUjkBPmRkOw2EwzDBk8H";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "33.0.6";
}
