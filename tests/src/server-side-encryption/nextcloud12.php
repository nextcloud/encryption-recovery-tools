<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud12 extends main
{
	const INSTANCEID        = "ocsm6z97mzli";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "UTdm0FJIoWHfuolkucrM0aRyXUP56O27MTnu1oM+5IcR1bn9";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "12.0.0";
}
