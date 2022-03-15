<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Tipo_cambio extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('tipo_cambio_model');
    	$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo!
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		$data['modo'] = '';
		$data['p_tipo_cambio'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		// Listado de datos para el datatables()
		$data['lista'] = $this->tipo_cambio_model->listar();
		$this->load->view("tipo_cambio/main", $data);
	}

	public function insertar()
	{
  		if($this->tipo_cambio_model->fechadupli() === 1) // Si la fecha existe!
  			$data['fecha_existe'] = 'fecha_existe';
  		else
  		{
  			$data = array(
				//'id_tc' => strtoupper($this->input->post('id_tc')),
				'compra' => $this->input->post('compra'),
	            'venta' => $this->input->post('venta'),
	            'fecha_registro' => mdate("%Y-%m-%d", time()),
	            'id_owner' => $this->session->userdata('person_id')
			);
			$this->tipo_cambio_model->insertar($data);

			// Invoca Globales
			$this->actualizarGlobales(array('tc' => $this->input->post('venta')));
  		}

		$data['lista'] = $this->tipo_cambio_model->listar();
		$data['v_ajax'] = 'tipo_cambio';
		$this->load->view("tipo_cambio/ajax", $data);
	}

	public function ver()
	{
		$data['bus_dato'] = $this->tipo_cambio_model->ver($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizar()
	{
        $data = array(
			//'id_tc' => strtoupper($this->input->post('id_tc')),
            'compra' => $this->input->post('compra'),
            'venta' => $this->input->post('venta'),
            'id_owner' => $this->session->userdata('person_id')
		);

		$this->tipo_cambio_model->actualizar($this->input->post('id_tc'), $data);

		// Invoca Globales
		$this->actualizarGlobales(array('tc' => $this->input->post('venta')));

		$data['lista'] = $this->tipo_cambio_model->listar();
		$data['v_ajax'] = 'tipo_cambio';
		$this->load->view("tipo_cambio/ajax", $data);
	}

	public function eliminar()
	{
		$this->tipo_cambio_model->eliminar($this->input->post('id')); //No Tocar el post('id')
	}

	// PROCESO TIPO DE CAMBIO AUTOMATICO
	public function generarTC()
  {
    $url_tc = str_replace('index.php', '', base_url().'auto_paginas/vertcdiario');

    $ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url_tc);
		$result = curl_exec($ch);
		curl_close($ch);

		$obj = json_decode($result);

		if (count($obj) == 0) {
			echo 'Falla de conexión internet, por favor vuelva actualizar su navegador web.';
			exit;
		} else {
			foreach ($obj->data as $v)
			{
				$tc_fecha = $v->fecha; // Obtiene los últimos datos del for
				$tc_compra = $v->compra;
				$tc_venta = $v->venta;
			}

			if ($this->tipo_cambio_model->fechaduplicaTC($tc_fecha) == 0) {
				if($this->tipo_cambio_model->fechadupli() === 1) // Si la fecha existe!
			  	$data['fecha_existe'] = 'fecha_existe';
				else {
					$data = array(
											'compra' => $tc_compra,
											'venta' => $tc_venta,
											'fecha_registro' => mdate("%Y-%m-%d", time()),
											'id_owner' => $this->session->userdata('person_id')
									);
					$this->tipo_cambio_model->insertar($data);
					// Invoca Globales
					$this->actualizarGlobales(array('tc' => $tc_venta));
				}
			} else {
				$data['fecha_existe'] = 'fecha_existe';
				// $data['fecha_existe'] = 'fecha_existe_tc';
			}
		}

		$data['lista'] = $this->tipo_cambio_model->listar();
		$data['v_ajax'] = 'tipo_cambio';
		$this->load->view("tipo_cambio/ajax", $data);
  }
  // --

}
