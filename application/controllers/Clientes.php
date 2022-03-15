<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Clientes extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('clientes_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		// Listado desplegables / Combobox
		//$data['lista_empleados'] = $this->clientes_model->listarEmpleados();
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->clientes_model->listar();
		$this->load->view("clientes/main", $data);
	}

	// Verifica si existe el dato en el Sistema
	public function verificarDato()
	{
		$valor = $this->input->post('valor');
		$campo = 'nro_doc';
		$tabla = 'gs_datos';
		if($this->privilegios_model->exists($tabla, $campo, $valor) === 1) // Si el dato existe!
  			echo 'error';
  		else
  			echo 'success';	
	}

	public function insertar()
	{
		if(!$this->input->post('razon_social'))
		{
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$razon_social = $first_name.' '.$last_name;
		}
		else
		{
			$first_name = '';
			$last_name = '';
			$razon_social = strtoupper($this->input->post('razon_social'));
		}

		$data = array(
				'tipo_doc' => $this->input->post('tipo_doc'),
				'nro_doc' => $this->input->post('nro_doc'),
				'tipo_user' => 'cli',
				'first_name' => $first_name,
				'last_name' => $last_name,
				'phone_number' => $this->input->post('phone_number'),
				'celular' => $this->input->post('celular'),
				'email' => $this->input->post('email'),
				'address_1' => $this->input->post('address_1'),
				'address_2' => $this->input->post('address_2'),
				'city' => $this->input->post('city'),
				'state' => 'Lima',
				'zip' => 'Lima38',
				'country' => 'Peru',
				'comments' => $this->input->post('comments'),
				'fecha_nace' => $this->input->post('fecha_nace'),
				'fecha_registro' => $this->input->post('fecha_registro')
			);
		$person_id = $this->clientes_model->insertar($data);

		$data_d = array(
				'person_id' => $person_id,
				'razon_social' => $razon_social,
				'deleted' => 0,
				'tipo_doc' => $this->input->post('tipo_doc'),
				'id_owner' => $this->session->userdata('person_id')
			);
		$this->clientes_model->insertarDetalle($data_d);
		// Se imprime solo cuando se va a subir un Nuevo Archivo
		echo $person_id;
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->clientes_model->ver($id);
		//Muestra el User creador:
		$data['user_creador_data'] = $this->privilegios_model->getUserCreator($data['bus_dato'][0]->id_owner);
		//--
		$data['modo'] = 'actualizar';
		$this->load->view("clientes/main", $data);
	}

	public function actualizar()
	{
		$first_name = strtoupper($this->input->post('razon_social'));
		$last_name = '';
		$razon_social = strtoupper($this->input->post('razon_social'));

		$data = array(
				'tipo_doc' => $this->input->post('tipo_doc'),
				'nro_doc' => $this->input->post('nro_doc'), 
				'tipo_user' => 'cli',
				'first_name' => $first_name,
				'last_name' => $last_name,
				'phone_number' => $this->input->post('phone_number'),
				'celular' => $this->input->post('celular'),
				'email' => $this->input->post('email'),
				'address_1' => $this->input->post('address_1'),
				'address_2' => $this->input->post('address_2'),
				'city' => $this->input->post('city'),
				'state' => 'Lima',
				'zip' => 'Lima38',
				'country' => 'Peru',
				'comments' => $this->input->post('comments'),
				'fecha_nace' => $this->input->post('fecha_nace'),
				'fecha_modifica' => mdate("%Y-%m-%d", time())
			);
		
		$data_d = array(
					'razon_social' => $razon_social,
					'nro_doc' => $this->input->post('nro_doc'), 
					'id_owner' => $this->session->userdata('person_id')
			);
		$this->clientes_model->actualizar($this->input->post('person_id'), $data , $data_d );
	}

	public function eliminar()
	{
		$this->clientes_model->eliminar($this->input->post('id'));
	}

	// Subir Archivos
	public function cargarFile()
    {
        if($_FILES["archivo"]["name"])
        {
            $id = $this->input->post('id');

            $archivo = $_FILES["archivo"]["name"];
            // FCPATH -> Obtiene la ruta del directorio principal del proyecto.
            $ruta_archivo = FCPATH."public/images/users/clientes/".$archivo;
            $tmp_imagen = $_FILES["archivo"]["tmp_name"];
            copy($tmp_imagen, $ruta_archivo);

            $this->clientes_model->updateImagen($id, $archivo);
            echo $id;
        }
        else 
        {
            echo 0;
        }
    }


    // Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Clientes';
		$this->load->view("clientes/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Clientes';
		$data['lista'] = $this->clientes_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("clientes/report", $data);
	}
	// --

}