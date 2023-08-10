<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class unencrypted extends main {
	const EXTERNAL_STORAGES = [];
	const SOURCEPATHS       = [];
	const USER_MNEMONICS    = ["admin" => "hood stamp fee tree winter quarter bar interest vintage dash lazy deposit"];
	const VERSION           = "unencrypted";
}
