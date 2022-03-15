<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");
include_once 'application/libraries/PHPExcel/IOFactory.php';
class Asistencia extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('asistencia_model');
		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --
		$data['lista_empleados'] = $this->asistencia_model->listarEmpleados();
		$data['turno']= $this->asistencia_model->listarTabla('turno');
		$data['modalidad']= $this->asistencia_model->listarTabla('modalidad');
		$data['modo'] = '';
		$this->load->vars($data);
	}
	
	
	public function index()
	{	
		// Listado de datos para el datatables()
		$data['lista_horario'] = $this->asistencia_model->listarHorarios();
		$data['lista_marcas']  = $this->asistencia_model->listarMarcas();
		$data['lista_asistencia']=$this->asistencia_model->listarAsistencia();
        $this->load->view("asistencia/main", $data);
	}

	public function insertar()
	{
		$table='tb_persona_horarios';

        $data = array(
                'id_empleado' => $this->input->post('cbo_1'),
                'desde' => $this->input->post('desde'),
                'hasta' => $this->input->post('hasta'),                
				'turno' => $this->input->post('turno'),
				'modalidad' => $this->input->post('modalidad'),
				'h_in_lu' => $this->input->post('h_in_lu'),
				'h_sa_lu' => $this->input->post('h_sa_lu'),
				'h_in_ma' => $this->input->post('h_in_ma'),
				'h_sa_ma' => $this->input->post('h_sa_ma'),
				'h_in_mi' => $this->input->post('h_in_mi'),
				'h_sa_mi' => $this->input->post('h_sa_mi'),
				'h_in_ju' => $this->input->post('h_in_ju'),
				'h_sa_ju' => $this->input->post('h_sa_ju'),
				'h_in_vi' => $this->input->post('h_in_vi'),
				'h_sa_vi' => $this->input->post('h_sa_vi'),
				'h_in_sa' => $this->input->post('h_in_sa'),
				'h_sa_sa' => $this->input->post('h_sa_sa'),
				'h_in_do' => $this->input->post('h_in_do'),
				'h_sa_do' => $this->input->post('h_sa_do'),
				'h_refrigerio' => $this->input->post('h_refrigerio'),
			);
		$this->asistencia_model->insertar($table,$data);
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->asistencia_model->ver($id);
		$data['modo'] = 'actualizar';
		$this->load->view("asistencia/main", $data);
	}

	public function actualizar()
	{

		$table='tb_persona_horarios';

        $data = array(
                'id_empleado' => $this->input->post('cbo_1'),
                'desde' => $this->input->post('desde'),
                'hasta' => $this->input->post('hasta'),                
				'turno' => $this->input->post('turno'),
				'modalidad' => $this->input->post('modalidad'),
				'h_in_lu' => $this->input->post('h_in_lu'),
				'h_sa_lu' => $this->input->post('h_sa_lu'),
				'h_in_ma' => $this->input->post('h_in_ma'),
				'h_sa_ma' => $this->input->post('h_sa_ma'),
				'h_in_mi' => $this->input->post('h_in_mi'),
				'h_sa_mi' => $this->input->post('h_sa_mi'),
				'h_in_ju' => $this->input->post('h_in_ju'),
				'h_sa_ju' => $this->input->post('h_sa_ju'),
				'h_in_vi' => $this->input->post('h_in_vi'),
				'h_sa_vi' => $this->input->post('h_sa_vi'),
				'h_in_sa' => $this->input->post('h_in_sa'),
				'h_sa_sa' => $this->input->post('h_sa_sa'),
				'h_in_do' => $this->input->post('h_in_do'),
				'h_sa_do' => $this->input->post('h_sa_do'),
				'h_refrigerio' => $this->input->post('h_refrigerio'),
			);
			$pk='personahorario';
			$id=$this->input->post('personahorario');
		$this->asistencia_model->actualizar($pk,$id, $table,$data);		
	}

	public function eliminar()
	{
	   $this->asistencia_model->eliminar($this->input->post('id'));
	}


	// Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Asistencias';
		$this->load->view("asistencia/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Asistencias';
		$data['lista'] = $this->asistencia_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("asistencia/report", $data);
	}
	public function procAsist($fecha1, $fecha2)
	{
		$val=$this->asistencia_model->procAsist($fecha1, $fecha2);
		if ($val){
			$data['titulo_main'] = 'Reporte de Asistencias';
			$data['lista'] = $this->asistencia_model->filtrar($fecha1, $fecha2, 0);
			$data['fecha_1'] = $fecha1;
			$data['fecha_2'] = $fecha2;
			$data['cbo_1'] = 0;
			$this->load->view("asistencia/report", $data);
		}
		
	}
	// TODO: INICIO CODE, IMPORTANDO DATOS

    public function importarArchivoDatos(){
        $tabla = "tb_marcaciones";
		$objPHPExcel = PHPEXCEL_IOFactory::load($_FILES['archivoExcel']['tmp_name']);
		$objPHPExcel->setActiveSheetIndex(0);
		
		$cantColumns = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		$id_usuario=$this->session->userdata('id_user');

        for ($i=2; $i <= $cantColumns ; $i++) {
			$dni = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
			$fechahora=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
			$datos = array(
				'id_empleado'=> 0,
				'fecha_hora' => $fechahora,
				'dni' => $dni,
				'id_owner' => $id_usuario
            );
			$rptaImportar = $this->asistencia_model->mdlRegistroDatos($tabla, $datos);
			$resUpdate=$this->asistencia_model->updatemarcas();
        }

        if ($rptaImportar == TRUE) {
			echo '<script>
				alert("Archivo Importado Correctamente");
                </script>
			';
			if($resUpdate ==TRUE){
				redirect('asistencias');
			}
        }else{
			echo '<script>
				alert("Hubo un error vuelva a intentar nuevamente con los formatos correctos..");
                </script>
            ';
		}

    }

    // FIXME: FIN CODE, IMPORTANDO DATOS
}