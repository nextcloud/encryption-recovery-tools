<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud11 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocj3ol6o1dz1";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "P7F1LkHaE+uS4xB74IWXK8jmAsKYOsy3arIHL+w1wnkijjI7";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "11.0.1";
}
