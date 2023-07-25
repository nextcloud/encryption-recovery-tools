<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud24 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocfiw5pa2jk2";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "Sw50f5IPAJJk3XlaLi+QxvgosZ8tokrpD7sfefSB1eowxfWF";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "24.0.0";
}
