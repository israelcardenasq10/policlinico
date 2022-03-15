<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Asistencias extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('asistencias_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		$data['lista_empleados'] = $this->asistencias_model->listarEmpleados();

		$data['modo'] = '';
		$this->load->vars($data);
	}
    
    public function resta($inicio, $fin)
    {
        $dif=date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio) );
        return $dif;
    }    

	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista'] = $this->asistencias_model->listar();
        $this->load->view("asistencias/main", $data);
	}

	public function insertar()
	{
        $hora_diferencia = (strtotime($this->input->post('hora_logout')) - strtotime($this->input->post('hora_login')));                
        $horas = round(($hora_diferencia/60/60),2);   		
        
        $data = array(
                'id_emple' => $this->input->post('cbo_1'),
                'fecha_login' => $this->input->post('fecha_login'),
                'fecha_logout' => $this->input->post('fecha_login'),                
				'hora_login' => $this->input->post('hora_login'),
				'hora_logout' => $this->input->post('hora_logout'),
                'horas_trabajo' => $horas,
                'consecutivo' => 1,
                'fecha_registro' => date('Y-m-d H:i:s'), 
				'concepto' => $this->input->post('concepto'),
                'id_owner' => $this->session->userdata('person_id')
			);
		$this->asistencias_model->insertar($data);
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->asistencias_model->ver($id);
		$data['modo'] = 'actualizar';
		$this->load->view("asistencias/main", $data);
	}

	public function actualizar()
	{
        $hora_diferencia = (strtotime($this->input->post('hora_logout')) - strtotime($this->input->post('hora_login')));                
        $horas = round(($hora_diferencia/60/60),2);        
		$data = array(
                'horas_trabajo' => $horas,
				'hora_login' => $this->input->post('hora_login'),
				'hora_logout' => $this->input->post('hora_logout'),
				'concepto' => $this->input->post('concepto'),
                'id_owner' => $this->session->userdata('person_id')
			);
		$this->asistencias_model->actualizar($this->input->post('id'), $data);		
	}

	public function eliminar()
	{
	   $this->asistencias_model->eliminar($this->input->post('id'));
	}


	// Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Asistencias';
		$this->load->view("asistencias/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Asistencias';
		$data['lista'] = $this->asistencias_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("asistencias/report", $data);
	}
	// --
}