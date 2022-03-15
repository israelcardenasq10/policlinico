<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Mesas extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('mesas_model');
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
		$data['lista'] = $this->mesas_model->listar();
		$this->load->view("mesas/main", $data);
	}

	public function insertar()
	{
        $data = array(
        	'mesa' => ucwords($this->input->post('mesa')),
        	'alias' => $this->input->post('alias'),
        	'estado' => $this->input->post('estado')
        );
        $this->mesas_model->insertar($data);
        
        $data['lista'] = $this->mesas_model->listar();
        $data['v_ajax'] = 'mesas';
        $this->load->view("mesas/ajax", $data);
	}

	public function ver()
	{
		$data['bus_dato'] = $this->mesas_model->ver($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizar()
	{
        $data = array(
			'mesa' => ucwords($this->input->post('mesa')),
        	'alias' => $this->input->post('alias'),
        	'estado' => $this->input->post('estado')
		);

		$this->mesas_model->actualizar($this->input->post('id_mesa'), $data);

		$data['lista'] = $this->mesas_model->listar();
		$data['v_ajax'] = 'mesas';
		$this->load->view("mesas/ajax", $data);
	}

	public function eliminar()
	{
		$this->mesas_model->eliminar($this->input->post('id')); //No Tocar el post('id')
	}
}