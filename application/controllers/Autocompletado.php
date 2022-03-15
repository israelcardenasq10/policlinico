<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autocompletado extends CI_Controller {

	function __construct()
	{
	    parent::__construct();
		//cargamos la base de datos
		$this->load->database('default');
		$this->load->model('autocompletado_model');
		$this->load->model('clientes_model');

		$this->load->library(array('session','form_validation'));
	}

	public function index()
	{
		//$this->load->view('');
	}
	
	public function autocompletarClienteRuc()
	{
		if($this->input->is_ajax_request() && $this->input->get('term'))
		{
			$criterio = $this->security->xss_clean($this->input->get('term'));

			if (!$criterio) return;

			$search = $this->autocompletado_model->buscarClienteRuc(strtoupper($criterio));
			// var_dump($search);
			if($search)
			{
				echo '[';
					$contador = 0;
					foreach ($search as $key => $fila)
					{
						//echo $fila->razon_social;
						//if (strpos(strtoupper($fila->razon_social), $criterio) !== false)
						if (strtoupper($fila->razon_social) )
						{
						// genera JSON
						if ($contador++ > 0) print ", ";
							print '{ 
									"label" : "'.$fila->nro_doc.'", 
										"value" : 
										{
											"person_id" : "'.$fila->person_id.'",
											"nro_doc" : "'.$fila->nro_doc.'",
											"tpo_doc" : "'.$fila->tipo_doc.'",
											"razon_social" : "'.$fila->razon_social.'" 
										} 
									}';
						}
					}
				echo ']';
			}
			else
			{
				echo '[';
				print '{ 
						"label" : "NO EXISTE CLIENTE!", 
							"value" : 
							{ 
								"razon_social" : "NO EXISTE CLIENTE!",
								"nro_doc":"'.$criterio.'"
							} 
						}';
				echo ']';
			}
		}
	}	//Cierra Function
	
	public function insertarCliente()
	{
		
		$data = array(
				'tipo_doc' => trim($this->input->post('tpo_doc')),
				'nro_doc' => trim($this->input->post('nro_doc')),
				'tipo_user' => 'cli',
				'email' => $this->input->post('email'),
				'first_name' => strtoupper($this->input->post('razon_social')),
				'fecha_registro' => date("Y-m-d") //mdate("%Y-%m-%d", time())
			);
		$data_d = array(
			'tipo_doc' =>  trim($this->input->post('tpo_doc')),
			'nro_doc' => trim($this->input->post('nro_doc')),
			'razon_social' => strtoupper($this->input->post('razon_social')),
			'id_owner' => $this->session->userdata('person_id'),
			'deleted' => 0,
		);
		$person_id = $this->clientes_model->insertar($data,$data_d);

		// $data_d = array(
		// 		'razon_social' => strtoupper($this->input->post('razon_social')),
		// 		'deleted' => 0,
		// 		'id_owner' => $this->session->userdata('person_id'),
		// 		'nro_doc' => trim($this->input->post('nro_doc'))
		// 	);
		// $this->clientes_model->insertarDetalle($data_d);
		// Se imprime solo cuando se va a subir un Nuevo Archivo
		echo $person_id;
	}

	// PROCESO BOLETA CLIENTE
	public function autocompletarClienteBoleta()
	{
		if($this->input->is_ajax_request() && $this->input->get('term'))
		{
			$criterio = $this->security->xss_clean($this->input->get('term'));

			if (!$criterio) return;

			$search = $this->autocompletado_model->buscarClienteBoleta(strtoupper($criterio));

			if($search)
			{
				echo '[';
					$contador = 0;
					foreach ($search as $key => $fila)
					{
						if (strpos(strtoupper($fila->nombres), $criterio) !== false)
						{
						// genera JSON
						if ($contador++ > 0) print ", ";
							print '{ 
									"label" : "'.$fila->nombres.'", 
										"value" : 
										{
											"person_id" : "'.$fila->id.'",
											"nro_doc" : "'.$fila->documento.'",
											"tipo_doc" : "'.$fila->tipo_doc.'",
											"email" : "'.$fila->email.'",
											"razon_social" : "'.$fila->nombres.'" 
										} 
									}';
						}
					}
				echo ']';
			}
			else
			{
				echo '[';
				print '{ 
						"label" : "NO EXISTE CLIENTE!", 
							"value" : 
							{ 
								"razon_social" : "NO EXISTE CLIENTE!"
							} 
						}';
				echo ']';
			}
		}
	}	//Cierra Function

	public function insertarClienteBoleta()
	{
		$data = array(
				'tipo_doc' => 'DNI',
				'documento' => trim($this->input->post('nro_doc')),
				'nombres' => strtoupper($this->input->post('razon_social')),
				'email' => $this->input->post('email'),
				'date_created' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
				'persona_id_created' => $this->session->userdata('person_id')
			);
		$person_id = $this->clientes_model->insertarClienteBoleta($data);
		echo $person_id;
	}

	// public function autocompletarProducto()
	// {
	// 	header('Content-type: application/json');		
	// 	$criterio = $this->input->get('term');
		
	// 	$data['data'] = $this->autocompletado_model->autocompletarProducto($criterio);
	// 	// var_dump($data);
	// 	echo json_encode($data); 

	// }	
	
	public function autocompletarProducto()
	{
		if($this->input->is_ajax_request() && $this->input->get('term'))
		{
			$criterio = $this->security->xss_clean($this->input->get('term'));

			if (!$criterio) return;

			$search = $this->autocompletado_model->autocompletarProducto(strtoupper($criterio));
			// var_dump($search);
			if($search)
			{
				echo '[';
					$contador = 0;
					foreach ($search as $key => $fila)
					{
						if (strtoupper($fila->TEXT) )
						{
						// genera JSON
						if ($contador++ > 0) print ", ";
							print '{ 
									"label" : "'.$fila->TEXT.'", 
										"value" : 
										{
											"id" : "'.$fila->id.'",
											"TEXT" : "'.$fila->TEXT.'",
											"id_categoria" : "'.$fila->id_categoria.'"
										} 
									}';
						}
					}
				echo ']';
			}
			else
			{
				echo '[';
				print '{ 
						"label" : "NO EXISTE PRODUCTO!", 
							"value" : 
							{ 
								"error" : "NO EXISTE PRODUCTO!"
							} 
						}';
				echo ']';
			}
		}
	}	
}