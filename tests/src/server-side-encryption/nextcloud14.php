<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud14 extends main
{
	const INSTANCEID        = "ocpztied5g24";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "SwY1MKbWamSb0MvrEq/3AUO9XYf6hU4TCbc0+RlsbEvEiLwF";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "14.0.0";
}
