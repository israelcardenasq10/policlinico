<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Series_documentos extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('series_documentos_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --
		        
		$data['modo'] = '';
		$data['p_modulo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->series_documentos_model->listar();
		$this->load->view("series_documentos/main", $data);
	}

	public function insertar()
	{
		if($this->series_documentos_model->validarCod($this->input->post('tipo_doc')) === 1) // Si el dato existe!
  			$data['valida_dato'] = 'existe';
  		else
  		{
			$data = array(
				//'id_serie' => $this->input->post('id_serie'),
				'serie' => $this->input->post('serie'),
	            'tipo_doc' => strtoupper($this->input->post('tipo_doc')),
				'descripcion' => ucfirst($this->input->post('descripcion')),
				'tdoc'=> strtoupper($this->input->post('tdoc')),
				'local'=> strtoupper($this->input->post('local'))
			);
			$this->series_documentos_model->insertar($data);
		}
		$data['lista'] = $this->series_documentos_model->listar();
		$data['v_ajax'] = 'series_documentos';
		$this->load->view("series_documentos/ajax", $data);
	}

	public function ver()
	{
		$data['bus_dato'] = $this->series_documentos_model->ver($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizar()
	{
        $data = array(
            'serie' => $this->input->post('serie'),
            'tipo_doc' => strtoupper($this->input->post('tipo_doc')),
			'descripcion' => ucfirst($this->input->post('descripcion')),
			'tdoc'=> strtoupper($this->input->post('tdoc')),
			'local'=> strtoupper($this->input->post('local')),
		);
		$this->series_documentos_model->actualizar($this->input->post('id_serie'), $data);
		$data['lista'] = $this->series_documentos_model->listar();
		$data['v_ajax'] = 'series_documentos';
		$this->load->view("series_documentos/ajax", $data);
	}

	public function eliminar()
	{
		$this->series_documentos_model->eliminar($this->input->post('id')); //No Tocar el post('id')
	}
}