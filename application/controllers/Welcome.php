<?php
// defined("BASEPATH") OR exit("No direct script access allowed");

// class Welcome extends CI_Controller {

	$txt = '$db["default"] = array(
		"dsn"	=> "",
		"hostname" => "localhost",
		"username" => "sa",
		"password" => "1q2w3e4r.",
		"database" => "bd_charlee",
		"dbdriver" => "sqlsrv",
		"dbprefix" => "",
		"pconnect" => FALSE,
		"db_debug" => (ENVIRONMENT !== "production"),
		"cache_on" => FALSE,
		"cachedir" => "",
		"char_set" => "utf8",
		"dbcollat" => "utf8_general_ci",
		"swap_pre" => "",
		"encrypt" => FALSE,
		"compress" => FALSE,
		"stricton" => FALSE,
		"failover" => array(),
		"save_queries" => TRUE
	);';

	echo base64_encode($txt);
// }