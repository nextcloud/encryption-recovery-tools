<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud22 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocfl6ava2omt";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "vjfCZ+j+5enlm3iuL5YNCsYwhyDL+n985e4iBvhOtfMD3EjI";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "22.0.0";
}
