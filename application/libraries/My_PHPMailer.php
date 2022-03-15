<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_PHPMailer {
	function __construct()
	{
	    require_once(APPPATH.'libraries/PHPMailer/src/PHPMailer.php');
	}
}

/*
require_once(APPPATH.'libraries/PHPMailer/class.phpmailer.php');

class My_PHPMailer {

	private $data;

	function __construct()
	{
	    $this->data = new PHPMailer();
	}

}
*/