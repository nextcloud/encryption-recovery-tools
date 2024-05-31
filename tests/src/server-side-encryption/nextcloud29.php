<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud29 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oco0q04i4djv";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "kiYNlIetbTQwn328L/GCuo/FrCHFhvbkOr32IiCI88PRjtGm";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "29.0.0";
}
