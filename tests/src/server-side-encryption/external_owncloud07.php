<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class external_owncloud07 extends main
{
	const EXTERNAL_STORAGES = ["external_system"     => __DIR__."/../../data/server-side-encryption/external_07.0.10/external_system/",
	                           "admin/external_user" => __DIR__."/../../data/server-side-encryption/external_07.0.10/external_user/"];
	const INSTANCEID        = "oc5781c9e0cb";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "f47381070c5ce8f4cc163c9875915ba958a0f5e3588ed759c8d3202d84f0b7baf7a24da1db6773d42e1998a1b2b3d8bd";
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "external_07.0.10";
}
