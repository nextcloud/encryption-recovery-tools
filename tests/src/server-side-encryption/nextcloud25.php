<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class nextcloud25 extends main {
	const EXTERNAL_STORAGES = [];
	const INSTANCEID        = "ocpa2mmg3te5";
	const RECOVERY_PASSWORD = "recovery";
	const SECRET            = "2ls6SVLZIeA2gxzOPXAGC5oVfN3t/kvmkGuH/ilFwF/quG/8";
	const SOURCEPATHS       = [];
	const USER_PASSWORDS    = ["admin" => "admin"];
	const VERSION           = "25.0.0";
}
