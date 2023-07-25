<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud13 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ochm0flhsasc";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "eKIloQQo0JsRXY1ZJqw0bbIIhp29O3/q60g0XP4dUDzZIt0m";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "13.0.0";
}
