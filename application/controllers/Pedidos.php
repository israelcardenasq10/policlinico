<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Pedidos extends Secure_area {
	private $cod_categoria;
	private $g_ruta_printer;
	private $g_ruta_printer_cocina;
	private $g_ruta_printer_barra;
	private $g_espacio_print;

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('pedidos_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
	
		$data['modo'] = '';
		$this->load->vars($data);
		$this->g_ruta_printer_simple ='CAJA';
	}

	public function index()
	{
		$this->load->view("pedidos/main");
	}

	public function verid()
	{
		header('Content-type: application/json');
		// Buscar datos para actualizar
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$data['data'] = $this->pedidos_model->ver($id_tmp_cab);
		echo json_encode($data);
	}
				
    public function verpedidos(){
		header('Content-type: application/json');
		$desde	= $this->input->post('desde');
		$hasta	= $this->input->post('hasta');
		$data['data'] = $this->pedidos_model->listar($desde,$hasta);
		
		echo json_encode($data);
	}
	
	public function verdetallepedidos(){
		header('Content-type: application/json');
		$desde	= $this->input->post('desde');
		$hasta	= $this->input->post('hasta');
		$data['data'] = $this->pedidos_model->listar2($desde,$hasta);
		
		echo json_encode($data);
	}
	
}
