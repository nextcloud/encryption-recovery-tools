<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud10 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocbfej6x8g3x";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "CUz1/qUjNP9wy1QrVzwHNhsw8MYg0jM9j+xa1YYNmxr0G522";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "10.0.3";
}
