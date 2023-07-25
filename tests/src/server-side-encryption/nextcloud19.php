<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud19 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc5bemlo9thw";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "/fEY0PdCmOjHc9mdeaIXlikSoWTWF2SX0RIKa8wXry3NDmEf";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "19.0.0";
}
