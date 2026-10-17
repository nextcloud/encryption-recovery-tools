<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud35 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocvnhw8wz717";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "Td9KS5YZZnfkGpQqqqtWhj1XseUmocxu3KLb1VHavc347fGg";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "35.0.0";
}
