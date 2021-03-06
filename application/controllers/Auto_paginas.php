<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ("Secure_area.php");
class Auto_paginas extends CI_Controller  {
	
  function __construct()
	{
    parent::__construct();
    $this->load->model('auto_pagina_model');
    $this->load->library(array('session', 'tc_automatico'));
	}

  public function index()
  {
    //$this->auto_pagina_model->actualizarIPWifi(1, array('ip_wifi' => $this->ObtenerIP()));
  }

  public function verTCDiario()
  {
    $this->tc_automatico->index('get_sunat', mdate("%m", time()), mdate("%Y", time())); // Fecha actual, ejemplo 06-2017
  }

  public function ObtenerIP()
  {
     if( @$_SERVER['HTTP_X_FORWARDED_FOR'] != '' )
     {
        $client_ip = 
           ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
              $_SERVER['REMOTE_ADDR'] 
              : 
              ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
                 $_ENV['REMOTE_ADDR'] 
                 : 
                 "unknown" );
   
        // los proxys van añadiendo al final de esta cabecera
        // las direcciones ip que van "ocultando". Para localizar la ip real
        // del usuario se comienza a mirar por el principio hasta encontrar 
        // una dirección ip que no sea del rango privado. En caso de no 
        // encontrarse ninguna se toma como valor el REMOTE_ADDR
   
        $entries = preg_split('/[, ]/', @$_SERVER['HTTP_X_FORWARDED_FOR']);
   
        reset($entries);
        while (list(, $entry) = each($entries)) 
        {
           $entry = trim($entry);
           if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
           {
              // http://www.faqs.org/rfcs/rfc1918.html
              $private_ip = array(
                    '/^0\./', 
                    '/^127\.0\.0\.1/', 
                    '/^192\.168\..*/', 
                    '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', 
                    '/^10\..*/');
   
              $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
   
              if ($client_ip != $found_ip)
              {
                 $client_ip = $found_ip;
                 break;
              }
           }
        }
     }
     else
     {
        $client_ip = 
           ( !empty($_SERVER['REMOTE_ADDR']) ) ? 
              $_SERVER['REMOTE_ADDR'] 
              : 
              ( ( !empty($_ENV['REMOTE_ADDR']) ) ? 
                 $_ENV['REMOTE_ADDR'] 
                 : 
                 "unknown" );
     }
   
     return $client_ip;
  }

}