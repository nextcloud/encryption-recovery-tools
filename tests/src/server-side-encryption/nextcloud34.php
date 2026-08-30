<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud34 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocu1i7efvcer";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "0qvOfQAJkajVc+w6MNhgHzHltv3m+nMhwlM1OascQHMIAajk";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "34.0.1";
}
