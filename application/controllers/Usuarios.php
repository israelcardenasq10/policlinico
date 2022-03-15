<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Usuarios extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('usuarios_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		// Listado desplegables / Combobox
		$data['lista_empleados'] = $this->usuarios_model->listarEmpleados();
		$data['lista_perfiles'] = $this->usuarios_model->listarPerfiles();
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->usuarios_model->listar();
		$this->load->view("usuarios/main", $data);
	}

	public function insertar()
	{
		$data = array(
				'person_id' => $this->input->post('person_id'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'deleted' => $this->input->post('deleted'),
				'id_owner' => $this->session->userdata('person_id'),
				'id_perfil' => $this->input->post('id_perfil')
			);
		$this->usuarios_model->insertar($data);
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->usuarios_model->ver($id);
		$data['lista_empleados'] = $this->usuarios_model->verEmpleado($data['bus_dato'][0]->person_id);
		$data['modo'] = 'actualizar';
		$this->load->view("usuarios/main", $data);
	}

	public function actualizar()
	{
		$password = $this->input->post('password');
		
		$data = array(
				'person_id' => $this->input->post('person_id'),
				'username' => $this->input->post('username'),
				'id_perfil' => $this->input->post('id_perfil'),
				'deleted' => $this->input->post('deleted')
			);

		if(!empty($password))
			$data['password'] = md5($this->input->post('password'));		

		$this->usuarios_model->actualizar($this->input->post('id'), $data);		
	}

	public function eliminar()
	{
		$this->usuarios_model->eliminar($this->input->post('id'));
	}

}