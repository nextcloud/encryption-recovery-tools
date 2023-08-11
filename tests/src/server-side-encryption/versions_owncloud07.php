<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class versions_owncloud07 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "oc5781c9e0cb";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "f47381070c5ce8f4cc163c9875915ba958a0f5e3588ed759c8d3202d84f0b7baf7a24da1db6773d42e1998a1b2b3d8bd";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "versions_07.0.10";
}
