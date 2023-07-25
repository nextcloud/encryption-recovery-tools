<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud18 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc72b1ob3098";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "ivk//tSvo6ecAxChsKORvEG1IS6f1VQc/PL4deVI47nWcAzQ";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "18.0.0";
}
