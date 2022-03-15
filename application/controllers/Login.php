<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
	    parent::__construct();

	    $this->load->model('login_model'); //Carga el Model
	    $this->load->helper('form');
	   	$this->load->library(array('session','form_validation'));
      	$this->load->model('globales_model');

	    $data['globales'] = $this->globales_model->listar();
		$data['g_ruc'] = $data['globales'][0]->ruc;
		$data['g_razon_social'] = $data['globales'][0]->razon_social;
		$data['g_direccion'] = $data['globales'][0]->direccion; // -- declarar en el view: $g_direccion
		$data['g_telefono'] = $data['globales'][0]->telefono;
		$data['g_email'] = $data['globales'][0]->email;
		$data['g_tema'] = $data['globales'][0]->tema;
		$data['g_logotipo'] = $data['globales'][0]->logotipo;

		$this->load->vars($data); //Envia valores al VIEW por default
		date_default_timezone_set('America/Lima');
	}

	public function index()
	{
		$this->login();
	}

	public function login()
	{
		if ($this->session->userdata('username') == false)
		{
		    //Validar los campos del formulario
		    $this->form_validation->set_rules('username', 'Usuario', 'required');
		    $this->form_validation->set_rules('password', 'Password', 'required');

		    if ($this->form_validation->run() == false)
		    {
		    	$this->load->view('login');
		    }
		    else
		    {
				//Asignar variables a lo obtenido desde el formulario
				$usr = $this->input->post('username');
				$pass = md5($this->input->post('password'));

				$valor_login = $this->login_model->login($usr, $pass);

				if($valor_login == 0)
					$ingreso = 0;
				else
					$ingreso = 1;

				switch ($ingreso):
				case 0:
					$this->session->set_flashdata('notice', 'Cuenta Incorrecta, intente de nuevo!'); //establece mensaje personalizado de manera temporal
				  	$this->load->view('login');
					break;
				case 1:
					$data = array(
				  		'id_user' => $valor_login[0]->id,
				  		'person_id' => $valor_login[0]->person_id,
				    	'username' => $usr,
				    	'id_perfil' => $valor_login[0]->id_perfil
				  	);
					$this->session->set_userdata($data);

					// Registra Session!
					//To serialize
					$datestring = "%Y-%m-%d";
			        $horastring = "%h:%i:%s";
					$time = time();
					$fecha['fecha'] = mdate($datestring, $time);
					$fecha['hora'] = mdate($horastring, $time);

					$insert = array(
								'person_id' => $valor_login[0]->person_id,
								//'session' => serialize($data),
								'session' => json_encode($data),								
								'fecha_login'  => $fecha['fecha'].'T'.$fecha['hora']
							);
					$this->login_model->insertarSession($insert);
					

					if($valor_login[0]->id_perfil == 1) // MASTER
					{
						redirect('panel');
					}
					else if($valor_login[0]->id_perfil == 2) // ADMINISTRADOR
					{
						redirect('panel');
					}
					else if($valor_login[0]->id_perfil == 3) // JEFE
					{
						redirect('panel');
					}
					else if($valor_login[0]->id_perfil == 4) // EJECUTIVO
					{
						redirect('panel');
					}
					else // BARISTA - CAJA - SUPERVISOR
					{
						redirect('tpv');
					}
					break;
				endswitch;
			}
		}
		else
		{
			$this->session->sess_destroy();
			redirect('login');
		}
	}

}
