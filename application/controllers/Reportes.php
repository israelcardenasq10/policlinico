<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Reportes extends Secure_area {
 	
	private $nom_reporte;
	private $fecha_hora;

	public function __construct()
	{
		parent::__construct();
    	$this->load->model('reportes_model');
		$this->load->helper('fechas_to_excel_helper');
		$this->load->dbutil(); // To access methods BD
		
		$this->load->library(array('session','form_validation'));

		// --
		$data['id_perfil'] = $this->session->userdata('id_perfil');

		if($this->router->class == $this->session->userdata('module_id'))
			$data['module_id'] = $this->session->userdata('module_id');
		else
		{
			$session['module_id'] = $this->router->class;
			$this->session->set_userdata($session);
			$data['module_id'] = $this->session->userdata('module_id');
		}
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		$this->fecha_hora = date('Y.m.d-H.i.s');

		$data['titulo_1'] = 'Exportar Ventas';
		$this->load->vars($data);
	}
 
	public function index()
	{
		$data['lista'] = NULL;
		$this->load->view("reportes/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['lista'] = $this->reportes_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("reportes/report", $data);
	}

	public function exportarExcelReportes1($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Listado";
		to_excel($this->reportes_model->exportarExcelReportes1($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}


	// El nombre del metodo tiene que ser: exportarExcel + modulo_id
	public function exportarExcelInventario($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Inventarios";
		to_excel($this->reportes_model->exportarExcelInventario($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelClientes($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Clientes";
		to_excel($this->reportes_model->exportarExcelClientes($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelAsistencias($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Asistencias";
		to_excel($this->reportes_model->exportarExcelAsistencias($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelProveedores($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Proveedores";
		to_excel($this->reportes_model->exportarExcelProveedores($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelEmpleados($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Empleados";
		to_excel($this->reportes_model->exportarExcelEmpleados($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelCompras($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Compras";
		to_excel($this->reportes_model->exportarExcelCompras($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelAlmacen($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Almacen";
		to_excel($this->reportes_model->exportarExcelAlmacen($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelProductos($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Productos";
		to_excel($this->reportes_model->exportarExcelProductos($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}
	
	public function exportarExcelVentas($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2)
	{
		$this->nom_reporte = $this->fecha_hora."-Ventas";
		to_excel($this->reportes_model->exportarExcelVentas($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2), $this->nom_reporte);
 	}
	
 	public function exportarExcelVentasRC($fecha1, $fecha2)
	{
		$this->nom_reporte = $this->fecha_hora."-Ventas_rc";
		to_excel($this->reportes_model->exportarExcelVentasRC($fecha1, $fecha2), $this->nom_reporte);
 	}

 	public function exportarExcelVentasRDP($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Ventas_rdp";
		to_excel($this->reportes_model->exportarExcelVentasRDP($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelVentasBar($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Ventas_bar";
		to_excel($this->reportes_model->exportarExcelVentasBar($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}

 	public function exportarExcelVentasMB($fecha1, $fecha2)
	{
		$this->nom_reporte = $this->fecha_hora."-M_borra";
		to_excel($this->reportes_model->exportarExcelMesasBorradas($fecha1, $fecha2), $this->nom_reporte);
 	}

	public function exportarExcelVentasCMBR($fecha1, $fecha2)
	{
		$this->nom_reporte = $this->fecha_hora."-Comandas-borradas";
		to_excel($this->reportes_model->exportarExcelComandasBorradas($fecha1, $fecha2), $this->nom_reporte);
	}

	public function exportarExcelVentasVFDM($fecha1, $fecha2)
	{
		$this->nom_reporte = $this->fecha_hora."-Ventas-fin-de-mes";
		to_excel($this->reportes_model->exportarExcelVFDM($fecha1, $fecha2), $this->nom_reporte);
	}
	public function exportarExcelVentasCCB($fecha1, $fecha2)
	{
		$this->nom_reporte = $this->fecha_hora."-Comandas";
		$data = $this->reportes_model->exportarExcelComandas($fecha1, $fecha2);
		to_excel($data , $this->nom_reporte);
		//echo $this->dbutil->csv_from_result($data);
 	}

 	public function exportarExcelMermas($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = $this->fecha_hora."-Mermas";
		to_excel($this->reportes_model->exportarExcelMermas($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
 	}


 	// -- To File CSV
 	public function exportarCSVCompras($fecha1, $fecha2, $cbo_1)
	{
		$this->nom_reporte = "csv_compras";
		$query = $this->reportes_model->exportarCSVCompras($fecha1, $fecha2, $cbo_1);
		echo $this->dbutil->csv_from_result($query);
 	}

}