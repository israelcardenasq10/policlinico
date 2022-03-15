<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Proveedores extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('proveedores_model');
    	$this->load->model('series_documentos_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		// Listado desplegables / Combobox
		$data['lista_cat_servicios'] = $this->proveedores_model->listarCategoriasServicios();
		$data['lista_documentos'] = $this->series_documentos_model->listar();
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->proveedores_model->listar();
		$this->load->view("proveedores/main", $data);
	}

	// Verifica si existe el dato en el Sistema (Validación por Campo.)
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
				'tipo_user' => 'prov',
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
		$person_id = $this->proveedores_model->insertar($data);

		$data_d = array(
				'person_id' => $person_id,
				'id_cate_serv' => $this->input->post('id_cate_serv'),
				'razon_social' => $razon_social,
				'nombre_corto' => strtoupper($this->input->post('nombre_corto')),
				'tipo_prov' => $this->input->post('tipo_prov'),
				//'linea' => $this->input->post('linea'),
				'id_pref_1' => $this->input->post('rbdocumentos'),
				'deleted' => 0,
				'id_owner' => $this->session->userdata('person_id')
			);
		$this->proveedores_model->insertarDetalle($data_d);
		// Se imprime solo cuando se va a subir un Nuevo Archivo
		echo $person_id;
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->proveedores_model->ver($id);
		//Muestra el User creador:
		$data['user_creador_data'] = $this->privilegios_model->getUserCreator($data['bus_dato'][0]->id_owner);
		//--

		// [LISTAR DATOS EN SELECT MULTIPLE Y PASAR A OTRO]
		//echo "<br><br><br>Serv : ".$data['bus_dato'][0]->id_cate_serv;
		$data['lis_servicios'] = $this->proveedores_model->listarServicios($data['bus_dato'][0]->id_cate_serv, $data['bus_dato'][0]->person_id);
		$data['lis_servicios_prov'] = $this->proveedores_model->listarServiciosXProv($data['bus_dato'][0]->person_id);
		// --

		$data['modo'] = 'actualizar';
		$this->load->view("proveedores/main", $data);
	}

	public function actualizar()
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
				'tipo_user' => 'prov',
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

		$this->proveedores_model->actualizar($this->input->post('person_id'), $data);
		$data_d = array(
					'razon_social' => $razon_social,
					'id_cate_serv' => $this->input->post('id_cate_serv'),
					'nombre_corto' => strtoupper($this->input->post('nombre_corto')),
					'tipo_prov' => $this->input->post('tipo_prov'),
					'id_pref_1' => $this->input->post('rbdocumentos'),
					'id_owner' => $this->session->userdata('person_id')
			);
		$this->proveedores_model->actualizarDetalle($this->input->post('person_id'), $data_d);
	}

	public function eliminar()
	{
		$this->proveedores_model->eliminar($this->input->post('id'));
	}

	// Subir Archivos
	public function cargarFile()
    {
        if($_FILES["archivo"]["name"])
        {
            $id = $this->input->post('id');

            $archivo = $_FILES["archivo"]["name"];
            // FCPATH -> Obtiene la ruta del directorio principal del proyecto.
            $ruta_archivo = FCPATH."public/images/users/proveedores/".$archivo;
            $tmp_imagen = $_FILES["archivo"]["tmp_name"];
            copy($tmp_imagen, $ruta_archivo);

            $this->proveedores_model->updateImagen($id, $archivo);
            echo $id;
        }
        else
        {
            echo 0;
        }
    }

    // LISTAR DATOS EN SELECT MULTIPLE Y PASAR A OTRO
    public function actualizarDatosSelectMultiple()
    {
    	$lista_secundario = $this->input->post('lista_secundario'); //Recibe un ARRAY de JS
    	$person_id = $this->input->post('person_id');

    	if($lista_secundario)
		{
			//array_pop($lista_secundario);
			$this->proveedores_model->eliminarServicioProv($person_id);
			foreach($lista_secundario as $id_rep)
			{
				if($id_rep <> 0)
				{
					$value = array('person_id' => $person_id,
									'id_serv_prov' => $id_rep, 
									);
					$this->proveedores_model->insertarServicioProv($value);
				}
			}
		}
		else
		{
			$this->proveedores_model->eliminarServicioProv($person_id);	
		}
    }

    // Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Proveedores';
		$this->load->view("proveedores/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Proveedores';
		$data['lista'] = $this->proveedores_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("proveedores/report", $data);
	}
	// --

}