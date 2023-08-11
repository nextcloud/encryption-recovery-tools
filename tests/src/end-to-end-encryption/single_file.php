<?php
// use prepared test setup
include_once(__DIR__."/main.php");

final class single_file extends main {
	const EXTERNAL_STORAGES = [];
	const SOURCEPATHS       = ["/admin/files/e2e/7c297d9297b0491cbd316bdf351402c8"];
	const USER_MNEMONICS    = ["admin" => "hood stamp fee tree winter quarter bar interest vintage dash lazy deposit"];
	const VERSION           = "single_file";
}
