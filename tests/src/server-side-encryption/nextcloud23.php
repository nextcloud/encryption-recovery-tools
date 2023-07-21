<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud23 extends main
{
	const INSTANCEID        = "oc0hyclfwl79";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "bSLk3nFQN8Xl8eOJr3bguXRqGw3AzbN/RsoAPhenVZVQezBa";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "23.0.0";
}
