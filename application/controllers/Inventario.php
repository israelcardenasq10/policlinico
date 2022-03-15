<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Inventario extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('inventario_model');
    	$this->load->model('inventarioCat_model');
    	$this->load->model('inventarioArea_model');

		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --
		
		// Listado desplegables / Combobox
		$data['lista_categorias'] = $this->inventario_model->listarCategorias();
        $data['lista_areas']      = $this->inventario_model->listarAreas();
		$data['lista_perfiles']   = $this->inventario_model->listarPerfiles();
        $data['lista_proveedor']  = $this->inventario_model->listarProveedor("OT");
        $data['lista_nromax']     = $this->inventario_model->regMax();
        //$data['lista_nromax'][0]->id_inventario;
        
		$data['modo'] = '';
		$data['p_categoria'] = 'categoria';
		$data['p_area'] = 'area';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->inventario_model->listar();
		$this->load->view("inventario/main", $data);
	}

	public function insertar()
	{        
        $area = $this->input->post('hab_area');
        $cate = $this->input->post('id_cat');
        $id   = $this->input->post('id_inventario');        
        $codigo = $area."-".$cate."-".mdate("%Y", time())."-".$id;
        
        $data = array(
            'hab_area' => $this->input->post('hab_area'),
            'id_cat' => $this->input->post('id_cat'),
            'descripcion' => ucwords($this->input->post('descripcion')),
            'marca_modelo' =>$this->input->post('marca_modelo'),
            'nro_serie' => $this->input->post('nro_serie'),
            'cant_unidad' => $this->input->post('cant_unidad'),
            'costo_valor' => $this->input->post('costo_valor'),
            'fecha_registro' => $this->input->post('fecha_registro'),
            'id_owner' => $this->session->userdata('person_id'),
            'prov_id' => $this->input->post('prov_id'),
            //'id_compra' => 1,  
            'codigo' => $codigo,           				
			);
            
		$this->inventario_model->insertar($data);
	}

	public function ver($id)
	{
		// Buscar datos para Editar
		$data['bus_dato'] = $this->inventario_model->ver($id);
		$data['modo'] = 'actualizar';
		$this->load->view("inventario/main", $data);
	}
    
    public function mas($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->inventario_model->ver($id);
		$data['modo'] = 'mas';
		$this->load->view("inventario/main", $data);
	}

	public function actualizar()
	{
        $data = array(               
        'id_inventario'=>$this->input->post('id_inventario'),
        'codigo'=>$this->input->post('codigo'),
        'nro_serie'=>$this->input->post('nro_serie'),
        'id_cat'=>$this->input->post('id_cat'),
        'hab_area'=>$this->input->post('hab_area'),
        'cant_unidad'=>$this->input->post('cant_unidad'),
        /*'prov_id'=>$this->input->post('prov_id'),*/
        'descripcion' => ucwords($this->input->post('descripcion')),
        'costo_valor'=>$this->input->post('costo_valor'),
        'marca_modelo'=>$this->input->post('marca_modelo'),
        'fecha_registro'=>$this->input->post('fecha_registro')        
        );

		$this->inventario_model->actualizar($this->input->post('id_inventario'), $data);
	}

	public function eliminar()
	{
        $this->inventario_model->eliminar($this->input->post('id'));    
	}

	// Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Inventarios';
		$this->load->view("inventario/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Inventarios';
		$data['lista'] = $this->inventario_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("inventario/report", $data);
	}



	// MANTENIMIENTO DE INVENTARIO CATEGORIA
	public function listarCategoria()
	{
		$data['lista'] = $this->inventarioCat_model->listar();
		$data['modo'] = 'categorias';
		$data['page_js'] = 'inventario_cat.js';
		//$this->load->view("inventario/categoria", $data);
		$this->load->view("inventario/main", $data); 
	}

	public function insertarCategoria()
	{
		if($this->inventarioCat_model->validarCod($this->input->post('id_cat')) === 1) // Si el dato existe!
  			$data['valida_dato'] = 'existe';
  		else
  		{
			$data = array(
				'id_cat' => strtoupper($this->input->post('id_cat')),
				'nombre' => ucwords($this->input->post('nombre'))
			);
			$this->inventarioCat_model->insertar($data);
		}

		$data['lista'] = $this->inventarioCat_model->listar();
		$data['v_ajax'] = 'categorias';
		$this->load->view("inventario/ajax", $data);
	}

	public function verCategoria()
	{
		$data['bus_dato'] = $this->inventarioCat_model->ver($this->input->post('id'));
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizarCategoria()
	{
        $data = array(
			//'id_cat' => strtoupper($this->input->post('id_cat')),
			'nombre' => ucwords($this->input->post('nombre'))
		);
		$this->inventarioCat_model->actualizar($this->input->post('id_cat'), $data);
		$data['lista'] = $this->inventarioCat_model->listar();
		$data['v_ajax'] = 'categorias';
		$this->load->view("inventario/ajax", $data);
	}

	public function eliminarCategoria()
	{
		//$data['lista_categorias'] = $this->inventario_model->listarCategorias();
		$this->inventarioCat_model->eliminar($this->input->post('id'));
	}
	// --



	// MANTENIMIENTO DE INVENTARIO AREA
	public function listarArea()
	{
		$data['lista'] = $this->inventarioArea_model->listar();
		$data['modo'] = 'areas';
		$data['page_js'] = 'inventario_area.js';
		$this->load->view("inventario/main", $data); 
	}

	public function insertarArea()
	{
		if($this->inventarioArea_model->validarCod($this->input->post('id_area')) === 1) // Si el dato existe!
  			$data['valida_dato'] = 'existe';
  		else
  		{
			$data = array(
				'id_area' => strtoupper($this->input->post('id_area')),
				'nombre' => ucwords($this->input->post('nombre'))
			);
			$this->inventarioArea_model->insertar($data);
		}
		
		$data['lista'] = $this->inventarioArea_model->listar();
		$data['v_ajax'] = 'areas';
		$this->load->view("inventario/ajax", $data);
	}

	public function verArea()
	{
		$data['bus_dato'] = $this->inventarioArea_model->ver($this->input->post('id')); //Parametro ID fijo
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizarArea()
	{
        $data = array(
			//'id_area' => strtoupper($this->input->post('id_area')),
			'nombre' => ucwords($this->input->post('nombre'))
		);
		$this->inventarioArea_model->actualizar($this->input->post('id_area'), $data);
		$data['lista'] = $this->inventarioArea_model->listar();
		$data['v_ajax'] = 'areas';
		$this->load->view("inventario/ajax", $data);
	}

	public function eliminarArea()
	{
		$this->inventarioArea_model->eliminar($this->input->post('id')); //Parametro ID fijo
	}
	// --

}