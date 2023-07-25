<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud16 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "occjpv8fypow";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "oDlwxPayQKfjITpR4evCo9NYdwaQ3L9S2nkF0tznfX+CaMN9";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "16.0.0";
}
