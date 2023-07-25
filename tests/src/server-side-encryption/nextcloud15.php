<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud15 extends main
{
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "och5c2xbdlhb";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "GdsVrMMfS8G4dQjuFUeGfFS34UPYNiX+s9ehU8L1F1JJfY2p";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "15.0.0";
}
