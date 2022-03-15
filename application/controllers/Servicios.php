<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Servicios extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('servicios_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --
		
        $data['lista_categorias'] = $this->servicios_model->listaCategorias();
        
		$data['modo'] = '';
		$data['p_modulo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->servicios_model->listar();
		$this->load->view("servicios/main", $data);
	}

	public function insertar()
	{
		$data = array(
			'nombres' => ucwords($this->input->post('nombres')),
			'id_categoria' => $this->input->post('id_cate_serv'),
            'cuenta_conta' => $this->input->post('cuenta_conta')
		);
		$this->servicios_model->insertar($data);
		$data['lista'] = $this->servicios_model->listar();
		$data['v_ajax'] = 'servicios';
		$this->load->view("servicios/ajax", $data);
	}

	public function ver()
	{
		$data['bus_dato'] = $this->servicios_model->ver($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizar()
	{
        $data = array(
			'nombres' => ucwords($this->input->post('nombres')),
            'id_categoria' => $this->input->post('id_cate_serv'),
            'cuenta_conta' => $this->input->post('cuenta_conta')
		);
		$this->servicios_model->actualizar($this->input->post('id_serv_prov'), $data);
		$data['lista'] = $this->servicios_model->listar();
		$data['v_ajax'] = 'servicios';
		$this->load->view("servicios/ajax", $data);
	}

	public function eliminar()
	{
		$this->servicios_model->eliminar($this->input->post('id')); //No Tocar el post('id')
	}

	// MANTENIMIENTO DE CATEGORIA
	public function listarCategoriaServicio()
	{
		//$data['lista'] = $this->servicios_model->listar();
		$data['modo'] = 'categorias';
        $data['p_modulo'] = 'categorias';
		//$data['page_js'] = 'servicios_cat.js';
		$this->load->view("servicios/main", $data);
	}
    
	public function listarCategoria()
	{	
		$data['lista'] = $this->servicios_model->listar();
		$this->load->view("servicios/main", $data);
	}    
    
	public function insertarCategorias()
	{
		$data = array(
			'id_cate_serv' => $this->input->post('id_cate_serv'),
			'nombre' => strtoupper($this->input->post('nombre')),
            'estado' => 1
		);
        
        $this->servicios_model->insertarCat($data);
		$data['lista'] = $this->servicios_model->listaCategorias();
		$data['v_ajax'] = 'categorias';
        $data['p_modulo'] = 'categorias';
		$this->load->view("servicios/ajax", $data);
        
	}    
    
	public function verCategorias()
	{
		$data['bus_dato'] = $this->servicios_model->verCat($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}    
    
	public function actualizarCategorias()
	{
		$data = array(
			'id_cate_serv' => $this->input->post('id_cate_serv'),
			'nombre' => strtoupper($this->input->post('nombre')),
            'estado' => 1
		);
        //print_r($data);
       
        $this->servicios_model->actualizarCat($this->input->post('id_cate_serv'),$data);
		$data['lista'] = $this->servicios_model->listaCategorias();
		$data['v_ajax'] = 'categorias';
        $data['p_modulo'] = 'categorias';
		$this->load->view("servicios/ajax", $data);
       
	}
    
	public function eliminarCategorias()
	{
		$this->servicios_model->eliminarCat($this->input->post('id')); //No Tocar el post('id')
	}

}