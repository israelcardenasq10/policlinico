<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once ("Secure_area.php");
class Control extends CI_Controller  {
	
  function __construct()
	{
    parent::__construct();
    $this->load->model('control_model');
    $this->load->model('asistencias_model');
		$this->load->library(array('session','form_validation'));
    date_default_timezone_set('America/Lima');
    
    $horastring = "%H:%i:%s";
    $time = time();
    $data['hora_sistema'] = mdate($horastring, $time);   
    $this->session->set_userdata(array("hora_sistema"=>$data['hora_sistema']));  
    $data['fecha_sistema'] = mdate("%Y-%m-%d", time());       
		$this->session->set_userdata(array("fecha_sistema"=>$data['fecha_sistema']));
        
    /*
      $zona_horaria = "-5"; //Para per�, la zona horaria es GMT-5 		      
      $formato = "H:i:s"; //El formato "H:i:s a" de tu fecha. Checa en http://www.php.net/date 		      
      $hora_fin = gmdate($formato,time()+($zona_horaria*3600)); 	         
      $data['hora_real'] = $hora_fin; 
      $this->session->set_userdata(array("hora_real"=>$data['hora_real']));
    */          
		$data['modo'] = '';
		$this->load->vars($data);
	}

  public function index()
  {
    //if($this->ObtenerIP() === $this->control_model->verIPGlobal())
    //{
      $this->load->view("control/main");
    //}
    //else
    //{
    //  $this->load->view("control/error");
    //}
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
   
        // los proxys van a�adiendo al final de esta cabecera
        // las direcciones ip que van "ocultando". Para localizar la ip real
        // del usuario se comienza a mirar por el principio hasta encontrar 
        // una direcci�n ip que no sea del rango privado. En caso de no 
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

  public function resta($inicio, $fin)
  {
      $dif = date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio) );
      return $dif;
  }

  public function verInfoEmpleado()
  {
      $lis = $this->control_model->ver(md5($this->input->post('clave')));
      
      if($lis):
          $numrow = $this->asistencias_model->verCount($lis[0]->id, $this->session->userdata('fecha_sistema'));
                  
          $trans = true;
          $valor = '';
          $valor_update = '';

          if($numrow == 1):
              $reg_1 = $this->asistencias_model->verReg1($lis[0]->id, $this->session->userdata('fecha_sistema'),$numrow);
              $id_asistencia = $reg_1[0]->id_asistencia;
              $hora_login = $reg_1[0]->hora_login;
              $valor_update = 'procede';

              $diferencia = $this->resta($reg_1[0]->hora_login, $this->session->userdata('hora_sistema')); 
              if(strtotime($diferencia) <= strtotime('00:59:59')):
                 echo "Dentro de la Hora";
                 return;
              endif;
          elseif($numrow == 2):
              $reg_2 = $this->asistencias_model->verReg2($lis[0]->id, $this->session->userdata('fecha_sistema'),$numrow);
              $id_asistencia = $reg_2[0]->id_asistencia;
              $hora_login = $reg_2[0]->hora_login;
              $valor_update = 'procede';

              $diferencia = $this->resta($reg_2[0]->hora_login, $this->session->userdata('hora_sistema')); 
              if(strtotime($diferencia) <= strtotime('00:59')):
                 echo "Dentro de la Hora";
                 return;
              endif;
          else:
              $valor = ''; 
          endif;
      
          if(@$reg_1[0]->hora_logout == null && $numrow == 1)
              $valor_update = 'procede';
          else if(@$reg_1[0]->hora_logout != null && $numrow == 1)
              $valor_update = '';
          else if(@$reg_2[0]->hora_logout != null && $numrow == 2)
              $trans = false;
            
          if($trans == true)
          {
              if($valor == '' && $valor_update == ''):
                  echo '<img class="img-circle" src="public/images/users/empleados/'.$lis[0]->imagen.'" width="300" height="300" />';
                  if($numrow == null) 
                    $con = 1;
                  else
                    $con = 2;
                    
                    $data = array(               
                          'id_emple' => $lis[0]->id,
                          'fecha_login' => date('Y-m-d H:i:s'),
                          'fecha_logout' => date('Y-m-d H:i:s'),
                          'hora_login' => date('H:i:s'),
                          //'hora_logout'=>date('H:i:s'),
                          'fecha_registro' => date('Y-m-d H:i:s'),
                          'consecutivo' => $con
                        );
                    $this->control_model->insertar($data);

              else:
                    echo '<img class="img-circle" src="public/images/vuelve.jpg" width="300" height="300" />';   
              endif;

              if($valor_update == 'procede')
              {
                  // Obtiene la cantidad de Horas trabajadas!
                  $hora_diferencia = (strtotime(date('H:i', time())) - strtotime($hora_login));                
                  $horas = round(($hora_diferencia/60/60),2);
                  // --

                  // Proceso de Feriados
                  @$fecha_feriado = $this->control_model->verCalendarioFeriado(date('Y-m-d'));
                  if(@$fecha_feriado !== NULL)
                  {
                    $horas = ($horas * 2);
                    $feriado = 'S';
                  }
                  else
                    $feriado = 'N';
                  // --

                  $data = array(  
                      'hora_logout' => date('H:i:s'),
                      'horas_trabajo' => $horas,
                      'feriado' => $feriado
                  );
                  $this->asistencias_model->actualizar($id_asistencia, $data);
              }
          }
          else
          {
              echo "Sesion Excedida +2";
          }

      else:
          echo "Clave Incorrecto";
      endif;
  }  //Fin Function           
}