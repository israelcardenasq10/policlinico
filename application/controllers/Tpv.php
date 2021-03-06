<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");


use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Tpv extends Secure_area {

	private $cod_categoria;
	private $g_ruta_printer;
	private $g_ruta_printer_cocina;
	private $g_ruta_printer_barra;
	private $g_espacio_print;

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
		
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$this->cod_categoria = '1001';
		$this->g_espacio_print = "    "; 

		$this->g_ruta_printer_simple ='TH230'; 
		$this->g_ruta_printer_simple_precuenta = 'TH230'; 
		$this->g_ruta_printer_cocina = 'TH230';  
		$this->g_ruta_printer_barra = 'TH230'; 

		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['lis_productos'] = $this->tpv_model->filtrarProductos($this->cod_categoria);
		$this->load->view('punto_venta/pos', $data);

	}

	public function verCategorias()
	{
		$data['lis_categorias'] = $this->tpv_model->listarCategorias();
		$data['v_ajax'] = 'tab_categorias';
		$this->load->view("punto_venta/ajax", $data);
	}

	public function filtrarProductos()
	{
		$lis_productos = $this->tpv_model->filtrarProductos($this->input->post('id_categoria'));

    	if($this->g_pv_prod_images === '1')
			$style_prod = 'padding: 10px 0px; height: 40px;';
		else
			$style_prod = '';

            foreach($lis_productos as $key => $lis):
				$lis_insu = $this->tpv_model->verificarStockInsumoProd($lis->id_producto);

				if($lis_insu):
					foreach($lis_insu as $l_i){
						if($l_i->stock_porcion <= 0){
							$venta_prod = 'NO';
							break;
						}else{
							$venta_prod = '';
						}
					}
				else:
					$lis_insu = NULL;
				endif;

				// if(@$venta_prod == 'NO' || $lis_insu === NULL){
				// 	@$disabled_prod = 'pointer-events: none; color: rgba(0,0,0,0.1);';
				// 	@$texto_prod_disab = 'text-decoration: line-through; font-weight: bold;';
				// }else{
					@$disabled_prod = '';
					@$texto_prod_disab = '';
				// }
			
			if($this->g_pv_prod_images === '1'):
				if($lis->imagen) $imagen = $lis->imagen;
				else 				
				echo '
					<div class="col-xs-4">
						<div class="panel text-center" style="padding: 10px; height: 140px; cursor: pointer; box-shadow: 1px 1px 1px 1px gray; color: blue;  '.$disabled_prod.'" onclick="agregarProducto('.$lis->id_categoria.', '.$lis->id_producto.');">
							<div class="pull-left" style="color: white; font-weight: bold; font-size: 30px; border-bottom: solid 3px silver; position: absolute; background-color: black; padding: 0px 10px 0px 10px;">'.$lis->nro_producto.'</div>
							<div class="panel-body">
								<img class="img-responsive" src="'.base_url().'public/images/productos/no_disponible.jpg"/>
							</div>
						</div>
					</div>';
			else: 
				echo '
				<div class="col-xs-4">					
					<div class="panel text-center" style="padding: 10px; height: 80px; cursor: pointer; box-shadow: 1px 1px 1px 1px gray; color: blue;  '.$disabled_prod.'" onclick="agregarProducto('.$lis->id_categoria.', '.$lis->id_producto.');">
						<div class="row">	
							<div class="col-md-3 pull-left" style="color: black; font-weight: bold; font-size: 30px; position: relative;  padding: 0px 10px 0px 10px; margin: 0;">'.$lis->nro_producto.'</div>
							<div class="col-md-9" style="'.$style_prod.' '.$texto_prod_disab.'">S/'.$lis->precio_venta.'|'.$lis->nombre.'</div>
						</div>
					</div>
				</div>';
			endif;
		endforeach;
	}

	public function agregarProducto()
	{
		$id_categoria = $this->input->post('id_categoria');
		$id_producto = $this->input->post('id_producto');
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$cant_calculador_prod = $this->input->post('cant_calculador_prod');

		$correlativo = $this->tpv_model->obtenerCorrelativoDetalle($id_tmp_cab);
		if($correlativo > 0)
			$correlativo = $correlativo + 1;
		else
			$correlativo = 1;
			/*
			$lis_insu = $this->tpv_model->verificarStockInsumoProdAlmacen($id_producto);
			$cant_prod_ins = $this->tpv_model->verCantProdSelectTMP($id_producto); //($id_tmp_cab)

			// var_dump($lis_insu);
			$valor_ins = 0;
			$cant_tmp_prod_ins = 0;
			$venta_procede ='SI';
			foreach($lis_insu as $l_i)
			{
				if($l_i->stock_porcion <= $l_i->stock_min){
					$venta_prod = 'NO';
					if($l_i->valor == 'UND')
						$valor_porcion = ($l_i->valor_porcion * $cant_prod_ins);
					else
						$valor_porcion = ($l_i->valor_porcion * ($cant_prod_ins + 1));

					$cant_tmp_prod_ins = ($l_i->stock_porcion - $valor_porcion);
						if($l_i->stock_porcion < $valor_porcion)
							$venta_procede = 'NO';

					$valor_ins = $l_i->valor;
					$stock_porcion = $l_i->stock_porcion;
					break;
				}elseif($cant_prod_ins == $l_i->stock_porcion){
					$venta_prod = 'NO';
					$cant_tmp_prod_ins = ($l_i->stock_porcion - $cant_prod_ins);
					break;
				}elseif(($cant_calculador_prod + $cant_prod_ins) > $l_i->stock_porcion){
					$cant_tmp_prod_ins = ($l_i->stock_porcion - $cant_prod_ins);
					$valor_ins = $l_i->valor;
					break;
				}else{
					$venta_prod = '';
					$valor_ins = '';
				}
			}

			if(@$venta_procede == 'NO')
			{
				$array = array(
							array(
								'valida' => 'STOCK_0',
								'mensaje' => 'STOCK disponible del Producto = 0'
							)
						);
				print json_encode($array);
				return; // Aqui corta el c??digo.
			}

			if(@$venta_prod == 'NO' && $cant_tmp_prod_ins <= 0)
			{
				$array = array(
								array(
									'valida' => 'STOCK_0',
									'mensaje' => 'STOCK disponible del Producto = 0'
								)
							);
				print json_encode($array);
				return; // Aqui corta el c??digo.
			}

			if($valor_ins == 'UND' && $cant_tmp_prod_ins < $cant_calculador_prod)
			{
				$array = array(
									array(
										'valida' => 'STOCK_CALCU',
										'mensaje' => 'Cantidad ingresada es mayor al STOCK del producto'
									)
								);
				print json_encode($array);
				return; // Aqui corta el c??digo.
			}

			if($valor_ins == 'UND')
			{
				$valida = 'STOCK_MIN';
				$mensaje = 'STOCK disponible del Producto = '.$cant_tmp_prod_ins;
			}
			elseif(@$venta_prod == 'NO')
			{
				$valida = 'STOCK_MIN';
				$mensaje = 'El Producto se encuentra en STOCK MINIMO!';
			}
			else
			{*/
				$valida = '';
				$mensaje = '';
			// }
			
		// Cierra validaciones.
		$person_id = $this->session->userdata('person_id');
		$des_prod = $this->tpv_model->obtenerDesProducto($id_producto);
		$fecha  = mdate("%Y-%m-%dT%H:%i:%s");
		foreach ($des_prod as $key => $value)
		{
			$data_insert = array(
								'id_tmp_cab' => $id_tmp_cab,
								'id_producto' => $value->id_producto,
								'id_categoria' => $value->id_categoria,
								'correlativo' => $correlativo,
								'nombre' => $value->nombre,
								'imagen' => '',
								'categoria' => $value->categoria,
								'cantidad' => $cant_calculador_prod, // default = 1
								'venta' => $value->precio_venta,
								'persona_id_created'=> $person_id,
								'date_created' =>$fecha,
								'print_comanda' => 0 ,
								'dividir_cuenta'=> '' ,
								'nota_comanda'=> '' ,
								'transac_venta' => 0 ,
								'unidad' => $value->unidades,
								'isDelete'=>0,
							);
			$this->tpv_model->insertarTMPPuntoVenta($data_insert);
		}

		// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		//$precio_total = ($cant_calculador_prod * $des_prod[0]->precio_venta);
		//$precio_total_venta = $precio_total;
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		$aux_bolsa = 0;
		foreach ($lis_tmp_pventa as $lis)
		{
			if ($lis->dividir_cuenta < 2)
			{
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$aux_bolsa = 1;
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
					$aux_bolsa = 0;
				}
				$precio_total_venta += $precio_total;
			}
		}
		// --
		if($aux_bolsa == 1)
			$descrip_prod = $des_prod[0]->nombre. ' + ICBPER';
		else
			$descrip_prod = $des_prod[0]->nombre;

		$array = array(
						array(
							'valida' => $valida,
							'mensaje' => $mensaje,
							'id_tmp_cab' => $id_tmp_cab,
							'correlativo' => $correlativo,
							'nombre' => $descrip_prod,
							'cantidad' => $cant_calculador_prod,
							'precio_unitario' => $des_prod[0]->precio_venta,
							'precio_total' => number_format($precio_total, 2),
							'precio_total_venta' => number_format($precio_total_venta, 2),
							'comentario' => ""
						)
					);
		print json_encode($array);
	}

	public function agregarNotaProd()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_nota_comanda = $this->input->post('id_nota_comanda');

		$correlativo = $this->tpv_model->obtenerCorrelativoDetaTMP($id_tmp_cab);
		$print = $this->tpv_model->verPrint($id_tmp_cab,$correlativo);
		if($print==0){
		$des_nc = $this->tpv_model->obtenerDatosNotaComanda($id_nota_comanda);
		$des_tmp = $this->tpv_model->verTMPPuntoVenta($id_tmp_cab, $correlativo);

		foreach ($des_tmp as $key => $value)
		{
			// ejemplo = 1:SIN SAL|2:CALIENTE
			if(trim($value->nota_comanda) == '')
				$nota_comanda = $des_nc[0]->id.':'.$des_nc[0]->nota;
			else
				$nota_comanda = $value->nota_comanda.'|'.$des_nc[0]->id.':'.$des_nc[0]->nota;
			
			$data = array('nota_comanda' => $nota_comanda);
			$this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $correlativo, $data);
		}

		$array = array(
				array(
					'id_tmp_cab' => $id_tmp_cab,
					'correlativo' => $correlativo,
					'id_nota_comanda' => $id_nota_comanda,
					'nombre' => $des_nc[0]->nota
				)
			);

		}else{
		$array = array(
			array(
				'id_tmp_cab' => -1,
			)
			);
		}
		print json_encode($array);
	}

	public function agregarCampoNotaProd()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_nota_comanda = $this->input->post('id_nota_comanda');
		$nota_comanda_text = $this->input->post('nota_comanda');
		$correlativo = $this->tpv_model->obtenerCorrelativoDetaTMP($id_tmp_cab);
		$print = $this->tpv_model->verPrint($id_tmp_cab,$correlativo);
		if($print==0){
		$data_nc = array(
						'nota' => strtoupper($nota_comanda_text),
						'estado'=>1,
						'date_created' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
						'persona_id_created' => $this->session->userdata('person_id')
					);
		$id_nota_comanda = $this->tpv_model->insertarNotaComandaProd($data_nc);
		$des_tmp = $this->tpv_model->verTMPPuntoVenta($id_tmp_cab, $correlativo);
		foreach ($des_tmp as $key => $value)
		{
			// ejemplo = 1:SIN SAL|2:CALIENTE
			if(trim($value->nota_comanda) == '')
				$nota_comanda = $id_nota_comanda.':'.strtoupper($nota_comanda_text);
			else
				$nota_comanda = $value->nota_comanda.'|'.$id_nota_comanda.':'.strtoupper($nota_comanda_text);

			$data = array('nota_comanda' => $nota_comanda);
			$this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $correlativo, $data);
		}
		$array = array(
					array(
						'id_tmp_cab' => $id_tmp_cab,
						'correlativo' => $correlativo,
						'id_nota_comanda' => $id_nota_comanda,
						'nombre' => strtoupper($nota_comanda_text)
					)
				);
		}else{
			$array = array(
					array(
						'id_tmp_cab' => -1,
					)
				);
		}
		print json_encode($array);
	}

	// Proceso de Comanda (Cocina, Barra)
	public function generarComanda()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');

		$lis_tv = $this->tpv_model->listarTMPPuntoVentaCAB($id_tmp_cab);
		$lis_tv_deta = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$lis_user_venta = $this->tpv_model->verUsuarioVenta($id_tmp_cab);
		$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
		$moneda_ticket = substr($this->g_moneda, 0, 2); // Soles
		$dato_mesa = $this->tpv_model->listarMesas($lis_tv[0]->id_mesa);
		$correlativo = $lis_tv[0]->correlativo;
		$print_comanda = false;
		$array_prod_coman = array();
		$array_prod_coman_barra = array();
		$cuerpo_tck_cn = '';
		foreach ($lis_tv_deta as $lis)
		{
			if($lis->print_comanda == 0) // IF verificaci??n de "print_comanda"
			{
				$len_prod = strlen($lis->nombre);
				$len_cant = strlen($lis->cantidad);

				// PRODUCTO
				if($len_prod <= 11)
					$text_producto = $lis->nombre."\t  ";
				elseif($len_prod >= 11 && $len_prod <= 19)
					$text_producto = $lis->nombre."  ";
				else
					$text_producto = substr($lis->nombre, 0, 20)."  ";

				// CANTIDAD
				if(@$len_cant == 1)
					$text_cant = " ".$lis->cantidad;
				else
					$text_cant = $lis->cantidad;

				$lista_prod = $this->productos_model->buscarProducto($lis->id_producto);
				$nro_plato = substr($lista_prod[0]->nro_producto, 0, 2); //'01'
				if($lis->id_categoria == $this->cod_categoria) //ES MENU
				{
					$cuerpo_tck_c = $text_cant." # ".$nro_plato." ";
				}
				else
				{
					$cuerpo_tck_c = $text_cant." # ".$nro_plato." ".$text_producto;
				}

				if($lista_prod[0]->producto_comanda_id == 1) // Cocina
					array_push($array_prod_coman, $cuerpo_tck_c);
				else // Barra
					array_push($array_prod_coman_barra, $cuerpo_tck_c);


				// Actualiza el campo "Print_comanda" para saber que ya se mando a comanda el producto
				$des_tmp = $this->tpv_model->verTMPPuntoVenta($lis->id_tmp_cab, $lis->correlativo);
				if($des_tmp != NULL)
				{
					foreach ($des_tmp as $key => $value)
					{
						if(trim($value->nota_comanda) != '')
						{
							$nota_comanda = '';
							$cuerpo_tck_cn = '';
							$c_n_c = '';
							$data_c = explode("|", trim($value->nota_comanda)); // array(1:SIN SAL, 2:CALIENTE)
							foreach ($data_c as $k => $val)
							{
								$notas_c = explode(':', $val);

								if($k>0) $c_n_c = ', ';
								$nota_comanda .= $c_n_c.$notas_c[1];
							}

							$cuerpo_tck_cn .= " - (".$nota_comanda.")";

							if($lista_prod[0]->producto_comanda_id == 1)
								array_push($array_prod_coman, $cuerpo_tck_cn);
							else
								array_push($array_prod_coman_barra, $cuerpo_tck_cn);
						}
					}
				}
				$data = array(
							'print_comanda' => 1,
							'date_updated' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
							'persona_id_updated' => $this->session->userdata('person_id')
						);
				$this->tpv_model->actualizarTMPPuntoVenta($lis->id_tmp_cab, $lis->correlativo, $data);
				$print_comanda = true;
			}
		}

		if($print_comanda == true)
		{
			if(count($array_prod_coman) > 0)
				$this->printerTCKComanda($array_prod_coman, $this->g_ruta_printer_cocina, $dato_mesa, $usuario_venta , $correlativo);
			
			// Activar el d??a que usen ticket BARRA
			if(count($array_prod_coman_barra) > 0)
				$this->printerTCKComanda($array_prod_coman_barra, $this->g_ruta_printer_barra, $dato_mesa, $usuario_venta , $correlativo);
			
		}
	}

	public function printerTCKComanda($array_prod_coman, $ruta_printer, $dato_mesa, $usuario_venta , $correlativo)
	{
		$cuerpo = "------------------------\n";
		$cuerpo .= '   '.strtoupper($dato_mesa[0]->mesa)." | ".$usuario_venta[0]."\n";
		$cuerpo .= "------------------------\n";

		foreach ($array_prod_coman as $lis) {
			$cuerpo.= $lis."\n";
		}
		$cuerpo .= "------------------------\n";
		$cuerpo .= "    Por favor atender   \n";

		try {
			$connector = new WindowsPrintConnector($ruta_printer);
			$printer = new Printer($connector);
			$printer -> setFont(Printer::FONT_B);
			$printer -> setTextSize(2, 1);
			$printer -> text('Tk#:'.$correlativo.' COMANDA '.mdate("%d/%m", time()).' '.mdate("%H:%i", time())."\n");
			$printer -> setFont(Printer::FONT_A);
			$printer -> setTextSize(2, 1);
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer -> text(utf8_decode($cuerpo) . "\n");
			$printer -> cut();
			$printer -> pulse();
			$printer -> close();
		}	catch (Exception $e) 
		{
			echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
	}
	
	public function getCabeceraImpresionTck()
	{
		$cabecera_tck = " \n";
		$cabecera_tck .= "        ".$this->g_nombre_corto."\n";
		$cabecera_tck .= "        ".$this->g_razon_social."\n";
		$cabecera_tck .= "          RUC ".$this->g_ruc."\n";
		$cabecera_tck .= "     ".$this->g_direccion."\n";
		return $cabecera_tck;
	}

	public function generarPreVenta()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$total_venta = $this->input->post('total_venta');

		$fecha_registro = mdate("%Y-%m-%d", time());
		$hora_fin = mdate("%H:%i:%s", time());

		// Proceso "Imprimir el TICKET"
			$lis_tv = $this->tpv_model->listarTMPPuntoVentaCAB($id_tmp_cab);
			$lis_tv_deta = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
			$lis_user_venta = $this->tpv_model->verUsuarioVenta($id_tmp_cab);
			$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "SOLES"
			$dato_mesa = $this->tpv_model->listarMesas($lis_tv[0]->id_mesa);

			// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
			$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
			$icbper_tax = $lis_icbper[0]->monto;
			$cuerpo_tck = "                ".$this->g_nombre_corto."\n";
			// $cuerpo_tck .= "          RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "        ".$this->g_direccion."\n";
			
			$cuerpo_tck .= "                ".strtoupper($dato_mesa[0]->mesa)."\n";
			$cuerpo_tck .= " Tck#: ".$lis_tv[0]->correlativo."-".$lis_tv[0]->estado." PRE CTA   ".mdate("%d/%m/%y", time()).' '.$hora_fin."\n";
			$cuerpo_tck .= "----------------------------------------\n";
			$cuerpo_tck .= "   Descripcion    ".$this->g_espacio_print."Cant P.Unit P.Total\n";
			$cuerpo_tck .= "----------------------------------------\n";

			$precio_total = $precio_total_venta = 0;
			$aux_cant_icbper = 0;

			foreach ($lis_tv_deta as $lis)
			{
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$aux_cant_icbper = $lis->cantidad;
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				// PRODUCTO
				$text_producto = str_pad(substr($lis->nombre, 0, 18), 20 ," ", STR_PAD_RIGHT);
				// CANTIDAD
				$text_cant = str_pad($lis->cantidad, 2 ," ", STR_PAD_LEFT);
				// VENTA
				$text_venta = str_pad($lis->venta, 8 ," ", STR_PAD_LEFT);
				// TOTAL
				$text_total = str_pad(number_format($precio_total, 2), 7 ," ", STR_PAD_LEFT);
				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";

				$precio_total_venta += $precio_total;
			}
			$cuerpo_tck .= "--------------------------------------------\n";
		
			// Formula correcta de Calculos:
			$mas_igv = ((100 + $this->g_igv) / 100); 
			// RAG : Cambio por las bolsas ICBPER
			$st_venta_aux = ($total_venta - ($aux_cant_icbper * $icbper_tax));
			$subtotal_venta = ($st_venta_aux /  $mas_igv);
			$total_igv = ($st_venta_aux - $subtotal_venta);

			// TOTAL NETO
			$total_neto = str_pad(number_format($subtotal_venta, 2), 9 ," ", STR_PAD_LEFT);			
			// TOTAL VENTA
			$total_venta = str_pad(number_format($precio_total_venta, 2), 9 ," ", STR_PAD_LEFT);


			//$cuerpo_tck .= "\t     Total Neto   ".$moneda_ticket.$total_neto."\n"; //$lis_tv[0]->subtotal_venta
			//$cuerpo_tck .= "\t            IGV   ".$moneda_ticket.$igv."\n";
			$cuerpo_tck .= "          TOTAL   ".$moneda_ticket.$total_venta."\n";
			$cuerpo_tck .= "--------------------------------------------\n";
			$cuerpo_tck .= "             Atendido por: ".strtoupper($usuario_venta[0])."\n";
			$cuerpo_tck .= "   ".$this->g_firma_ticket."\n";
			
			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			// echo ($nv_cuerpo_tck);
			if($this->session->userdata('id_perfil') == 5) //BARISTA
			 	$this->printerTCKNegrita($nv_cuerpo_tck, $this->g_ruta_printer_simple_precuenta);
			else
				$this->printerTCKNegrita($nv_cuerpo_tck, $this->g_ruta_printer_simple);

	}
		
	public function printerTCKNegrita($nv_cuerpo_tck, $ruta_printer)
	{
		// echo $nv_cuerpo_tck;
		try{
			$connector = new WindowsPrintConnector($ruta_printer);
			$printer = new Printer($connector);
			$printer -> text(utf8_decode($nv_cuerpo_tck));
			$printer -> cut();
			$printer -> pulse();
	
			$printer -> close();
		}catch (Exception $e) 
		{
		    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
	
	}

	public function generarVenta()
	{
		header('Content-type: application/json; charset=utf-8');
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_serie = $this->input->post('doc_pago');
		$tipo_pago = $this->input->post('tipo_pago');
		$tipo_pago_dif = $this->input->post('tipo_pago_dif');
		$total_venta_f = $this->input->post('total_venta');
		$pago_cliente = $this->input->post('pago_cliente');
		$vuelto_cliente = $this->input->post('vuelto_cliente');
		$tp_nruc = $this->input->post('tipo_doc');
		$nruc = trim($this->input->post('nruc'));
		$rsoc = strtoupper (trim($this->input->post('rsoc')));
		$fecha_registro = mdate("%Y-%m-%d", time());
		$hora_fin = mdate("%H:%i:%s", time());
		$cliente_venta = $this->input->post('id_cliente');

		if ($cliente_venta) {
			$id_cliente = $cliente_venta;
			$cliente_activo = 'ok';
		}
		else
			$id_cliente = 0;

		/***********************************************************
		 * validacion backend
		 ***********************************************************/
		if($pago_cliente==0) {
			echo json_encode ( array('error'=>"El pago del Cliente no puede ser 0"));
			return;
		}elseif ($id_serie == 1 && ($tp_nruc != 'RUC' || strlen($nruc) != 11) ) { //factura
			echo json_encode ( array('error'=>"Para generar la Factura debe Ingresar un documento tipo RUC!"));
            return;
		}elseif ($id_serie == 1 && $id_cliente==0 ) { //factura con id 0
			echo json_encode ( array('error'=>"Para generar la Factura debe Seleccionar un RUC valido."));
            return;
		} 
		/*elseif ($id_serie == 2 && ($tp_nruc != 'DNI' || strlen($nruc)  != 8)) { //boleta
			echo json_encode ( array('error'=>"Solo puede Generar una Boleta con DNI VALIDO!"));
            return;
        } */ 
		elseif($total_venta_f > $pago_cliente) {
			echo json_encode ( array('error'=>"El pago del Cliente no puede ser Menor al Total"));
			return;
		}elseif($total_venta_f == 0) {
			echo json_encode ( array('error'=>"El total de la venta no puede ser 0"));
			return;
		}elseif($pago_cliente !=($total_venta_f + $vuelto_cliente) ) {
			echo json_encode ( array('error'=>"No Coincide el Pago con la suma de la venta mas el Vuelto"));
			return;
		}else{
			echo json_encode ( array('exito'=>"ok"));
		}
		/***********************************************************
		 * CREACION DE TIPO, SERIE Y NUMERO
		 ***********************************************************/
		$lis_serie_doc = $this->series_documentos_model->ver($id_serie);
		$tipo_doc = trim($lis_serie_doc[0]->tipo_doc);
		$tdoc = trim($lis_serie_doc[0]->tdoc);
		$sfactu = trim($lis_serie_doc[0]->serie);	

		$cod_max = $this->tpv_model->generarCodMax($sfactu );       
		$num = ($cod_max*1)  + 1;							
		$nfactu=str_pad($num, 8 ,"0", STR_PAD_LEFT);
		$num_doc = $tdoc.'-'.$sfactu.'-'.$nfactu;
		/***********************************************************
		 * FIN CREACION DE TIPO SERIE Y NUMERO
		 ***********************************************************/
		
		// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		// Calcula el total venta seleccionado por producto (Dividir Cuenta)
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		$condicion = '';
		$icbper_total = 0.00;
		$icbper_cant = 0;
		$aux_cant_icbper = 0;

		$aux_cant_icbper = 0;
		foreach($lis_tmp_pventa as $lis)
		{
			if ($lis->dividir_cuenta == 1) {
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$aux_cant_icbper = $lis->cantidad;

					$icbper_total += round($lis->cantidad * $icbper_tax, 2);
					$icbper_cant += $lis->cantidad;

				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$precio_total_venta += $precio_total;

				$condicion = 'd_c';

				// $data = array('dividir_cuenta' => 2);
				// $this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $lis->correlativo, $data);
			}
		}

		if ($condicion == '') {
			foreach($lis_tmp_pventa as $lis)
			{
				if ($lis->dividir_cuenta == 0) { //Venta normal
					if($lis->categoria == 'BOLSAS') {
						$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
						$aux_cant_icbper = $lis->cantidad;

						$icbper_total += round($lis->cantidad * $icbper_tax, 2);
						$icbper_cant += $lis->cantidad;

					} else {
						$precio_total = ($lis->cantidad * $lis->venta);
					}
					$precio_total_venta += $precio_total;
				}
			}
		}

		$total_venta = 0;
		if ($condicion == 'd_c') {
			$total_venta = $precio_total_venta;
		} else {
			$total_venta = $precio_total_venta;
		}
		// --

		//Es cortes??a
		if($id_serie == 7)
			$total_venta = 0;

		// Formula correcta de Calculos:
		$mas_igv = ((100 + $this->g_igv) / 100); // Obtiene Ejm: 1.18

		// RAG : Cambio por las bolsas ICBPER
		$st_venta_aux = ($total_venta - ($aux_cant_icbper * $icbper_tax));
		$subtotal_venta = ($st_venta_aux /  $mas_igv);
		$total_igv = ($st_venta_aux - $subtotal_venta);

		$des_tmppventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		// Calcula los costos del Producto
		$costo = 0;
		$costo_prod_p = 0;
		$condicion_c = '';
		foreach ($des_tmppventa as $value)
		{
			if ($value->dividir_cuenta == 1) {
				$lista = $this->productos_model->buscarProducto($value->id_producto);
				$costo = ($lista[0]->precio_insumo * $value->cantidad);
				$costo_prod_p += $costo;
				$condicion_c = 'd_c';
			}
		}

		if ($condicion_c == '') {
			foreach ($des_tmppventa as $value)
			{
				if ($value->dividir_cuenta == 0) {
					$lista = $this->productos_model->buscarProducto($value->id_producto);
					$costo = ($lista[0]->precio_insumo * $value->cantidad);
					$costo_prod_p += $costo;
				}
			}
		}

		$costo_prod = 0;
		if ($condicion_c == 'd_c') {
			$costo_prod = $costo_prod_p;
		} else {
			$costo_prod = $costo_prod_p;
		}
		// --
		// if($total_venta_f != $total_venta && $id_serie != 7) {
		// 	echo json_encode ( array('error'=>"No Coincide el El total con Total cobrado"));
		// 	return;
		// }
		$date_created =mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		
		$tipo_doc_sunat = $this->tpv_model->obtdatoCliente($tp_nruc);

		$data = array(
			'num_doc' => $tdoc.'-'.$sfactu.'-'.$nfactu,
			'subtotal_venta' => $subtotal_venta,
			'igv' => $total_igv,
			'costo' => $costo_prod,						
			'desc_venta' => 0, // Guarda el id_tarjeta del Pago Diferido / Mixto.
				//'otros_cargos' => $otros_cargos, // Nuevo ICBPER bolsas
			'total_venta' => $total_venta,
			'pago_cliente' => $pago_cliente,
			'vuelto' => $vuelto_cliente,
			'tc' => $this->g_tc,
			'moneda' => $this->g_moneda,
			'id_cliente' => $id_cliente,
			'id_tp' => $tipo_pago,
			'id_serie' => $id_serie,
			'id_tmp_cab' => $id_tmp_cab,
			'estado' => 'D',
			'icbper_total' => $icbper_total,
			'icbper_cant' => $icbper_cant,
			'fecha_registro' => $fecha_registro,
			'id_owner' => $this->session->userdata('person_id'),
			'date_created' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
			'persona_id_created' => $this->session->userdata('person_id'),
			'tdoc'  => $tdoc,
			'sfactu'=> $sfactu,
			'nfactu'=> $nfactu,
			'n_ruc'=>$nruc ,
			'n_rs'=>$rsoc ,
			'tp_ruc'=>$tp_nruc,
			'anulado'=>'NO',
			'tipo_doc_sunat'=>$tipo_doc_sunat,
		);
		$id_transac = $this->tpv_model->insertarTransacVenta($data);

		$mpago = $this->input->post('mpago');
		foreach($mpago as $fila){
			$data = array(
				'id_transac' => $id_transac,
				'id_tp' => $fila['id_tp'],
                'tipo_pago' => $fila['tipo_pago'] ,
                'monto' => $fila['monto'] ,
			);
			$this->tpv_model->insertarTransacVentaMedioPago($data);
		}
		
		$condicion_venta = '';
		// Venta Dividir Cuenta
		$total = 0;
		foreach ($des_tmppventa as $value)
		{
			if ($value->dividir_cuenta == 1 && $value->transac_venta == 0) {
				$total = ($value->cantidad * $value->venta);
				$data = array(
					'id_transac' => $id_transac,
					'id_producto' => $value->id_producto,
					'id_categoria' => $value->id_categoria,
					'correlativo' => $value->correlativo,
					'producto' => $value->nombre,
					'categoria' => $value->categoria,
					'cantidad' => $value->cantidad,
					'venta' => $value->venta,
					'total' => $total
				);
				$this->tpv_model->insertarTransacVentaDetalle($data);
				$condicion_venta = 'd_c';

				$data = array('transac_venta' => 1 ,'dividir_cuenta' => 2);
				$this->tpv_model->actualizarTMPPuntoVenta($value->id_tmp_cab, $value->correlativo, $data);

			}
		}
		// Sin Dividir Cuenta - Venta Normal
		if ($condicion_venta == '') {
			foreach ($des_tmppventa as $value)
			{
				if ($value->dividir_cuenta == 0 && $value->transac_venta == 0) {
					$total = ($value->cantidad * $value->venta);
					$data = array(
										'id_transac' => $id_transac,
										'id_producto' => $value->id_producto,
										'id_categoria' => $value->id_categoria,
										'correlativo' => $value->correlativo,
										'producto' => $value->nombre,
										'categoria' => $value->categoria,
										'cantidad' => $value->cantidad,
										'venta' => $value->venta,
										'unidad' => $value->unidad,
										'total' => $total
									);
					$this->tpv_model->insertarTransacVentaDetalle($data);

					$data = array('transac_venta' => 1);
					$this->tpv_model->actualizarTMPPuntoVenta($value->id_tmp_cab, $value->correlativo, $data);
				}
			}
		} 
		// ************** PROCESO SUNAT ************** //
		if ( substr($sfactu,0,1) == 'F' ) // FACTURA // BOLETA
		{
			$this->generartxtfactu($id_transac);
		}

		// PROCESO IMPRIMIR TICKET
			$lis_tv = $this->tpv_model->listarTransacVentaCAB($id_transac);
			$lis_tv_deta = $this->tpv_model->listarTransacVentaDetalle($id_transac);
			$lis_t_pago = $this->tpv_model->verTipoPago($tipo_pago);
			$lis_user_venta = $this->tpv_model->verUsuarioVenta($id_tmp_cab);
			$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "SOLES"
			
			$lis_tv_tmp = $this->tpv_model->listarTMPPuntoVentaCAB($id_tmp_cab);
			$dato_mesa = $this->tpv_model->listarMesas($lis_tv_tmp[0]->id_mesa);
		
		if ($id_serie == 1 || $id_serie == 2) // FACTURA / BOLETA - ELECTRONICA
		{                      
			$this->generarFacBolElectronica($id_transac) ;
		}
		else 			    // RECIBO
		{
			$cuerpo_tck = $this->getCabeceraImpresionTck();

			$cuerpo_tck .= "                ".strtoupper($dato_mesa[0]->mesa)."\n";
			$cuerpo_tck .= "Tk: Recibo         ".mdate("%d/%m/%y", time()).' '.$hora_fin."\n";
			
			$cuerpo_tck .= "====================================\n";
			
			if(@$cliente_activo == 'ok')
			{
				// $lis_cliente = $this->tpv_model->verClienteVenta($id_cliente);
				$cuerpo_tck .= "Nro Doc : ".$nruc."\n";
				$cuerpo_tck .= "Cliente : ".$rsoc."\n";
				$cuerpo_tck .= "====================================\n";
			}

			$cuerpo_tck .= "   Descripcion    CantP.Unit P.Total\n";
			$cuerpo_tck .= "====================================\n";
			
			foreach ($lis_tv_deta as $lis)
			{
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$aux_cant_icbper = $lis->cantidad;
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				// PRODUCTO
				$text_producto = str_pad(substr($lis->producto, 0, 16), 18 ," ", STR_PAD_RIGHT);
				// CANTIDAD
				$text_cant = str_pad($lis->cantidad, 2 ," ", STR_PAD_LEFT);
				// VENTA
				$text_venta = str_pad($lis->venta, 8 ," ", STR_PAD_LEFT);
				// TOTAL
				$text_total = str_pad(number_format($precio_total, 2), 7 ," ", STR_PAD_LEFT);
				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";

				$precio_total_venta += $precio_total;
			}
			$cuerpo_tck .= "====================================\n";

			// TOTAL NETO
			$total_neto = str_pad($lis_tv[0]->subtotal_venta, 8 ," ", STR_PAD_LEFT);
			// IGV
			$igv = str_pad($lis_tv[0]->igv, 8 ," ", STR_PAD_LEFT);
			// TOTAL VENTA
			$total_venta = str_pad($lis_tv[0]->total_venta, 8 ," ", STR_PAD_LEFT);
			// PAGO CLIENTE
			$pago_cliente = str_pad($lis_tv[0]->pago_cliente, 8 ," ", STR_PAD_LEFT); 
			// VUELTO
			$vuelto = str_pad($lis_tv[0]->vuelto, 8 ," ", STR_PAD_LEFT); 
			
			$text_tipo_pago =  str_pad($lis_t_pago[0]->tipo_pago, 25 ," ", STR_PAD_LEFT); 
			
			$cuerpo_tck .= "       TOTAL   ".$moneda_ticket.$total_venta."\n";

			
			$cuerpo_tck .= "====================================\n";
			if($lis_t_pago[0]->id_tp == 6) // Pago Diferido o Mixto
			{
				$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$total_venta."\n";
			}
			else
				$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$pago_cliente."\n";
		

			if($lis_t_pago[0]->id_tp == 1 && $id_serie <> 7) // 7 = Cortesia
				$cuerpo_tck .= "          Cambio   ".$moneda_ticket.$vuelto."\n";

			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= "   Cajero(a): ".strtoupper($usuario_venta[0])."\n";

			if($id_serie == 7)
				$cuerpo_tck .= "             CORTESIA               \n";

			$cuerpo_tck .= "     ".$this->g_firma_ticket."\n\n";
			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
            
            //Print
			$this->printerTCKNegrita($nv_cuerpo_tck, $this->g_ruta_printer_simple);
		}
		// Proceso "Cerrar mesas y venta Temporal"
		if ($condicion_venta == 'd_c') {
			$valor_pv_transac = $this->tpv_model->listarTMPPVTransacVentaDCuenta($id_tmp_cab, 0);
			if($valor_pv_transac > 0)
				$estado_venta = 'D';
			else
				$estado_venta = 'C';
		} else {
			$estado_venta = 'C';
		}

		$data = array(
						'hora_fin' => $hora_fin,
						'total_venta' => $total_venta,
						'estado' => $estado_venta
					);
		$this->tpv_model->actualizarTMPTpvCab($data, $id_tmp_cab);
	}

	public function cabecera(){
		$connector = new WindowsPrintConnector($this->g_ruta_printer_simple);
		$printer = new Printer($connector);
		$printer -> setJustification(Printer::JUSTIFY_CENTER);
		// $logo = EscposImage::load(APPPATH . "libraries/escpos/example/resources/logo_fs_3.jpeg", false);
		// $printer -> bitImage($logo);			
		$printer -> setFont(Printer::FONT_B);
		$printer -> setTextSize(2, 1);
		$printer -> text("Policl??nico\n");
		$printer -> setFont(Printer::FONT_B);
		$printer -> setTextSize(4, 3);
		$printer -> text("Carapongo\n");
		$printer -> feed();
		$printer -> setFont(Printer::FONT_B);
		$printer -> setTextSize(2, 1);
		$printer -> text("Medical Center\n");
		
		$printer -> feed(2);
		$printer -> selectPrintMode();		
		$printer -> text("-------------------------------------------\n");
		$printer -> text("Descripci??n              Cant.|P.Unit|Total\n");
		$printer -> text("-------------------------------------------\n");
		
		$printer -> cut();
		$printer -> pulse();

		$printer -> close();
	}

    public function generarFacBolElectronica($id_transac)
    {
        try
	    {	
			// $id_transac=213477;//03-B003-00003211
			$vuelto="";
			$lis_tv = $this->ventas_model->ver($id_transac);
			$lis_tv_deta = $this->ventas_model->verDetalleVenta($id_transac);
			// var_dump($lis_tv[0]);
			// echo $this->obtenerTotalesVentaTK('Cambio', $lis_tv[0]->vuelto);

			$subtotal = $this->obtenerTotalesVentaTK('Subtotal', $lis_tv[0]->subtotal_venta); //new item('Subtotal', $lis_tv[0]->subtotal_venta);
			$tax = $this->obtenerTotalesVentaTK('I.G.V', $lis_tv[0]->igv);
			$total = $this->obtenerTotalesVentaTK('Total', $lis_tv[0]->total_venta, true);
            $hora_fin = $lis_tv[0]->hora_fin;
			$vuel = $lis_tv[0]->vuelto ;
			$fech_reg=$lis_tv[0]->fecha_registro;
			$text_tipo_pago = str_pad($lis_tv[0]->tipo_pago, 25 ," ", STR_PAD_LEFT); 
			
            
            if(floatval($lis_tv[0]->vuelto) > 0) { //Pago Efectivo
                $tipo_pago = $this->obtenerTotalesVentaTK($text_tipo_pago, $lis_tv[0]->pago_cliente);
                $vuelto = $this->obtenerTotalesVentaTK('Cambio', $vuel);
			} else{
				$tipo_pago = $this->obtenerTotalesVentaTK($text_tipo_pago, $lis_tv[0]->pago_cliente);
			}
			
            
            $date = mdate("%d/%m/%y", strtotime($fech_reg)).' '.substr($hora_fin,0,5);
			
			
			$connector = new WindowsPrintConnector($this->g_ruta_printer_simple);
			$printer = new Printer($connector);
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			// $logo = EscposImage::load(APPPATH . "libraries/escpos/example/resources/logo_fs_3.jpeg", false);
			// $printer -> bitImage($logo);			
			$printer -> setFont(Printer::FONT_B);
			$printer -> setTextSize(2, 1);
			$printer -> text("Policl??nico\n");
			$printer -> setFont(Printer::FONT_B);
			$printer -> setTextSize(4, 3);
			$printer -> text("Carapongo\n");
			$printer -> feed();
			$printer -> setFont(Printer::FONT_B);
			$printer -> setTextSize(2, 1);
			$printer -> text("Medical Center\n");			
			$printer -> feed(2);
			// $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text($this->g_razon_social."\n");
			$printer -> selectPrintMode();
			$printer -> text($this->g_ruc."\n");
			$printer -> selectPrintMode();
			$printer -> text($this->g_direccion."\n");
			$printer -> feed();
            $f_b = $lis_tv[0]->sfactu;
			$printer -> setEmphasis(true);
			$printer -> text((substr($f_b,0,1) == 'F')? 'FACTURA DE VENTA ELECTRONICA': 'BOLETA DE VENTA ELECTRONICA');
		    $printer -> text("\n");
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text("NRO:".$f_b."-".$lis_tv[0]->nfactu."\n\n");
			$printer -> selectPrintMode();
			$printer -> setEmphasis(false);

			// $printer -> text("CAJA 01 / ".strtoupper($lis_tv[0]->mesa)." / "." Responsable: ".strtoupper($usuario_venta[0])."\n");
			$printer -> text("CAJA 01 / ".strtoupper($lis_tv[0]->mesa)." \n");
			$printer -> setJustification(Printer::JUSTIFY_LEFT );			
			$printer -> text("Fecha de Emisi??n :".mdate("%d/%m/%y", strtotime($lis_tv[0]->fecha_registro))."\n");
			$printer -> text("Fecha de Vencimiento : ".mdate("%d/%m/%y", strtotime($lis_tv[0]->fecha_registro))."\n");
			$printer -> text("Condici??n : Contado\n");

			$printer -> feed(1);
			$printer -> selectPrintMode();	
			$printer -> text("-------------------------------------------\n");
			$printer -> text("Descripci??n--------------cant|P.Uunit|total\n");
			$printer -> text("-------------------------------------------\n");
            // $col = EscposImage::load(APPPATH . "libraries/escpos/example/resources/campos.png", false);
			// $printer -> setEmphasis(false);

			// $printer -> setJustification(Printer::JUSTIFY_CENTER);
			// $printer -> bitImage($col);                
            

			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer -> setEmphasis(true);
			//$printer -> text(new item('', 'S/ '));
            $printer -> text("\n");
			$printer -> setEmphasis(false);
            
            // RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
			
			$lis_icbper = $this->tpv_model->verTaxAnioBolsa(substr($lis_tv[0]->fecha_registro,0,4));
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

			$printer -> text($tax);
            //$printer -> setEmphasis(false);
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer -> text($total);
			$printer -> selectPrintMode();

            
            if(floatval($lis_tv[0]->vuelto) > 0) { //Pago Efectivo
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
            $printer -> text("\n-------------------------------------------\n");
            $printer -> text('SON: '.$ob_nal->convertir(floor($lis_tv[0]->total_venta)).' CON '.substr($lis_tv[0]->total_venta, -2).'/100 SOLES'."\n");
            $printer -> text("-------------------------------------------\n");
            $printer -> selectPrintMode();
            
            if($lis_tv[0]->tp_ruc=="RUC"){$t_doc="RS  :";}else {$t_doc="CLIENTE:";}

			$printer -> text("\n");
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> setEmphasis(true);
			$printer -> text($lis_tv[0]->tp_ruc.': '.$lis_tv[0]->n_ruc."\n");
			$printer -> text($t_doc.$lis_tv[0]->n_rs."\n");
			$printer -> setEmphasis(false);
			$printer -> selectPrintMode();
            
			$valor_QR = $this->g_ruc.'|'.$lis_tv[0]->tdoc.'|'.$f_b .'|'.$lis_tv[0]->nfactu.
			'|'.$lis_tv[0]->igv.'|'.$lis_tv[0]->total_venta.
			'|'.$lis_tv[0]->fecha_registro.'|'.$lis_tv[0]->tipo_doc_sunat.'|'.$lis_tv[0]->n_ruc;
			
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> qrCode($valor_QR, Printer::QR_ECLEVEL_L, 7);
			//$printer -> text("Pixel size $size $label\n");
			$printer -> feed();

			$printer -> feed(2);
			$printer -> setJustification(Printer::JUSTIFY_CENTER);			
			$printer -> text($this->g_firma_ticket."\n");
            // $printer -> text("Representacion impresa del documento de venta \n Electronica, puede consultar el documento\n");			
            $printer -> text("EMITIDO MEDIANTE PROVEEDOR AUTORIZADO POR LA \n SUNAT RESOLUCION N.?? 097- 2012/SUNAT\n");			
			$printer -> feed(2);
			$printer -> text($date . "\n");

			$printer -> cut();
			$printer -> pulse();

			$printer -> close();
			
		} 
		catch (Exception $e) 
		{
		    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
		}
    }



	public function actualizarVenta($id_tmp_cab)
	{
		$des_tmppventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);

		foreach ($des_tmppventa as $value)
		{
			$lista = $this->productos_model->verInsumosXProd($value->id_producto);
			$cantidad_producto = $value->cantidad;

			foreach($lista as $i=>$lis)
			{
				// PROCESO DE CALCULO (Cantidad, Costo Unit., Stock Ins. y Costo Ins.) ALMACEN
				$lis_data_almacen = $this->almacen_model->verDatosAlmacenServ($lis->id_serv_prov);
				$cant_alm_actual = $lis_data_almacen[0]->cantidad;
				$stock_porcion_alm_actual = $lis_data_almacen[0]->stock_porcion;

				if($lis->valor == 'LTS' || $lis->valor == 'KLG' || $lis->valor == 'GRM' || $lis->valor == 'MLD')
				{
					$valor_porcion_alm_actual = ($lis->valor_porcion * $cantidad_producto);
					$stock_insumo = ($stock_porcion_alm_actual - $valor_porcion_alm_actual);
				}
				else // UND - PRN
				{
					$valor_porcion_alm_actual = ($lis_data_almacen[0]->valor_porcion  * $cantidad_producto);
					$stock_insumo = ($stock_porcion_alm_actual - $valor_porcion_alm_actual);
				}

				$data_alm = array(
								'stock_porcion' => str_replace(',', '', number_format($stock_insumo)),
								'fecha_modifica' => mdate("%Y-%m-%d", time()),
								'id_owner' => $this->session->userdata('person_id')
							);
				$this->almacen_model->actualizarAlmacenServicio($lis->id_serv_prov, $data_alm);
			}
		}
	}

	public function listarMesas()
  	{
      	$fecha = mdate("%Y-%m-%d", time());
      	$lis_mesas = $this->tpv_model->listarMesas();

      	echo '<div class="tab-content" style="overflow-y: scroll; height: 589px;">';
		echo '<h3 style="margin-top:10px;">Seleccione una Sala!</h3>';
		foreach($lis_mesas as $lis)
		{
	        @$lis_pv_cab = $this->tpv_model->verDatoPVCab($fecha, $lis->id_mesa);
	          	if(@$lis_pv_cab[0]->id_mesa == $lis->id_mesa)
	          	{
					$dato_empleado = $this->tpv_model->verEmpleado(@$lis_pv_cab[0]->id_emple);
	          	
				echo '<button id="btnmesa_'.$lis->id_mesa.'" class="col-xs-3 btn-danger text-center cls_mesas" style="margin: 0px; font-size: 17px; height: 82px;" onclick="identificarMesaReservada('.@$lis_pv_cab[0]->id_emple.', '.$lis->id_mesa.');">
						'.$lis->mesa.'<br />
			            <small style="font-size: 13px;">'.$dato_empleado[0]->first_name.'</small>
			        </button>';
				}else { 
	        		echo '<button id="btnmesa_'.$lis->id_mesa.'" class="col-xs-3 btn-success text-center cls_mesas" style="margin: 0px; font-size: 18px; height: 82px;" onclick="identificarMesa('.$lis->id_mesa.');">'.$lis->mesa.'</button>';
				}
		}
		echo '</div>';
  	}

  	// Proceso CAMBIAR MESA
  	public function listarMesasDispoibleCM()
  	{
      	$fecha = mdate("%Y-%m-%d", time());
      	$lis_mesas = $this->tpv_model->listarMesasDisponibles($fecha);
      	$id_tmp_cab = $this->input->post('id_tmp_cab');
      	$nro_mesa = $this->input->post('nro_mesa');

      	echo '<div class="col-xs-12" style="overflow: hidden;">';
      	echo '<div class="col-xs-6"><h3 style="margin-top:10px;">ORIGEN : '.strtoupper($nro_mesa).'</h3></div>';
		echo '<div class="col-xs-6 text-right"><h3 style="margin-top:10px;">SELECCIONE LA MESA AL CUAL DESEA TRANSFERIR</h3></div>';
		foreach($lis_mesas as $lis)
		{ 
	    echo '<button id="btnmesa_'.$lis->id_mesa.'" class="col-xs-2 btn-success text-center cls_mesas" style="margin: 0px; font-size: 18px; height: 82px;" onclick="cambiarMesaReservada('.$lis->id_mesa.', '.$id_tmp_cab.');">'.$lis->mesa.'</button>';
		
		}
		echo '</div>';
  	}

  	public function cambiarMesaReservada()
	{
		/**
		 * log de cambio de mesa
		 */
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_mesa = $this->input->post('id_mesa');
		$fecha = mdate("%Y-%m-%d", time());
		$new_mesa = $this->tpv_model->listarMesas($id_mesa);
		$data = array(
					'id_owner' => $this->session->userdata('person_id'),
					'accion_log' => 'cambio_mesa',
					'nro_mesa' => $this->input->post('nro_mesa')." =>".$new_mesa[0]->mesa,
					'fecha_log' => $fecha.'T'. mdate("%H:%i:%s", time()),
					'id_tmp_cab' => $this->input->post('id_tmp_cab')
					);
		$this->tpv_model->insertarLogUsuarios($data);


				
		// Actualiza la MESA
		$data = array('id_mesa' => $id_mesa);
		$this->tpv_model->actualizarTmpCabVenta($id_tmp_cab, $data);

		$lis_pv_cab = $this->tpv_model->verDatoPVCabMDisponible($id_tmp_cab);

		$dato_empleado = $this->tpv_model->verEmpleado($lis_pv_cab[0]->id_emple);
		$dato_mesa = $this->tpv_model->listarMesas($id_mesa);

		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		// Calcula el Total de Venta por Mesa
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($lis_pv_cab[0]->id_tmp_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		foreach($lis_tmp_pventa as $lis){
			if ($lis->dividir_cuenta == 0) {
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$precio_total_venta += $precio_total;
			}
		}

		$array = array(
					array(
						'id_tmp_cab' => $lis_pv_cab[0]->id_tmp_cab,
						'empleado' => $dato_empleado[0]->first_name,
				   		'hora_ini' => $lis_pv_cab[0]->hora_ini,
				   		'correlativo' => $lis_pv_cab[0]->correlativo,
				   		'nro_mesa' => $dato_mesa[0]->mesa,
				   		'precio_total_venta' => number_format($precio_total_venta, 2)
				   	)
			 	);
		print json_encode($array);
	}

	public function identificarEmpleadoMesa()
	{
		$fecha = mdate("%Y-%m-%d", time());
		$session['id_emple'] = $this->input->post('id_emple');
		$this->session->set_userdata($session);
	}

	public function identificarMesa()
	{
		$id_emple = $this->session->userdata('person_id');
		$hora_ini = mdate("%H:%i:%s", time());
		$fecha = mdate("%Y-%m-%d", time());
		$id_mesa = $this->input->post('id_mesa');

		$disponible = $this->tpv_model->disponibleMesa($id_mesa);
		
		if($disponible == 0){

			$correlativo = $this->tpv_model->generarCodMaxPVCab($fecha);
			$correlativo = $correlativo + 1;

			$data = array(
						'id_emple' => $id_emple,
						'hora_ini' => $hora_ini,
						'fecha' => $fecha,
						'correlativo' => $correlativo,
						'estado' => 'P',
						//'evento_1' => '1',
						'id_mesa' => $id_mesa
						);
			$id_tmp_cab = $this->tpv_model->insertarTMPPuntoVentaCAB($data);

			$dato_empleado = $this->tpv_model->verEmpleado($id_emple);
			$dato_mesa = $this->tpv_model->listarMesas($id_mesa);

			$array = array(
						array(
							'id_tmp_cab' => $id_tmp_cab,
							'empleado' => $dato_empleado[0]->first_name,
							'hora_ini' => $hora_ini,
							'correlativo' => $correlativo,
							'nro_mesa' => $dato_mesa[0]->mesa
						)
					);
		}else{
			$array = array(
				array(
					'id_tmp_cab' => -1
				)
			);
		}
		print json_encode($array);
	}

	public function identificarMesaReservada()
	{
		$id_emple = $this->input->post('id_emple');
		$id_mesa = $this->input->post('id_mesa');
		$fecha = mdate("%Y-%m-%d", time());

		//$lis_pv_cab = $this->tpv_model->verDatoPVCab($fecha, 'P', $id_mesa, $id_emple);
		$lis_pv_cab = $this->tpv_model->verDatoPVCab($fecha, $id_mesa, $id_emple);

		$dato_empleado = $this->tpv_model->verEmpleado($id_emple);
		$dato_mesa = $this->tpv_model->listarMesas($id_mesa);

		// Vuelve a resetear el campo "dividir_cuenta" a 0.
		$data = array('dividir_cuenta' => 0);
		$this->tpv_model->actualizarTMPPuntoVentaDCuenta($lis_pv_cab[0]->id_tmp_cab, $data);

		// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		// Calcula el Total de Venta por Mesa
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($lis_pv_cab[0]->id_tmp_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		foreach($lis_tmp_pventa as $lis){
			if ($lis->dividir_cuenta == 0) {
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$precio_total_venta += $precio_total;
			}
		}
		// --

		$array = array(
					array(
						'id_tmp_cab' => $lis_pv_cab[0]->id_tmp_cab,
						'empleado' => $dato_empleado[0]->first_name,
				   		'hora_ini' => $lis_pv_cab[0]->hora_ini,
				   		'correlativo' => $lis_pv_cab[0]->correlativo,
				   		'nro_mesa' => $dato_mesa[0]->mesa,
				   		'precio_total_venta' => number_format($precio_total_venta, 2)
				   	)
			 	);
		print json_encode($array);
	}

	public function listarProductosXMesa()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		$precio_total = 0;
		$array = array();
		foreach ($lis_tmp_pventa as $key => $lis)
		{
			if ($lis->dividir_cuenta <> 2)
			{
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$lis->nombre = $lis->nombre." + ICBPER"  ;
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$val = array(
					"tipo" => "producto",
					"id_tmp_cab" => $lis->id_tmp_cab,
					"categoria" => $lis->categoria,
					"correlativo" => $lis->correlativo,
					"cantidad" => $lis->cantidad,
					"nombre" => $lis->nombre,
					"dividir_cuenta" => $lis->dividir_cuenta,
					"precio_unitario" => $lis->venta,
					"precio_total" => number_format($precio_total, 2),
					"comentario" => $lis->nota_comanda
				);
				array_push($array, $val);		
					// Notas Comanda
					if(trim($lis->nota_comanda) != '')
					{
						$data_c = explode("|", trim($lis->nota_comanda));
						foreach ($data_c as $k => $l)
						{
							$d_n_c = explode(":", $l);
							$val = array(
								"tipo" => "nota_comanda",
								"id_tmp_cab" => $lis->id_tmp_cab,
								"correlativo" => $lis->correlativo,
								"id_nota_comanda" => $d_n_c[0],
								"cantidad" => "-",
								"nombre" => $d_n_c[1],
								"dividir_cuenta" => $lis->dividir_cuenta,
								"precio_unitario" => "-",
								"precio_total" => "-",
							);
						array_push($array, $val);
						}
					}
			}
		}
		print json_encode($array);
	}

	// Proceso DIVIDIR CUENTA
	public function calcularVentaProdDCuenta()
	{
		$arr_producto_dc = $this->input->post('arr_producto_dc'); //["245-1","245-2"]

		if($arr_producto_dc)
		{
			$cod_array = explode('-', $arr_producto_dc[0]);
			$id_cab = $cod_array[0];
			$data = array('dividir_cuenta' => 0);
			$this->tpv_model->actualizarTMPPuntoVentaDCuenta($id_cab, $data);
			// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
			$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
			$icbper_tax = $lis_icbper[0]->monto;

			$precio_total = 0;
			$precio_total_venta = 0;
			foreach ($arr_producto_dc as $lis)
			{
				$cod_array = explode('-', $lis);
				$id_cab = $cod_array[0];
				$correlativo = $cod_array[1];
				// Calcula el total venta seleccionado por producto
				$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVentaDCuenta($id_cab, $correlativo);
				if ($lis_tmp_pventa[0]->dividir_cuenta < 2)
				{
					if($lis_tmp_pventa[0]->categoria == 'BOLSAS') {
						$precio_total = ($lis_tmp_pventa[0]->cantidad * ($lis_tmp_pventa[0]->venta + $icbper_tax));
					} else {
						$precio_total = ($lis_tmp_pventa[0]->cantidad * $lis_tmp_pventa[0]->venta);
					}
					$precio_total_venta += $precio_total;
				}
				
				if ($lis_tmp_pventa[0]->dividir_cuenta == 0) {
					$data = array('dividir_cuenta' => 1);
					$this->tpv_model->actualizarTMPPuntoVenta($id_cab, $correlativo, $data);
				}
			}
		}
		$array = array(
					array(
				   		'precio_total_venta' => number_format($precio_total_venta, 2)
				   	)
			 	);
		print json_encode($array);
	}

	public function listarProductosDCuenta()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVentaDivMe($id_tmp_cab);
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		$array = array();
		$precio_total = 0;

		foreach ($lis_tmp_pventa as $key => $lis)
		{
			if($lis->categoria == 'BOLSAS') {
				$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
				$lis->nombre = $lis->nombre." + ICBPER" ;
			} else {
				$precio_total = ($lis->cantidad * $lis->venta);
			}
			$val = array(
				"tipo" => "producto",
				"id_tmp_cab" => $lis->id_tmp_cab,
				"categoria" => $lis->categoria,
				"correlativo" => $lis->correlativo,
				"cantidad" => $lis->cantidad,
				"nombre" => $lis->nombre,
				"dividir_cuenta" => $lis->dividir_cuenta,
				"precio_unitario" => $lis->venta,
				"precio_total" => number_format($precio_total, 2),
				"comentario" => $lis->nota_comanda
			);
			array_push($array, $val);
		}
		print json_encode($array);
	}

	public function limpiarVentaProdDCuenta()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');

		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		foreach($lis_tmp_pventa as $lis)
		{
			if ($lis->dividir_cuenta == 1) {
				$data = array('dividir_cuenta' => 0);
				$this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $lis->correlativo, $data);
			}
		}

		$con_tmp = 0;
		foreach ($lis_tmp_pventa as $con => $lis)
			$con_tmp = $con;

		$precio_total = 0;
		print '{';
					foreach ($lis_tmp_pventa as $key => $lis)
					{
						$precio_total = ($lis->cantidad * $lis->venta);
						print '"Objeto '.$key.'":';
						print '{
									"tipo" : "producto",
									"id_tmp_cab" : "'.$lis->id_tmp_cab.'",
									"correlativo" : "'.$lis->correlativo.'",
									"cantidad" : "'.$lis->cantidad.'",
									"nombre" : "'.$lis->nombre.'",
									"dividir_cuenta" : "'.$lis->dividir_cuenta.'",
									"precio_unitario" : "'.$lis->venta.'",
									"precio_total" : "'.number_format($precio_total, 2).'"
								}';
						if($con_tmp <> $key) print ',';
					}
		print '}';
	}

	public function verCobroProducto()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');

		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		foreach ($lis_tmp_pventa as $lis) {
			if ($lis->dividir_cuenta < 2) {
				$data = array('dividir_cuenta' => 0);
				$this->tpv_model->actualizarTMPPuntoVentaDCuenta($id_tmp_cab, $data);
			}
		}
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;

		$precio_total = 0;
		$precio_total_venta = 0;
		foreach ($lis_tmp_pventa as $lis) {
			if ($lis->dividir_cuenta < 2) {
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$precio_total_venta += $precio_total;
			}
		}

		$array = array(
					array(
				   		'precio_total_venta' => number_format($precio_total_venta, 2)
				   	)
			 	);
		print json_encode($array);
	}

	public function eliminarProdTMPTpv()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_cab = $cod_array[0];
		$correlativo = $cod_array[1];
		$id_emple = $this->session->userdata('person_id');


		$lis_tmp_deta = $this->tpv_model->obtenerProdTMPTpv($id_cab, $correlativo);
		
		if($lis_tmp_deta[0]->print_comanda == 0) // Todavia no se mand?? COMANDA
		{
			$this->tpv_model->eliminarProdTMPTpv($id_cab, $correlativo, $id_emple);

			$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_cab);
			foreach ($lis_tmp_pventa as $lis) {
				if ($lis->dividir_cuenta < 2) {
					$data = array('dividir_cuenta' => 0);
					$this->tpv_model->actualizarTMPPuntoVentaDCuenta($id_cab, $data);
				}
			}
			$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
			$icbper_tax = $lis_icbper[0]->monto;
			$precio_total = 0;
			$precio_total_venta = 0;
			foreach ($lis_tmp_pventa as $lis) {
				if ($lis->dividir_cuenta < 2) {
					if($lis->categoria == 'BOLSAS') {
						$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					} else {
						$precio_total = ($lis->cantidad * $lis->venta);
					}
					$precio_total_venta += $precio_total;
				}
			}
			$array = array(
						array(
							'estado_comanda' => 'procede',
					   		'precio_total_venta' => number_format($precio_total_venta, 2)
					   	)
				 	);
		}
		else
		{
			$array = array(
						array(
					   		'estado_comanda' => 'no_procede'
					   	)
				 	);
		}		
		print json_encode($array);
	}

	public function eliminarProdTMPTpvSup()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_cab = $cod_array[0];
		$correlativo = $cod_array[1];
		$lis_tmp_deta = $this->tpv_model->obtenerProdTMPTpv($id_cab, $correlativo);
		$id_emple = $this->session->userdata('person_id');
		///INSERT LOG SEND EMAIL
		$obs = $this->input->post('obs');
		$fechahora = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		$asunto = $this->session->userdata('username')." Elimino una Comanda ".mdate("%d/%m/%Y", time()).' '.date("g:i:s A");
		$mailContent = "<h1>Elimino una Comanda</h1><brs>" ;

		$mailContent .=$lis_tmp_deta[0]->categoria." =>Nro:".$lis_tmp_deta[0]->correlativo." =><b>".$lis_tmp_deta[0]->nombre."</b> Cant:".$lis_tmp_deta[0]->cantidad." precio: <b>".$lis_tmp_deta[0]->venta."</b>" ; 
		$data = array(
			'accion' => 'EliminarVta',
			'id_tmp_cab' => $id_cab,
			'correlativo' =>$correlativo,
			'obs' => $obs ,
			'fecha' => $fechahora,
			'id_owner' => $this->session->userdata('person_id')
		);
		$this->tpv_model->insertVtaLog($data);		
		//$this->sendemail($asunto ,$mailContent."<br>".$obs);
		///end send email
		$this->tpv_model->eliminarProdTMPTpv($id_cab, $correlativo,$id_emple );
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_cab);
		foreach ($lis_tmp_pventa as $lis) {
			if ($lis->dividir_cuenta < 2) {
				$data = array('dividir_cuenta' => 0);
				$this->tpv_model->actualizarTMPPuntoVentaDCuenta($id_cab, $data);
			}
		}
		$lis_icbper = $this->tpv_model->verTaxAnioBolsa(date('Y'));
		$icbper_tax = $lis_icbper[0]->monto;
		$precio_total = 0;
		$precio_total_venta = 0;
		foreach ($lis_tmp_pventa as $lis) {
			if ($lis->dividir_cuenta < 2) {
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				$precio_total_venta += $precio_total;
			}
		}
		$array = array(
					array(
						'estado_comanda' => 'procede',
						'precio_total_venta' => number_format($precio_total_venta, 2)
					)
				);
		header('Content-type: application/json');
		echo json_encode($array);
	}
	public function eliminarNotaComandaTMPTpv()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_cab = $cod_array[0];
		$correlativo = $cod_array[1];
		$id_nota_comanda = $cod_array[2];

		$print = $this->tpv_model->verPrint($id_cab,$correlativo);
		if($print==0){
			$des_tmp = $this->tpv_model->verTMPPuntoVenta($id_cab, $correlativo);
			foreach ($des_tmp as $key => $value)
			{
				$data_c = explode("|", trim($value->nota_comanda)); // array(1:SIN SAL, 2:CALIENTE)
				$borrar_nota = array_search((int)$id_nota_comanda, $data_c);
				unset($data_c[(int)$borrar_nota]);
				$nota_comanda = implode("|", $data_c);
				$data = array('nota_comanda' => $nota_comanda);
				$this->tpv_model->actualizarTMPPuntoVenta($id_cab, $correlativo, $data);
			}
			$data_update = array(
								'estado' => 0,
								'date_updated' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
								'persona_id_updated' => $this->session->userdata('person_id')
							);
			$this->tpv_model->actualizarNotaComandaProd($id_nota_comanda, $data_update);
			print '[{"estado":"ok"}]';
		}else{
			print '[{"estado":"error"}]';			
		}
	}

	public function borraVentaProducto()
	{
		$this->tpv_model->eliminarTMPTpv($this->input->post('id_tmp_cab'));
		$this->tpv_model->eliminarTMPTpvCab($this->input->post('id_tmp_cab'));

		$hora = mdate("%H:%i:%s", time());
		$fecha = mdate("%Y-%m-%d", time());
		$data = array(
					'id_owner' => $this->session->userdata('person_id'),
					'accion_log' => 'pv_suprimir_venta',
					'nro_mesa' => $this->input->post('nro_mesa'),
					'fecha_log' => $fecha.'T'.$hora,
					'id_tmp_cab' => $this->input->post('id_tmp_cab')
					);
		$this->tpv_model->insertarLogUsuarios($data);
	}

	public function reimprimirVentaTicket()
	{
		$id_transac = $this->input->post('id_transac');

		// Proceso "Imprimir el TICKET"
			$lis_tv = $this->tpv_model->listarTransacVentaCAB($id_transac);
			$lis_tv_deta = $this->tpv_model->listarTransacVentaDetalle($id_transac);
			$lis_t_pago = $this->tpv_model->verTipoPago($lis_tv[0]->id_tp);

			$lis_user_venta = $this->tpv_model->verUsuarioVenta($lis_tv[0]->id_tmp_cab);
			$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "SOLES"

			$lis_serie_doc = $this->series_documentos_model->ver($lis_tv[0]->id_tp);
			$id_serie = $lis_serie_doc[0]->id_serie;
			$tipo_doc = $lis_serie_doc[0]->tipo_doc;
			
			$cuerpo_tck = $this->getCabeceraImpresionTck();
			$cuerpo_tck .= "Tk: ".$lis_tv[0]->num_doc."  ".mdate("%d/%m/%y", time()).' '.mdate("%H:%i:%s", time())."\n";

			$cuerpo_tck .= "====================================\n";

			if($lis_tv[0]->id_cliente <> 0)
			{
				$lis_cliente = $this->tpv_model->verClienteVenta($lis_tv[0]->id_cliente);
				$cuerpo_tck .= "RUC     : ".$lis_cliente[0]->nro_doc."\n";
				$cuerpo_tck .= "Cliente : ".$lis_cliente[0]->razon_social."\n";
				$cuerpo_tck .= "====================================\n";
			}
			$cuerpo_tck .= "   Descripcion    ".$this->g_espacio_print."CantP.Unit P.Total\n";
			$cuerpo_tck .= "====================================\n";
			foreach ($lis_tv_deta as $lis)
			{
				// RAG(09/2019) : Se actualiza por ICBPER (Bolsas)
				if($lis->categoria == 'BOLSAS') {
					$precio_total = ($lis->cantidad * ($lis->venta + $icbper_tax));
					$aux_cant_icbper = $lis->cantidad;
				} else {
					$precio_total = ($lis->cantidad * $lis->venta);
				}
				// PRODUCTO
				$text_producto = str_pad(substr($lis->producto, 0, 16), 18 ," ", STR_PAD_RIGHT);
				// CANTIDAD
				$text_cant = str_pad($lis->cantidad, 2 ," ", STR_PAD_LEFT);
				// VENTA
				$text_venta = str_pad($lis->venta, 8 ," ", STR_PAD_LEFT);
				// TOTAL
				$text_total = str_pad(number_format($precio_total, 2), 7 ," ", STR_PAD_LEFT);
				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";

			}
			$cuerpo_tck .= "====================================\n";

			// TOTAL NETO
			$total_neto = str_pad($lis_tv[0]->subtotal_venta, 8 ," ", STR_PAD_LEFT);
			// IGV
			$igv = str_pad($lis_tv[0]->igv, 8 ," ", STR_PAD_LEFT);
			// TOTAL VENTA
			$total_venta = str_pad($lis_tv[0]->total_venta, 8 ," ", STR_PAD_LEFT);
			// PAGO CLIENTE
			$pago_cliente = str_pad($lis_tv[0]->pago_cliente, 8 ," ", STR_PAD_LEFT); 
			// VUELTO
			$vuelto = str_pad($lis_tv[0]->vuelto, 8 ," ", STR_PAD_LEFT); 
			
			$text_tipo_pago =  str_pad($lis_t_pago[0]->tipo_pago, 25 ," ", STR_PAD_LEFT); 
			
			$cuerpo_tck .= "\t     Total Neto   ".$moneda_ticket.$total_neto."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "\t            IGV   ".$moneda_ticket.$igv."\n";
			$cuerpo_tck .= "\t          TOTAL   ".$moneda_ticket.$total_venta."\n";

			if($id_serie <> 7)
			{
				$cuerpo_tck .= "====================================\n";
				$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$pago_cliente."\n";
			}

			// if($lis_t_pago[0]->id_tp == 1 && $id_serie <> 7) // 7 = Cortesia
			// 	$cuerpo_tck .= "\t\t     Cambio   ".$moneda_ticket.$vuelto."\n";

			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= "  	   Cajero(a): ".strtoupper($usuario_venta[0])."\n";

			if($id_serie == 7)
				$cuerpo_tck .= "             CORTESIA               \n";

			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			$this->printerTCKNegrita($nv_cuerpo_tck, $this->g_ruta_printer_simple);
		
	}

	public function imprimirCambioTurno()
	{
		$this->tpv_model->limpMesas();

		$id_emple = $this->session->userdata('person_id');
		$fecha_cierre = mdate("%Y-%m-%d", time());
		$hora_cierre = mdate("%H:%i:%s", time());
		$cuerpo_tck ="";
		$turno = $this->ventas_model->verTurnoCaja($fecha_cierre);
		$turno = $turno + 1;
		$lis_user_venta = $this->tpv_model->verEmpleadoVenta($id_emple);
		$moneda_ticket = substr($this->g_moneda, 0, 2); 

		$total_venta_turno = $this->ventas_model->verTotalVentaXTurto($fecha_cierre);		
		$lis_ventas = $this->ventas_model->verListaVentaCierreCajaCTurno($fecha_cierre);
		$lis_ventas_tp = $this->ventas_model->verListaVentaGrupalesTPCturno($fecha_cierre);
		
		if($total_venta_turno>0){
			$total_efectivo = $total_tarjetas = 0;
			
			foreach ($lis_ventas_tp as $lis)
			{
				if($lis->tipo_pago=="Efectivo"){
					$total_efectivo = $lis->monto;
				}else{
					$total_tarjetas = $total_tarjetas + $lis->monto;
				}
			}
			$total_caja = ($total_efectivo + $total_tarjetas);

			$data = array(
						'turno' => $turno,
						'total_ticket' => $total_venta_turno,
						'total_cliente' => $total_venta_turno,
						'total_efectivo' => $total_efectivo,
						'total_tarjetas' => $total_tarjetas,
						'total_caja' => $total_caja,
						'fecha_cierre' => $fecha_cierre,
						'hora_cierre' => $hora_cierre,
						'id_emple' => $id_emple,
						'date_created' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
						'persona_id_created' => $this->session->userdata('person_id')
					);
			$id_cierre_caja = $this->ventas_model->insertarCierreCaja($data);

			$num_caja =  'CAJA-'.str_pad($id_cierre_caja, 6 ,"0", STR_PAD_LEFT);
				
			
			
			$cuerpo_tck = $this->getCabeceraImpresionTck();
			$cuerpo_tck .= "Tk: ".$num_caja."   ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";

			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= "    Reporte de Cambio de Turno      \n";
			$cuerpo_tck .= "       Cajero(a): ".strtoupper($lis_user_venta[0]->first_name)."\n";
			$cuerpo_tck .= "Turno: ".$turno."\n";
			$cuerpo_tck .= "Guia de Cierre: ".$id_cierre_caja."\n";
			$cuerpo_tck .= "Fecha Impresion: ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= "PROMEDIOS:\n";
			
			$cuerpo_tck .= "Clientes Local:                  ".$total_venta_turno."\n";
			$cuerpo_tck .= "====================================\n";

			foreach ($lis_ventas_tp as $lis)
			{
				$lis_venta_reg = $this->ventas_model->verListaVentaXTPagoCaja( $lis->tipo_pago);
			
				$cuerpo_tck .= strtoupper($lis->tipo_pago)."\n";

				$cuerpo_tck .= "   Ticket#                   P.Total\n";

				$total_cobrado = 0;
				foreach ($lis_venta_reg as $lisd)
				{
					$text_total = str_pad($lisd->monto, 8 ," ", STR_PAD_LEFT);
					$cuerpo_tck .= $lisd->num_doc."         ".$text_total."\n";
				}
				$total_cobro = str_pad(number_format($lis->monto, 2), 8 ," ", STR_PAD_LEFT);

				$cuerpo_tck .= "                                    \n";
				$cuerpo_tck .= "Total Cobrado            ".$moneda_ticket.$total_cobro."\n";
				$cuerpo_tck .= "====================================\n";
			}
			foreach ($lis_ventas as $lis)
			{
				$this->tpv_model->actualizarTransacVenta(array('id_cierre' => $id_cierre_caja), $lis->id_transac);
			}
			
			// TOTAL EFECTIVO
			$total_efectivo = str_pad(number_format($total_efectivo, 2), 8 ," ", STR_PAD_LEFT);
			// TOTAL TARJETAS
			$total_tarjetas = str_pad(number_format($total_tarjetas, 2), 8 ," ", STR_PAD_LEFT);
			// TOTAL CAJA
			$total_caja = str_pad(number_format($total_caja, 2), 8 ," ", STR_PAD_LEFT);

			$cuerpo_tck .= "                RESUMEN                 \n";
			$cuerpo_tck .= "Efectivo Soles           ".$moneda_ticket.$total_efectivo."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "Tarjetas                 ".$moneda_ticket.$total_tarjetas."\n";
			$cuerpo_tck .= "TOTAL CAJA               ".$moneda_ticket.$total_caja."\n";
			$cuerpo_tck .= "=====================================\n";
			$cuerpo_tck .= "     ".$this->g_firma_ticket."\n";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
		}else{
			$cuerpo_tck = "Sin Ventas                      0.00\n";
			$cuerpo_tck .= "====================================\n";
		}
		
		$this->printerTCKNegrita($cuerpo_tck, $this->g_ruta_printer_simple);	
	}

	public function imprimirCierreCaja()
	{

		$id_emple = $this->session->userdata('person_id');
		$fecha_cierre = mdate("%Y-%m-%d", time());
		$hora_cierre = mdate("%H:%i:%s", time());
				
		$total_ticket = $this->ventas_model->verTotalTicketCajaXDia($fecha_cierre);
		
		if($total_ticket>0){
			
			$lis_user_venta = $this->tpv_model->verEmpleadoVenta($id_emple);
			$moneda_ticket = substr($this->g_moneda, 0, 2); 

			$cuerpo_tck = $this->getCabeceraImpresionTck();

			$cuerpo_tck .= "       REPORTE CIERRE DE CAJA      \n";
			$cuerpo_tck .= "Guia de Cierre: Final\n";
			$cuerpo_tck .= "Fecha Impresion: ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			// Aqui calculo de horas trabajadas en los turnos.
			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= "PROMEDIOS:\n";

			$tot_ticket =	str_pad($total_ticket, 3, ' ', STR_PAD_LEFT);
			$cuerpo_tck .= "Ticket Local:                    ".$tot_ticket."\n";
			$cuerpo_tck .= "Clientes Local:                  ".$tot_ticket."\n";
			// Aqui se puede adicionar los totales por Mesa
			$cuerpo_tck .= "====================================\n";

			$lis_ventas_tp = $this->ventas_model->verListaVentaGrupalesTPCaja($fecha_cierre);
			if($lis_ventas_tp)
			{
				foreach ($lis_ventas_tp as $lis)
				{
					
					$cuerpo_tck .= strtoupper($lis->tipo_pago).":\n";

					$total_ope =	str_pad($lis->cantidad, 3, ' ', STR_PAD_LEFT);
					$total_cobro =	str_pad(number_format($lis->monto, 2), 8, ' ', STR_PAD_LEFT);

					$cuerpo_tck .= "                                    \n";
					$cuerpo_tck .= "Total operaciones:     ".$total_ope."\n";
					$cuerpo_tck .= "Total Cobrado:     ".$moneda_ticket.$total_cobro."\n";
					$cuerpo_tck .= "====================================\n";
				}
			}
			else
			{
				$cuerpo_tck .= "Sin Ventas                      0.00\n";
				$cuerpo_tck .= "====================================\n";
			}

			$lis_cc = $this->ventas_model->verTotalesCierreCaja($fecha_cierre);
			$total_efectivo = $lis_cc[0]->total_efectivo;
			$total_tarjetas = $lis_cc[0]->total_tarjetas;
			$total_caja = $lis_cc[0]->total_caja;

			// TOTAL EFECTIVO
			$total_efectivo = str_pad($total_efectivo, 8, ' ', STR_PAD_LEFT);
			// TOTAL TARJETAS
			$total_tarjetas = str_pad($total_tarjetas, 8, ' ', STR_PAD_LEFT);		
			// TOTAL CAJA
			$total_caja = str_pad($total_caja, 8, ' ', STR_PAD_LEFT);	

			$cuerpo_tck .= "                RESUMEN                 \n";
			$cuerpo_tck .= "Efectivo Soles  ".$moneda_ticket.$total_efectivo."\n"; 
			$cuerpo_tck .= "Tarjetas        ".$moneda_ticket.$total_tarjetas."\n";
			$cuerpo_tck .= "TOTAL DIA       ".$moneda_ticket.$total_caja."\n";
			$cuerpo_tck .= "====================================\n";
			$cuerpo_tck .= " ".$this->g_firma_ticket."\n";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
		}else{
			$cuerpo_tck = "Sin Ventas                      0.00\n";
			$cuerpo_tck .= "====================================\n";
		}

		$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
		$this->printerTCKNegrita($nv_cuerpo_tck, $this->g_ruta_printer_simple);			
	}

	public function sendemail($subject ,$mailContent )
  	{
      // Load PHPMailer library
	  $this->load->library('phpmailer_lib');   
	  // PHPMailer object
	  $mail = $this->phpmailer_lib->load();	  
	  // SMTP configuration
	  $mail->isSMTP();
	  $mail->Host     = 'smtp.gmail.com';
	  $mail->SMTPAuth = true;
	  $mail->Username = 'noreply.elgrancharlee@gmail.com';
	  $mail->Password = '1234cincoX';
	  $mail->SMTPSecure = 'ssl';
	  $mail->Port     = 465;
	  
	  $mail->setFrom('noreply.elgrancharlee@mail.com', 'no reply');
	   // Add a recipient
	  $mail->addAddress($this->g_mail_envio);	
	  // copia email 
	  $mail->addCC($this->g_mail_copia); 	  
	  // Email subject
	  $mail->Subject = $subject ;	 
	  $mail->isHTML(true);
	  $mail->Body = $mailContent;
	  $mail->send();
	  
  	}

	public function asignarCliente()
	{
		echo $this->input->post('id_cliente');
	}

	public function salir()
	{
		$this->session->sess_destroy();
		redirect('login');
	}

	public function titleQR(Printer $printer, $str)
	{
	    $printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
	    $printer -> text($str);
	    $printer -> selectPrintMode();
	}

    public function obtenerDetalleVentaTK($name = '', $cant = '', $price = '', $total = '')
	{
	    if(strlen($name) >= 26)
            $name = substr($name, 0, 26);
        		
        $leftName		= str_pad($name, 27) ;        
        $rightCant   	= str_pad($cant, 3, ' ', STR_PAD_LEFT);
		$rightPrecio 	= str_pad($price, 7, ' ', STR_PAD_LEFT);
		$rightTotal 	= str_pad($total, 7, ' ', STR_PAD_LEFT);
        return "$leftName$rightCant$rightPrecio$rightTotal\n";
	}
    
    public function obtenerTotalesVentaTK($name = '', $price = '', $moneda = false)
	{
        $rightCols = 10;
        $leftCols = 34;
        if ($moneda) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($name, $leftCols) ;
        
        $sign = ($moneda ? 'S/ ' : '');
        $right = str_pad($sign . $price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }

	public function verVentasXDia()
	{
		header('Content-type: application/json');
		$data['data']  = $this->ventas_model->verVentasXDia($this->fecha_actual);
		$data['total_caja']  = $this->ventas_model->sumaVentasXDia($this->fecha_actual);
		echo json_encode($data);
	}	

	public function suprimirVta()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$obs = $this->input->post('obs');
		$fechahora = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		$mydata = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$asunto = $this->session->userdata('username')." Elimino la venta ".mdate("%d/%m/%Y", time()).' '.date("g:i:s A");;
		$mailContent = "<h1>Se Suprimio una Venta</h1><br><ul>" ;

		foreach ($mydata as $lis){
			$mailContent .="<li>".$lis->categoria." =>Nro:".$lis->correlativo." =><b>".$lis->nombre."</b> Cant:".$lis->cantidad." precio: </b>".$lis->venta."</b></li>" ; 
			$data = array(
				'accion' => 'EliminarVtaTotal',
				'id_tmp_cab' => $id_tmp_cab,
				'correlativo' =>$lis->correlativo,
				'obs' => $obs ,
				'fecha' => $fechahora,
				'id_owner' => $this->session->userdata('person_id')
			);
			$this->tpv_model->insertVtaLog($data);
		}		
		//$this->sendemail($asunto ,$mailContent."</ul><br>".$obs);		
	}

	public function changeCant()
	{
		$id_cab = $this->input->post('id_tmp_cab');
		$correlativo = $this->input->post('correlativo');
		$new_cant = $this->input->post('newCantidad');
		$comentario = $this->input->post('comentario');
		$fechahora = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		
		$lis_tmp_deta = $this->tpv_model->obtenerProdTMPTpv($id_cab, $correlativo);
		$lis_tmp = $this->tpv_model->listarTMPPuntoVentaCAB($id_cab );

		$obs = "Se cambio el: ".$lis_tmp_deta[0]->nombre." de: ".$lis_tmp_deta[0]->cantidad." a: ".$new_cant." por motivo: ".$comentario;
		$data = array(
			'cantidad' => $new_cant,  
			'persona_id_updated' => $this->session->userdata('person_id') ,
			'date_updated' => $fechahora
		);
		$where = array(
			'id_tmp_cab' => $id_cab ,
			'correlativo' => $correlativo
		);
		
		$datalog = array(
			'accion' => 'CambiarCantidad',
			'id_tmp_cab' => $id_cab,
			'correlativo' =>$correlativo,
			'obs' => $obs ,
			'fecha' => $fechahora,
			'id_owner' => $this->session->userdata('person_id')
		);
		$asunto = $this->session->userdata('username')." Cambio la Cantidad en Comanda ".mdate("%d/%m/%Y", time()).' '.date("g:i:s A");
		// $mailContent = "<h1>Se Suprimio una Venta</h1><br>" ;

		$this->tpv_model->updateTmp_pventa($where, $data);
		$this->tpv_model->insertVtaLog($datalog);

		//$this->sendemail( $asunto , $obs );

		header('Content-type: application/json');
		$resp = array(
			'id_mesa' => $lis_tmp[0]->id_mesa,
			'id_tmp_cab' => $id_cab ,
			'id_emple' => $lis_tmp[0]->id_emple
		);
		echo json_encode($resp);	
	}

	public function changeMP()
	{
		$id_transac = $this->input->post('id_transac');
		$id_tp = (int) $this->input->post('id_tp');
		$id_tptext =  $this->input->post('id_tptext');
		$glosa =  $this->input->post('glosa');
		$id_transac_mp =  $this->input->post('id_transac_mp');
		$fechahora = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		$persona_upd = $this->session->userdata('person_id') ;
		
		$ver_id= $this->tpv_model->listarTransacVentaCAB($id_transac);
		if($ver_id[0]->id_tp==6){
			$new_tp = 6;
		}else{
			$new_tp = $id_tp;
		}

		$data = array(
			'id_tp' => $new_tp,  
			'persona_id_updated' => $persona_upd ,
			'date_updated' => $fechahora,
			'glosa'=> $glosa,
		);
				
		$this->tpv_model->updatetransac(array(
			'id_transac' => $id_transac 
		), $data);
		
		$data2=array(
			'id_tp'=>$id_tp ,
			'tipo_pago'=>$id_tptext,

		);
		$this->tpv_model->updatetransacmpago(array(
			'id_transac_mp ' => $id_transac_mp 
		), $data2);
	}

	public function limpMesas(){
		$this->tpv_model->limpMesas();
	}

	public function anularVta()
	{
		$id_transac = $this->input->post('id_transac');
		$motivo = $this->input->post('motivo');
		$fechahora = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		$persona_upd = $this->session->userdata('person_id') ;
		
		$data = array(
			'anulado' => 'SI',  
			'glosa' => $motivo,
			'persona_id_updated' => $persona_upd ,
			'date_updated' => $fechahora
		);
		
		$where = array(
			'id_transac' => $id_transac 
		);
		
		$this->tpv_model->updatetransac($where, $data);
		
		$transac = $this->tpv_model->listarTransacVentaCAB($id_transac );
		$asunto = "COMPROBANTE ANULADO POLICLINICO ".$transac[0]->num_doc;
		// $this->sendemail($asunto , $motivo);	

	}


	public function prueba(){
		$tp_nruc='RUC';
		$tipo_doc_sunat = $this->tpv_model->obtdatoCliente($tp_nruc);
		echo $tipo_doc_sunat;
	}

	public function generartxtfactu($id_transac){
	
		$ruta_dat_sunat = 'C:\SUNAT\sunat_archivos\sfs\DATA/';
		// sunat = 20509921793-03-B001-00000001.cab			
		$transac_venta = $this->tpv_model->listarTransacVentaCAB($id_transac);
		$nom_file = $this->g_ruc.'-'.$transac_venta[0]->tdoc.'-'.$transac_venta[0]->sfactu.'-'.$transac_venta[0]->nfactu;

		$file_cab =  $ruta_dat_sunat.$nom_file.'.cab';
		if(count($transac_venta) > 0)
		{	        
			if($archivo = fopen($file_cab, "w")) 
			{
				if($transac_venta[0]->id_serie == 1) { // Factura
					$valor_adquiriente = '6|'.$transac_venta[0]->n_ruc.'|'.$transac_venta[0]->n_rs;
				} else {
					$valor_adquiriente = '1|'.$transac_venta[0]->n_ruc.'|'.$transac_venta[0]->n_rs;
				}
				//$line = '0101|'.date('Y-m-d').'|'.date('H:i:s').'|-|0000|'.$valor_adquiriente.'|PEN|'.$value->igv.'|'.$value->subtotal_venta.'|'.$value->total_venta.'|0.00|0.00|0.00|'.$value->total_venta.'|2.1|2.0|';
				$line = '0101|'.mdate('%Y-%m-%d',strtotime($transac_venta[0]->fecha_registro)).'|'.mdate('%H:%i:%s',strtotime($transac_venta[0]->date_created)).'|-|0000|'.$valor_adquiriente.'|PEN|'.round($transac_venta[0]->igv,2).'|'.$transac_venta[0]->subtotal_venta.'|'.number_format($transac_venta[0]->igv+$transac_venta[0]->subtotal_venta, 2).'|0.00|'.number_format($transac_venta[0]->total_venta - ($transac_venta[0]->igv+$transac_venta[0]->subtotal_venta), 2).'|0.00|'.$transac_venta[0]->total_venta.'|2.1|2.0|';
				fwrite($archivo, $line);
				
				fclose($archivo);
			}
		}
		// --

		// GENERAR ARCHIVO .DET - sunat = 20509921793-03-B001-00000001.det
		$file_det =  $ruta_dat_sunat.$nom_file.'.det';

		$transac_venta_deta = $this->tpv_model->listarTransacVentaDetalle($id_transac);
		if(count($transac_venta_deta) > 0)
		{
			if($archivo = fopen($file_det, "w")) //a
			{
				$icbper_line = '';
				$icbper_total = 0;
				$icbper_cant = '';
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
					$line = 'NIU|'.$value->cantidad.'|1|-|'.$value->producto.'|'.round($subtotal_venta/$value->cantidad, 2).'|'.round($igv_det, 2).'|1000|'.round($igv_det, 2).'|'.round($subtotal_venta, 2).'|IGV|VAT|10|'.number_format($this->g_igv, 2).
							'|-|0.00|0.00||||0.00|-|0.00|0.00|||'.
							$icbper_line.
							number_format(round($m_precio_ven_uni/$value->cantidad, 2), 2).'|'.round($subtotal_venta, 2).'|0.00|'."\r\n";
					fwrite($archivo, $line);
				}
				fclose($archivo);
			}
		}

		// GENERAR ARCHIVO .LEY - sunat = 20509921793-03-B001-00000001.ley
		$file_ley =  $ruta_dat_sunat.$nom_file.'.ley';
		$ob_nal = new NumeroALetras();
		if($archivo = fopen($file_ley, "w")) //a
		{
			foreach ($transac_venta as $value)
			{		            	
				$line = '1000|'.$ob_nal->convertir(floor($value->total_venta)).' CON '.substr($value->total_venta, -2).'/100 SOLES'.'|';
				fwrite($archivo, $line);
			}
			fclose($archivo);
		}
		// GENERAR ARCHIVO .PAG - sunat = 20509921793-03-B001-00000001.pag
		$fila_pago =  $ruta_dat_sunat.$nom_file.'.pag';
		
		if($archivo = fopen($fila_pago, "w")) //a
		{		        		            	
			$line = 'Contado|-|-|';
			fwrite($archivo, $line);
			fclose($archivo);
		}
		// GENERAR ARCHIVO .TRI - sunat = 20509921793-03-B001-00000001.tri
		$file_tri =  $ruta_dat_sunat.$nom_file.'.tri';
				
		if($archivo = fopen($file_tri, "w")) //a
		{
			foreach ($transac_venta as $value)
			{
				$line = '1000|IGV|VAT|'.$value->subtotal_venta.'|'.round($value->igv, 2).'|';
				fwrite($archivo, $line);
			}
			fclose($archivo);
		}
	}

	
}
