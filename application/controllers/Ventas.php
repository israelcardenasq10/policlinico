<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");
// Pos Print
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Ventas extends Secure_area {
	private $cod_categoria;
	private $g_ruta_printer;
	private $g_ruta_printer_cocina;
	private $g_ruta_printer_barra;
	private $g_espacio_print;

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('ventas_model');
    	$this->load->model('asistencias_model');
    	$this->load->model('productos_model');

    	$this->load->model('tpv_model');
    	$this->load->model('series_documentos_model');
    	$this->load->model('globales_model');    	
		// $this->load->library(array('session','form_validation', 'NumeroALetras.php'));
		$this->load->library(array('session','form_validation', 'EscPos.php', 'NumeroALetras.php'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
	
		
		$data['lista_tp'] = $this->ventas_model->listarTP();
		$data['lista_empleados'] = $this->asistencias_model->listarEmpleados();
		$data['lista_productos'] = $this->productos_model->listarProductos();
		
		$data['modo'] = '';
		$this->load->vars($data);
		$this->g_ruta_printer_simple ='CAJA';
	}

	public function index()
	{
		// $data['lista'] = $this->ventas_model->listar(0);
		$this->load->view("ventas/main");
	}

	public function listarTodos()
	{
		$data['lista'] = $this->ventas_model->listar(1);
		$this->load->view("ventas/main", $data);
	}

	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->ventas_model->ver($id);
		$data['lista_deta'] = $this->ventas_model->verDetalleVenta($id);
		$data['modo'] = 'actualizar';
		$this->load->view("ventas/main", $data);
	}
	public function verid()
	{
		header('Content-type: application/json');
		// Buscar datos para actualizar
		$id_transac = $this->input->post('id_transac');
		$data['bus_dato'] = $this->ventas_model->ver($id_transac);
		$data['lista_deta'] = $this->ventas_model->verDetalleVenta($id_transac);
		echo json_encode($data);
	}
	
	public function anularTicket()
	{
		$id_transac = $this->input->post('id');		
		// Valida que ticket no este en CIERRE.
		$id_cierre_caja = 0;
		$id_cierre_caja = $this->ventas_model->verEstadoTicketVenta($id_transac);
		if ($id_cierre_caja == 0) {
			$data = array(
						'subtotal_venta' => 0,
						'igv' => 0,
						'costo' => 0,
						'total_venta' => 0,
						'pago_cliente' => 0,
						'vuelto' => 0,
						'estado' => 'V',
						'date_updated' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
						'persona_id_updated' => $this->session->userdata('person_id')
					);
			$this->ventas_model->anularTicketVenta($data, $id_transac);
			echo 'ok';
		} else {
			echo 'no_procede';
		}
	}
			
    // Reportes
	public function report($param = '')
	{
		$data = array(
					'lista' => NULL,
					'nro_report' => $param, //Numero de Report que da click!
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes'
				);

		$this->load->view("ventas/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2)
	{
		$lista = $this->ventas_model->filtrar($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 1,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2,
					'cbo_1' => $cbo_1,
					'anulado' => $anulado,
					'cbo_2' => $cbo_2
				);
		$this->load->view("ventas/report", $data);
	}
	
	public function filtrar_rc($fecha1, $fecha2)
	{
		$lista = $this->ventas_model->filtrarRC($fecha1, $fecha2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 2,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2
				);
		$this->load->view("ventas/report", $data);
	}
	
	public function filtrar_rdp($fecha1, $fecha2, $cbo_1)
	{
		$lista = $this->ventas_model->filtrarRDP($fecha1, $fecha2, $cbo_1);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 3,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2,
					'cbo_1' => $cbo_1
				);
		$this->load->view("ventas/report", $data);
	}

	public function filtrar_bar($fecha1, $fecha2, $cbo_1)
	{		
		$lista = $this->ventas_model->filtrarVentaBar($fecha1, $fecha2, $cbo_1);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 4,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2,
					'cbo_1' => $cbo_1
				);
		$this->load->view("ventas/report", $data);
	}
	
	public function filtrar_mb($fecha1, $fecha2)
	{
		$lista = $this->ventas_model->filtrarMB($fecha1, $fecha2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 5,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2
				);
		$this->load->view("ventas/report", $data);
	}

	public function filtrar_ccb($fecha1, $fecha2)
	{
		$lista = $this->ventas_model->filtrarCCB($fecha1, $fecha2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 6,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2
				);
		$this->load->view("ventas/report", $data);
	}
		
	public function filtrar_mbcom($fecha1, $fecha2)
	{
		$lista = $this->ventas_model->filtrarMBCOM($fecha1, $fecha2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 7,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2
				);
		$this->load->view("ventas/report", $data);
	}

	public function filtrar_vfdm($fecha1, $fecha2)
	{
		$lista = $this->ventas_model->filtrar_vfdm($fecha1, $fecha2);
		
		$data = array(
					'lista' => $lista,
					'nro_report' => 8,
					'titulo_main' => 'Reporte de Ventas',
					'titulo_main_2' => 'Reporte Consolidados',
					'titulo_main_3' => 'Reporte Detallado',
					'titulo_main_4' => 'Reporte Ventas Mozos',
					'titulo_main_5' => 'Reporte Mesas Borradas',
					'titulo_main_6' => 'Reporte Comandas',
					'titulo_main_7' => 'Reporte Comandas Anuladas',
					'titulo_main_8' => 'Reporte Ventas Fin de Mes',
					'fecha_1' => $fecha1,
					'fecha_2' => $fecha2
				);
		$this->load->view("ventas/report", $data);
	}

	public function verresumencabecera(){
		header('Content-type: application/json');
		$data['data'] = $this->ventas_model->resumencabecera();
		echo json_encode($data);
	}

	public function generarResumenDiario()
	{
		header('Content-type: application/json');
		$fecha = $this->input->post('fecha');
		$person_id=$this->session->userdata('person_id');
		
		$this->ventas_model->resumenDiario($fecha,$person_id);
		
		$registros= $this->ventas_model->resumenDiario($fecha,$person_id );		
		// ************** PROCESO SUNAT ************** //
		$ruta_dat_sunat = 'C:\SUNAT\sunat_archivos\sfs\DATA/';
		// $ruta_dat_sunat = 'C:\SUNAT\sunat_archivos\sfs/';
		// GENERAR ARCHIVO .CAB
		// RRRRRRRRRRR-RC-YYYYMMDD-CCC.RDI
		
		$date = new DateTime($fecha);
		$nom_file = $this->g_ruc.'-RC-'.$date->format('Ymd').'-001';

	    $file_rdi =  $ruta_dat_sunat.$nom_file.'.rdi';
	    if(count($registros) > 0)
	    {	        
	        if($archivo = fopen($file_rdi, "w")) //a
	        {
	            foreach ($registros as $valor)
	            {
	            	$line ="$valor->fecEmision|$valor->fecResumen|$valor->tdoc|$valor->idDocResumen|$valor->tdUser|$valor->ndocUser|PEN|".number_format($valor->totValGrabado,2)."|0.00|0.00|0.00|0.00|0.00|".number_format($valor->totImpCpe,2)."|$valor->tdoc_r|$valor->sfactu_r|$valor->nfactu_r||0.00|0.00|0.00|0.00|$valor->estado|\r\n";
                    fwrite($archivo, $line);
	            }
	            fclose($archivo);
	        }
	    }
	    // GENERAR ARCHIVO .TRD - sunat = 20509921793-03-B001-00000001.trd
	    $file_trd =  $ruta_dat_sunat.$nom_file.'.trd';
	    if(count($registros) > 0)
	    {	        
	        if($archivo = fopen($file_trd, "w")) //a
	        {
	        	foreach ($registros as $value)
	            {
	            	$line = $value->orden."|1000|IGV|VAT|".number_format($value->totValGrabado,2)."|".number_format($value->igv,2)."|\r\n";
	            	fwrite($archivo, $line);

					if($value->icbper_total>0){
						$line = $value->orden."|7152|ICBPER|OTH|".number_format($value->icbper_total,2)."|".number_format($value->icbper,2)."|\r\n";
						fwrite($archivo, $line);
					}
            	}
	            fclose($archivo);
	        }
	    }
	    // --
	    // ************** ************ ************** //
		// echo 'ok';
		$data['fecha']=$fecha.' '.$person_id;
		echo json_encode($data);
	}

	public function verresumen(){
		header('Content-type: application/json');
		$id_res=$this->input->post('id_valor');
		$data['data'] = $this->ventas_model->ver_resumen($id_res);
		echo json_encode($data);
	}
	
	public function verVentas(){
		header('Content-type: application/json');
		$tdoc	= $this->input->post('tdoc');
		$sfactu	= $this->input->post('sfactu');
		$nfactu	= $this->input->post('nfactu');
		$desde	= $this->input->post('desde');
		$hasta	= $this->input->post('hasta');
		// echo $desde;
		$data['data'] = $this->ventas_model->listar($tdoc,$sfactu,$nfactu,$desde,$hasta);
		
		echo json_encode($data);
	}

	public function vercierre(){
		header('Content-type: application/json');
		$data['data'] = $this->ventas_model->verCierres();		
		echo json_encode($data);
	}

	public function verdetcierre(){
		header('Content-type: application/json');
		$id_cierre = $this->input->post('id_cierre');
		$data['data'] = $this->ventas_model->verdetcierre($id_cierre);		
		$data['grupo'] = $this->ventas_model->grupodetcierre($id_cierre);		
		echo json_encode($data);
	}

	public function generarNC(){
		$id_transac	= $this->input->post('id_transac');
		$tpo_nc	= $this->input->post('tpo_nc');
		$glosa	= $this->input->post('glosa');
		$transac_ref = $this->tpv_model->listarTransacVentaCAB($id_transac);
		$this->ventas_model->actvtaNC($id_transac);

		$tdoc='07';
		$sfactu = trim($transac_ref[0]->sfactu);
		// $nfactu='';
		/**
		 * 8	F001	NCF 	Nota de Credito Factura
		 * 9	B003	NCB 	Nota de Credito Boleta
		 */
		$id_serie = $sfactu=='F001'?8:9;
		// $cod_max = $this->ventas_model->maxTpoSerNum($tdoc,$sfactu)
		$cod_max = $this->tpv_model->generarCodMax($id_serie);
		$num = $cod_max  + 1;
		$nfactu=str_pad($num, 8 ,"0", STR_PAD_LEFT);
		$num_doc = $tdoc.'-'.$sfactu.'-'.$nfactu;
		
		$data = array(
			'num_doc' => $num_doc,
			'subtotal_venta' => $transac_ref[0]->subtotal_venta,
			'igv' => $transac_ref[0]->igv,
			'costo' => $transac_ref[0]->costo,						
			'desc_venta' => $transac_ref[0]->desc_venta, // Guarda el id_tarjeta del Pago Diferido / Mixto.
				//'otros_cargos' => $otros_cargos, // Nuevo ICBPER bolsas
			'total_venta' => $transac_ref[0]->total_venta,
			'pago_cliente' => $transac_ref[0]->pago_cliente,
			'obs' =>'',
			'id_cierre' =>'0',
			'vuelto' => '0.00',
			'tc' => $this->g_tc,
			'moneda' => $this->g_moneda,
			'id_cliente' => $transac_ref[0]->id_cliente,
			'id_tp' => $transac_ref[0]->id_tp,
			'id_serie' => $id_serie,
			'id_tmp_cab' => 0,
			'estado' => 'D',
			'fecha_registro' => mdate("%Y-%m-%d", time()),
			'id_owner' => $this->session->userdata('person_id'),
			'date_created' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
			'persona_id_created' => $this->session->userdata('person_id'),
			'tdoc'  => $tdoc,
			'sfactu'=> $sfactu,
			'nfactu'=> $nfactu,
			'tdoc_r'  => $transac_ref[0]->tdoc,
			'sfactu_r' => $transac_ref[0]->sfactu,
			'nfactu_r' => $transac_ref[0]->nfactu,
			'n_ruc'=> $transac_ref[0]->n_ruc ,
			'n_rs'=> $transac_ref[0]->n_rs ,
			'tp_ruc'=> $transac_ref[0]->tp_ruc,
			'tpo_nc' => $tpo_nc,
			'glosa' => $glosa,
		);

		$id_nc = $this->tpv_model->insertarTransacVenta($data);
		$this->ventas_model->generarDetalleNC($id_nc,$id_transac);

		$lis_cliente = $this->tpv_model->verClienteVenta($transac_ref[0]->id_cliente);
		$clie_nro=$lis_cliente[0]->nro_doc;
		$clie_rs=$lis_cliente[0]->razon_social;
		if($sfactu == 'F001') {$idx_tdoc=6;}else{$idx_tdoc=1;} 		                
			$valor_adquiriente = $idx_tdoc.'|'.$clie_nro.'|'.$clie_rs;
		
		/**
		 * GENERAR ARCHIVOS PLANOS
		 */
		$ruta_dat_sunat = 'C:\SUNAT\sunat_archivos\sfs\DATA/';
		$nom_file = $this->g_ruc.'-'.$tdoc.'-'.$sfactu.'-'.$nfactu;

		$file_cab =  $ruta_dat_sunat.$nom_file.'.NOT';
		    $transac_venta = $this->tpv_model->listarTransacVentaCAB($id_nc);
		    if(count($transac_venta) > 0)
		    {	        
		        if($archivo = fopen($file_cab, "w")) //a
		        {
		            foreach ($transac_venta as $value)
		            {
						$line = '0101|'.date('Y-m-d').'|'.date('H:i:s').'|0000|'.$valor_adquiriente.'|PEN|'.$tpo_nc.'|'.$glosa.'|'.$value->tdoc_r.'|'.$value->sfactu_r.'-'.$value->nfactu_r.'|'.round($value->igv,2).'|'.$value->subtotal_venta.'|'.number_format($value->igv+$value->subtotal_venta, 2).'|0.00|'.$value->total_venta.'|2.1|2.0|';
	                    fwrite($archivo, $line);
		            }
		            fclose($archivo);
		        }
		    }
		// GENERAR ARCHIVO .DET - sunat = 20509921793-03-B001-00000001.det
		$file_det =  $ruta_dat_sunat.$nom_file.'.det';

		$transac_venta_deta = $this->tpv_model->listarTransacVentaDetalle($id_nc);
		if(count($transac_venta_deta) > 0)
		{
			if($archivo = fopen($file_det, "w")) //a
			{
				$icbper_line = '';
				$icbper_total = 0.00;
				$icbper_cant = 0;
				foreach ($transac_venta_deta as $value)
				{
					$mas_igv = ((100 + $this->g_igv) / 100); // Obtiene Ejm: 1.18
					$subtotal_venta = ($value->total /  $mas_igv);
					$igv_det = ($value->total - $subtotal_venta);
					$m_precio_ven_uni = ($subtotal_venta + $igv_det);

					if($value->categoria == 'BOLSAS')
					{
						$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
						$icbper_tax = $lis_icbper[0]->monto;
						$m_precio_ven_uni = ($m_precio_ven_uni + ($value->cantidad * $icbper_tax));
						$icbper_line = '7152|-|'.round($value->cantidad * $icbper_tax, 2).'|'.$value->cantidad.'|ICBPER|OTH|'.$icbper_tax.'|';	
						$icbper_total = round($value->cantidad * $icbper_tax, 2);
						$icbper_cant = $value->cantidad;
					}
					else
					{
						$icbper_tax = 0;
						//$icbper_line = '|||||||';
						$icbper_line = '0.00|-|0.00|0.00|||0.00|';
					}						

					// |-|0.00|0.00||||0.00|-|0.00|0.00|||0.00| = |-|0.00|0.00||||
					$line = 'NIU|'.$value->cantidad.'|1|-|'.$value->producto.'|'.round($subtotal_venta, 2).'|'.round($igv_det, 2).'|1000|'.round($igv_det, 2).'|'.round($subtotal_venta, 2).'|IGV|VAT|10|'.number_format($this->g_igv, 2).
							'|-|0.00|0.00||||0.00|-|0.00|0.00|||'.
							$icbper_line.
							number_format(round($m_precio_ven_uni, 2), 2).'|'.round($subtotal_venta, 2).'|0.00|'."\r\n";
					fwrite($archivo, $line);
				}
				fclose($archivo);
			}

			// Actualiza "ICBPER"
			//$data = array('otros_cargos' => $icbper_otros_cargo);
			$data = array('icbper_total' => $icbper_total, 'icbper_cant' => $icbper_cant);
			$this->tpv_model->actualizarTransacVenta($data, $id_nc);
			// --			
		}
		// GENERAR ARCHIVO .LEY - sunat = 20509921793-03-B001-00000001.ley
		$file_ley =  $ruta_dat_sunat.$nom_file.'.ley';
		$ob_nal = new NumeroALetras();
		if(count($transac_venta) > 0)
		{
			if($archivo = fopen($file_ley, "w")) //a
			{
				foreach ($transac_venta as $value)
				{		            	
					$line = '1000|'.$ob_nal->convertir(floor($value->total_venta)).' CON '.substr($value->total_venta, -2).'/100 SOLES'.'|';
					fwrite($archivo, $line);
				}
				fclose($archivo);
			}
		}
		// GENERAR ARCHIVO .TRI - sunat = 20509921793-03-B001-00000001.tri
		$file_tri =  $ruta_dat_sunat.$nom_file.'.tri';
		if(count($transac_venta) > 0)
		{	        
			if($archivo = fopen($file_tri, "w")) //a
			{
				foreach ($transac_venta as $value)				{
					$line = '1000|IGV|VAT|'.$value->subtotal_venta.'|'.round($value->igv, 2).'|';
					fwrite($archivo, $line);
				}
				fclose($archivo);
			}
		}
		// $valor_adquiriente = $idx_tdoc.'|'.$clie_nro.'|'.$clie_rs;
		$valor_QR = $this->g_ruc.'|'.$tdoc.'|'.$sfactu.'|'.$nfactu.
		'|'.$transac_venta[0]->igv.'|'.$transac_venta[0]->total_venta.
		'|'.date('Y-m-d').'|'.$idx_tdoc.'|'.$clie_nro;

		$this->generarNotaCreditoElectronica($tdoc,  $sfactu, $nfactu ,  $transac_venta, $transac_venta_deta, $valor_QR);
			
		echo "$sfactu-$nfactu";
	}


	public function generarpdf(){
		$id_transac = 213280;///197095;
		$cabecera = $this->ventas_model->ventaCab($id_transac);
		$this->load->library('Pdf');
		$medidas = array(280,1200); //"A4";
		$tcpdf = new TCPDF('p', 'pt', $medidas, true, 'UTF-8', false);
		
        $tcpdf->SetPrintHeader(false);
		$tcpdf->SetPrintFooter(false);
		// Set Title
		$tcpdf->SetTitle($cabecera[0]->nro);
		// $tcpdf->setJPEGQuality(75);
		$tcpdf->SetFont('helvetica', 'B', 8);
		$tcpdf->AddPage();
		$tcpdf->Image(APPPATH . "libraries/escpos/example/resources/logo_fs.png", '', '', 90, 90, 'PNG', false, 'C', false, 300, 'C', false, false, 0, false, false, false);
		$tcpdf->Image(APPPATH . "libraries/escpos/example/resources/campos.png",  '', 220, 250, 15, 'PNG', false, 'C', false, 300, 'C', false, false, 0, false, false, false);
			
		$html =  "<br>".$this->g_razon_social."<br>";
		$html .= $this->g_ruc."<br>";
		$html .= $this->g_direccion." / ".$this->g_distrito." - ".$this->g_ciudad."<br>";
		$html .="CAJA 01 / ".$cabecera[0]->mesa."<br>";
		$html .="Fecha de Emisión 		: ".$cabecera[0]->fecha."<br>";
		$html .="Fecha de Vencimiento 	: ".$cabecera[0]->fecha."<br>";
		$html .="Condición 				: CONTADO <br>";
		
		$tcpdf->SetXY(0,130);
		$tcpdf->writeHTML($html, true, 0, true, 0);

		$tcpdf->Output('tcpdfexample-pakainfo.pdf', 'I');
		
		// $ruta=APPPATH."\\docs";
		// $tcpdf->Output($ruta . '/example_002.pdf', 'F');
		// exit();

	}

	public function generarNotaCreditoElectronica($tdoc, $sfactu, $nfactu, $lis_tv , $lis_tv_deta, $valor_QR)
    {
        try
	    {
		    $connector = new WindowsPrintConnector($this->g_ruta_printer_simple);
            
			$subtotal = $this->obtenerTotalesVentaTK('Subtotal', $lis_tv[0]->subtotal_venta); //new item('Subtotal', $lis_tv[0]->subtotal_venta);
			$tax = $this->obtenerTotalesVentaTK('I.G.V', $lis_tv[0]->igv);
			$total = $this->obtenerTotalesVentaTK('Total', $lis_tv[0]->total_venta, true);

            $text_tipo_pago = $this->ventas_model->veriTipoPago($lis_tv[0]->id_tp);
			
            
            if($lis_tv[0]->id_tp == 1) { //Pago Efectivo
                $tipo_pago = $this->obtenerTotalesVentaTK($text_tipo_pago, $lis_tv[0]->pago_cliente);
                $vuelto = $this->obtenerTotalesVentaTK('Cambio', $lis_tv[0]->vuelto);
            } else
                $tipo_pago = $this->obtenerTotalesVentaTK($text_tipo_pago, $lis_tv[0]->pago_cliente);
            
            
			/* Date is kept the same for testing */
			$date = mdate("%d/%m/%y", time()).' '.date('H:i:s');

			/* Start the printer */
			$logo = EscposImage::load(APPPATH . "libraries/escpos/example/resources/logo_fs.png", false);
			$printer = new Printer($connector);

			/* Print top logo */
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> graphics($logo);

			/* Name of shop */
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text($this->g_razon_social."\n");
			$printer -> selectPrintMode();
			$printer -> text($this->g_ruc."\n");
			$printer -> selectPrintMode();
			$printer -> text($this->g_direccion." / ".$this->g_distrito." - ".$this->g_ciudad."\n");
			$printer -> feed();

            ;
			/* Title of receipt */
            // if($sfactu == 'F001') $val_d = 'F';
            // else $val_d = 'B';
            
			$printer -> setEmphasis(true);
			$printer -> text('NOTA DE CREDITO ELECTRONICA');
			//$printer -> text(($id_serie == 1)? 'FACTURA DE VENTA DE PRUEBA': 'BOLETA DE VENTA DE PRUEBA');
            $printer -> text("\n");
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text("NRO:".$sfactu."-".$nfactu."\n\n");
			$printer -> selectPrintMode();
			$printer -> setEmphasis(false);

			$printer -> text("CAJA 01 / CAJA / "." Responsable: ".strtoupper($this->session->userdata('username'))."\n");

			//$printer -> text("_______________________________________________\n");
            $col = EscposImage::load(APPPATH . "libraries/escpos/example/resources/campos.png", false);
			$printer -> setEmphasis(false);

			/* Print top Columns */
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> graphics($col);                
            

			/* Items */
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer -> setEmphasis(true);
			//$printer -> text(new item('', 'S/ '));
            $printer -> text("\n");
			$printer -> setEmphasis(false);
            
            // RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
			$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
			$icbper_tax = $lis_icbper[0]->monto;

			//foreach ($items as $item) {
            foreach ($lis_tv_deta as $lis)
            {
			    if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * $icbper_tax);

					$printer -> text($this->obtenerDetalleVentaTK($lis->producto, $lis->cantidad, $lis->venta, $lis->total));
					$printer -> text($this->obtenerDetalleVentaTK('IMP. BOLSA PLASTICA', '', '', number_format($precio_total,2)));
				} else {
					$printer -> text($this->obtenerDetalleVentaTK($lis->producto, $lis->cantidad, $lis->venta, $lis->total));	
				}
			}

			//$printer -> text("IMP. BOLSA PLASTICA                         0.10");
			            
			$printer -> setEmphasis(true);
			$printer -> text($subtotal);
			$printer -> setEmphasis(false);
			$printer -> feed();

			/* Tax and total */
			$printer -> text($tax);
            //$printer -> setEmphasis(false);
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text($total);
			$printer -> selectPrintMode();

            
            if($lis_tv[0]->id_tp == 1) { //Pago Efectivo
                $printer -> text($tipo_pago);
                $printer -> selectPrintMode();
                $printer -> text($vuelto);
                $printer -> selectPrintMode();
            } else {
                $printer -> text($tipo_pago);
                $printer -> selectPrintMode();
            }
            
            $printer -> selectPrintMode();
            
            $ob_nal = new NumeroALetras();
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("\n-----------------------------------------------\n");
            $printer -> text('SON: '.$ob_nal->convertir(floor($lis_tv[0]->total_venta)).' CON '.substr($lis_tv[0]->total_venta, -2).'/100 SOLES'."\n");
            $printer -> text("-----------------------------------------------\n");
            $printer -> selectPrintMode();
            
			$fact_bol=$lis_tv[0]->tdoc_r;
            // EN EL CASO SEA CLIENTE FACTURA
            if($fact_bol == '01')
            {                
                $lis_cliente = $this->tpv_model->verClienteVenta($lis_tv[0]->id_cliente);
                $printer -> text("\n");
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setEmphasis(true);
                $printer -> text('RUC: '.$lis_cliente[0]->nro_doc."\n");
                $printer -> text('RS: '.$lis_cliente[0]->razon_social."\n");
                $printer -> setEmphasis(false);
                $printer -> selectPrintMode();
            }
            // --

			// EN EL CASO SEA CLIENTE BOLETA
            if($fact_bol == '03')
            {                
                $lis_cliente = $this->tpv_model->verClienteVenta($lis_tv[0]->id_cliente);
                $printer -> text("\n");
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> setEmphasis(true);
                $printer -> text('DNI: '.$lis_cliente[0]->nro_doc."\n");
                $printer -> text('CLIENTE: '.$lis_cliente[0]->razon_social."\n");
                $printer -> setEmphasis(false);
                $printer -> selectPrintMode();
            }
            // --
			
			// QR
			$this->titleQR($printer, "\n");
			$testStr = $valor_QR;

			// Change size
			$this->titleQR($printer, "");
			$sizes = array(
			    7 => "");
			foreach ($sizes as $size => $label) {
				$printer -> setJustification(Printer::JUSTIFY_CENTER);
			    $printer -> qrCode($testStr, Printer::QR_ECLEVEL_L, $size);
			    //$printer -> text("Pixel size $size $label\n");
			    $printer -> feed();
			}

			/* Footer */
			$printer -> feed(2);
			$printer -> setJustification(Printer::JUSTIFY_CENTER);			
			$printer -> text($this->g_firma_ticket."\n");
            $printer -> text("Representacion impresa del documento de venta \n Electronica, puede consultar el documento\n ingresando a: \n http://www.elgrancharlee.com/facturador/\n");			
			$printer -> feed(2);
			$printer -> text($date . "\n");

			/* Cut the receipt and open the cash drawer */
			$printer -> cut();
			$printer -> pulse();

			$printer -> close();
		} 
		catch (Exception $e) 
		{
		    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
    }

	public function obtenerTotalesVentaTK($name = '', $price = '', $moneda = false)
	{
        $rightCols = 10;
        $leftCols = 38;
        if ($moneda) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($name, $leftCols) ;
        
        $sign = ($moneda ? 'S/ ' : '');
        $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }

	

	public function obtenerDetalleVentaTK($name = '', $cant = '', $price = '', $total = '')
	{
	    $lentTotal = 7;
		$lenPrecio = 7;
		$lenCant = 3;
        $lenName = 31;
        
        if(strlen($name) >= 28)
            $name = substr($name, 0, 28);
        		
        $leftName = str_pad($name, $lenName) ;        
        $rightCant = str_pad($cant, $lenCant, ' ', STR_PAD_LEFT);
		$rightPrecio = str_pad($price, $lenPrecio, ' ', STR_PAD_LEFT);
		$rightTotal = str_pad($total, $lentTotal, ' ', STR_PAD_LEFT);
        return "$leftName$rightCant$rightPrecio$rightTotal\n";
	}
	public function titleQR(Printer $printer, $str)
	{
	    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
	    $printer -> text($str);
	    $printer -> selectPrintMode();
	}
}
