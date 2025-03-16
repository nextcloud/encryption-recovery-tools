<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class version20_weak_sha1 extends main {
	const EXTERNAL_STORAGES = [];
	const SOURCEPATHS       = [];
	const USER_MNEMONICS    = ["admin" => "drift company glass demise table grass skill master oval wait century kite"];
	const VERSION           = "version20_weak_sha1";
}
