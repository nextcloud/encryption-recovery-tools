<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud26 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc22fp0ch1u2";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "6h1H4kQuqJtq2riRuckN/e2s7lhmh+ifhz41P+Gfpqw9gj6P";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "26.0.0";
}
