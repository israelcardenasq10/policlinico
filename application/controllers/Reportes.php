<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Reportes extends Secure_area {
 	
	private $nom_reporte;
	private $fecha_hora;

	public function __construct()
	{
		parent::__construct();
    	$this->load->model('reportes_model');
    	$this->load->model('ventas_model');

		$this->load->helper('fechas_to_excel_helper');
		$this->load->dbutil(); // To access methods BD
		
		$this->load->library(array('session','form_validation','excel'));
		
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
		//$this->nom_reporte = $this->fecha_hora."-Ventas";
		// to_excel($this->reportes_model->exportarExcelVentas($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2), $this->nom_reporte);
		$objPHPExcel = new PHPExcel();
		
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('COMPROBANTES');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', "FECHA DE EMISION"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "FECHA DE VENCIMIENTO");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "FECHA CREACION");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "TIPO");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "SERIE");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "NUMERO");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "SUCURSAL");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "DOC. CLIENTE");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "CLIENTE");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "USUARIO");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "COND. PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "T. PAGO");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "SUB TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "ANULADO");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "OBSSERVACION");
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(100);
        
		$i = 2;
		
		$lista = $this->ventas_model->filtrar($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2);
		foreach ($lista as $value){

			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, substr($value->fecha_emision,0,15) );
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "-");
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, PHPExcel_Shared_Date::PHPToExcel($value->fecha_creacion));
			$objPHPExcel->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value->tdoc)
			->setCellValue('E' . $i, $value->sfactu)
			->setCellValue('F' . $i, $value->nfactu)
			->setCellValue('G' . $i, "POLICLINICO")
			->setCellValue('H' . $i, $value->doc_cliente)
			->setCellValue('I' . $i, $value->cliente)
			->setCellValue('J' . $i, $value->username)
			->setCellValue('K' . $i, "CONTADO")
			->setCellValue('L' . $i, $value->tipo_pago)
			->setCellValue('M' . $i, $value->subtotal_venta)
			->setCellValue('N' . $i, $value->igv)
			->setCellValue('O' . $i, $value->total_venta)
			->setCellValue('P' . $i, $value->anulado)
			->setCellValue('Q' . $i, $value->glosa);
			$objPHPExcel->getActiveSheet()->getStyle('M' . $i)->getNumberFormat()->setFormatCode('0.00'); 
			$objPHPExcel->getActiveSheet()->getStyle('N' . $i)->getNumberFormat()->setFormatCode('0.00'); 
			$objPHPExcel->getActiveSheet()->getStyle('O' . $i)->getNumberFormat()->setFormatCode('0.00'); 
			
			$i++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_Comprobantes_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');

 	}
	
 	public function exportarExcelVentasRC($fecha1, $fecha2)
	{
		// $this->nom_reporte = $this->fecha_hora."-Ventas_rc";
		// to_excel($this->reportes_model->exportarExcelVentasRC($fecha1, $fecha2), $this->nom_reporte);
		$this->load->library('excel');

        $objPHPExcel = new PHPExcel();
        // $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        //         ->setLastModifiedBy("Maarten Balliauw")
        //         ->setTitle("Office 2007 XLSX Test Document")
        //         ->setSubject("Office 2007 XLSX Test Document")
        //         ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        //         ->setKeywords("office 2007 openxml php")
        //         ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('REPORTE CONSOLIDADOS');

		$objPHPExcel->getActiveSheet()->setCellValue('A1', "FECHA"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "ESTADO ANULADO");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "SUB_TOTAL");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "TOTAL");

		$objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        
		$lista = $this->ventas_model->filtrarRC($fecha1, $fecha2 );
		
		$i = 2;

		foreach ($lista as $value){

			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, PHPExcel_Shared_Date::PHPToExcel($value->fecha_registro) );
			$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value->anulado);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value->subtotal_venta);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value->igv);
			$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $value->total_venta);
			$objPHPExcel->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode('0.00'); 
			$objPHPExcel->getActiveSheet()->getStyle('D' . $i)->getNumberFormat()->setFormatCode('0.00'); 
			$objPHPExcel->getActiveSheet()->getStyle('E' . $i)->getNumberFormat()->setFormatCode('0.00'); 

			$i++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_Consolidados_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');
	
	}

 	public function exportarExcelVentasRDP($fecha1, $fecha2, $cbo_1)
	{
		// $this->nom_reporte = $this->fecha_hora."-Ventas_rdp";
		// to_excel($this->reportes_model->exportarExcelVentasRDP($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
	    $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('REPORTE X PRODUCTOS');

		$objPHPExcel->getActiveSheet()->setCellValue('A1', "PRODUCTO"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "CANT.VENTA");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "TOTAL VENTA");
        
		$objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(10);
        
		$i = 2;
		$lista = $this->ventas_model->filtrarRDP($fecha1, $fecha2 ,$cbo_1);
		foreach ($lista as $value){

			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value->producto );
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value->venta);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value->total);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->getNumberFormat()->setFormatCode('0.00'); 
			$i++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_x_Producto_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');
	}

 	public function exportarExcelVentasBar($fecha1, $fecha2, $cbo_1)
	{
		// $this->nom_reporte = $this->fecha_hora."-Ventas_bar";
		// to_excel($this->reportes_model->exportarExcelVentasBar($fecha1, $fecha2, $cbo_1), $this->nom_reporte);
		
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('REPORTE CONSOLIDADOS');

		$objPHPExcel->getActiveSheet()->setCellValue('A1', "DNI"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "NOMBRES");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "USER");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "TOTAL VENTA");

		$objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(6);
        
		$lista = $this->ventas_model->filtrarVentaBar($fecha1, $fecha2, $cbo_1 );
		
		$i = 2;

		foreach ($lista as $value){

			$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value->nro_doc );
			$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $value->usuario);
			$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $value->username);
			$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $value->total_venta);
			$objPHPExcel->getActiveSheet()->getStyle('D' . $i)->getNumberFormat()->setFormatCode('0.00'); 

			$i++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_x_usuario_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');
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