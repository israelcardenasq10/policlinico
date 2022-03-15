<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Tpv.php");


class Superv extends Tpv {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('tpv_model');
		$this->load->model('productos_model');
		$this->load->model('almacen_model');
		$this->load->model('series_documentos_model');
		$this->load->model('globales_model');
		$this->load->model('ventas_model');
        
		$this->load->library(array('session','form_validation', 'EscPos.php', 'NumeroALetras.php'));

		$data['id_user'] = $this->session->userdata('id_user');
		$data['person_id'] = $this->session->userdata('person_id');
		$data['username'] = $this->session->userdata('username');

		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$data['lis_categorias'] = $this->tpv_model->listarCategorias();
		$data['lis_empleados'] = $this->tpv_model->listarEmpleados($this->session->userdata('id_perfil') , $this->session->userdata('person_id') ); //5, 7 ID PERFIL = Barista, Caja
		$data['lis_mesas'] = $this->tpv_model->listarMesas();
		$data['lista_documentos'] = $this->series_documentos_model->listar();
		$data['lis_tpagos'] = $this->tpv_model->listarTipoPagos();
		
		// $this->getModulesAccion($data['id_perfil'], $data['module_id']);
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		$this->load->view('punto_venta/pos');

	}

	
}