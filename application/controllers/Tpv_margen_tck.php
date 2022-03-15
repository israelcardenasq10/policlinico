<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Tpv extends Secure_area {

	private $cod_categoria;
	private $g_ruta_printer;
	private $g_ruta_printer_cocina;
	private $g_ruta_printer_barra;

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('tpv_model');
    		$this->load->model('productos_model');
    		$this->load->model('almacen_model');
    		$this->load->model('series_documentos_model');
    		$this->load->model('globales_model');
    		$this->load->model('ventas_model');

		$this->load->library(array('session','form_validation'));

		$data['id_user'] = $this->session->userdata('id_user');
		$data['person_id'] = $this->session->userdata('person_id');
		$data['username'] = $this->session->userdata('username');

		// Define las acciones por Modulo!
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		$data['lis_empleados'] = $this->tpv_model->listarEmpleados('5');
		$data['lis_mesas'] = $this->tpv_model->listarMesas();
		$data['lista_documentos'] = $this->series_documentos_model->listar();
		$data['lis_tpagos'] = $this->tpv_model->listarTipoPagos();


		$data['lis_clientes'] = $this->tpv_model->listarClientes();

		$this->cod_categoria = '1001'; // Codigo del primer ID Categoria Producto
		
		// CONFIGURACIÓN DE LAS IMPRESORAS
		$this->g_ruta_printer = 'TKT_MTR1';
		$this->g_ruta_printer_cocina = 'TKT_TRM1';
		$this->g_ruta_printer_barra = 'TKT_MTR2';
		// --

		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['lis_productos'] = $this->tpv_model->filtrarProductos($this->cod_categoria);

		// Cumpleaños!
		$lis_emple_gen = $this->tpv_model->listarEmpleadosGen();
		if($lis_emple_gen):
			foreach($lis_emple_gen as $lis):
	            if(substr($lis->fecha_nace, 5) == substr($this->fecha_actual, 5))
	            {
	                $data['nombre_cumple'] = $lis->first_name;
	                $data['apelli_cumple'] = $lis->last_name;
	                $data['imagen_cumple'] = $lis->imagen;
	            }
	        endforeach;
        endif;
    // --

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

				if(@$venta_prod == 'NO' || $lis_insu === NULL){
					@$disabled_prod = 'pointer-events: none; color: rgba(0,0,0,0.1);';
					@$texto_prod_disab = 'text-decoration: line-through; font-weight: bold;';
				}else{
					@$disabled_prod = '';
					@$texto_prod_disab = '';
				}
			?>
         <?php if($this->g_pv_prod_images === '1'):
            			if($lis->imagen) $imagen = $lis->imagen;
            			else $imagen = 'no_disponible.jpg'; ?>
					        <div class="col-xs-4">
					            <div class="panel text-center" style="padding: 10px; height: 140px; cursor: pointer; box-shadow: 1px 1px 1px 1px gray; color: blue;  <?=$disabled_prod?>" onclick="agregarProducto('<?=$lis->id_categoria?>', '<?=$lis->id_producto?>');">
					                <div class="pull-left" style="color: white; font-weight: bold; font-size: 30px; border-bottom: solid 3px silver; position: absolute; background-color: black; padding: 0px 10px 0px 10px;"><?=$lis->nro_producto?></div>
					                <!--<div style="<?=$style_prod.' '.$texto_prod_disab?>"><?=$lis->nombre?></div>-->
					                <div class="panel-body">
					                    <img class="img-responsive" src="<?=base_url()?>public/images/productos/<?=$imagen?>"  />
					                </div>
					            </div>
					         </div>

         <?php else: ?>
			         	<div class="col-xs-4">
			            <div class="panel text-center" style="padding: 10px; height: 80px; cursor: pointer; box-shadow: 1px 1px 1px 1px gray; color: blue;  <?=$disabled_prod?>" onclick="agregarProducto('<?=$lis->id_categoria?>', '<?=$lis->id_producto?>');">
			                <div class="pull-left" style="color: black; font-weight: bold; font-size: 30px; position: relative;  padding: 0px 10px 0px 10px; margin: 0;"><?=$lis->nro_producto?></div>
			                <div style="<?=$style_prod.' '.$texto_prod_disab?>"><?=$lis->nombre?></div>
			            </div>
								</div>
         <?php endif; ?>
      <?php
			endforeach;
	}

	public function agregarProducto() //$id_cat, $id_prod
	{
		$id_categoria = $this->input->post('id_categoria');
		$id_producto = $this->input->post('id_producto');
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$cant_calculador_prod = $this->input->post('cant_calculador_prod');

		// Muestra el último "correlativo"
		$correlativo = $this->tpv_model->obtenerCorrelativoDetaTMP($id_tmp_cab);
		if($correlativo > 0)
			$correlativo = $correlativo + 1;
		else
			$correlativo = 1;
		// --

		// Calcular Stock Min de Venta para cada Producto por su Insumo: stock_porcion, stock_min (VALIDACIONES)
			$lis_insu = $this->tpv_model->verificarStockInsumoProdAlmacen($id_producto);
			$cant_prod_ins = $this->tpv_model->verCantProdSelectTMP($id_producto); //($id_tmp_cab)

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
				return; // Aqui corta el código.
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
				return; // Aqui corta el código.
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
				return; // Aqui corta el código.
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
			{
				$valida = '';
				$mensaje = '';
			}
		// Cierra validaciones.


		$des_prod = $this->tpv_model->obtenerDesProducto($id_producto);
		foreach ($des_prod as $key => $value)
		{
			$data_insert = array(
								'id_tmp_cab' => $id_tmp_cab,
								'id_producto' => $value->id_producto,
								'id_categoria' => $value->id_categoria,
								'correlativo' => $correlativo,
								'nombre' => $value->nombre,
								//'imagen' => $value->imagen,
								'categoria' => $value->categoria,
								'cantidad' => $cant_calculador_prod, // default = 1
								'venta' => $value->precio_venta
							);
			$this->tpv_model->insertarTMPPuntoVenta($data_insert);
		}

		// --
		$precio_total = ($cant_calculador_prod * $des_prod[0]->precio_venta);
		$precio_total_venta = $precio_total;

		$array = array(
							array(
								'valida' => $valida,
								'mensaje' => $mensaje,
								'id_tmp_cab' => $id_tmp_cab,
								'correlativo' => $correlativo,
								'nombre' => $des_prod[0]->nombre,
								'cantidad' => $cant_calculador_prod,
								'precio_unitario' => $des_prod[0]->precio_venta,
								'precio_total' => number_format($precio_total, 2),
								'precio_total_venta' => number_format($precio_total_venta, 2)
							)
						);
		// --

		print json_encode($array);
	}


	public function agregarNotaProd()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_nota_comanda = $this->input->post('id_nota_comanda');

		$correlativo = $this->tpv_model->obtenerCorrelativoDetaTMP($id_tmp_cab);
		$des_nc = $this->tpv_model->obtenerDatosNotaComanda($id_nota_comanda);
		$des_tmp = $this->tpv_model->verTMPPuntoVenta($id_tmp_cab, $correlativo);

		foreach ($des_tmp as $key => $value)
		{
			// ejemplo = 1:SIN SAL|2:CALIENTE
			if(trim($value->nota_comanda) == '')
				$nota_comanda = $des_nc[0]->id.':'.$des_nc[0]->nota;
			else
				$nota_comanda = $value->nota_comanda.'|'.$des_nc[0]->id.':'.$des_nc[0]->nota;
			// --

			$data = array('nota_comanda' => $nota_comanda);
			$this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $correlativo, $data);
		}

		// --
		$array = array(
							array(
								'id_tmp_cab' => $id_tmp_cab,
								'correlativo' => $correlativo,
								'id_nota_comanda' => $id_nota_comanda,
								'nombre' => $des_nc[0]->nota
							)
						);
		// --
		print json_encode($array);
	}

	public function agregarCampoNotaProd()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$id_nota_comanda = $this->input->post('id_nota_comanda');
		$nota_comanda_text = $this->input->post('nota_comanda');

		$correlativo = $this->tpv_model->obtenerCorrelativoDetaTMP($id_tmp_cab);

		$data_nc = array(
									'nota' => strtoupper($nota_comanda_text),
									'estado'=>1,
									'date_created' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
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
			// --

			$data = array('nota_comanda' => $nota_comanda);
			$this->tpv_model->actualizarTMPPuntoVenta($id_tmp_cab, $correlativo, $data);
		}

		// --
		$array = array(
							array(
								'id_tmp_cab' => $id_tmp_cab,
								'correlativo' => $correlativo,
								'id_nota_comanda' => $id_nota_comanda,
								'nombre' => strtoupper($nota_comanda_text)
							)
						);
		// --
		print json_encode($array);
	}


	// Proceso de Comanda (Cocina, Barra)
	public function generarComanda()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');

		// Proceso "Imprimir el TICKET"
			$lis_tv = $this->tpv_model->listarTMPPuntoVentaCAB($id_tmp_cab);
			$lis_tv_deta = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
			$lis_user_venta = $this->tpv_model->verUsuarioVenta($id_tmp_cab);
			$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // Soles
			$dato_mesa = $this->tpv_model->listarMesas($lis_tv[0]->id_mesa);

			$print_comanda = false;
			$array_prod_coman = array();
			$cuerpo_tck_cn = '';
			foreach ($lis_tv_deta as $lis)
			{
				if($lis->print_comanda == 0)
				{
					$len_prod = strlen($lis->nombre);
					$len_cant = strlen($lis->cantidad);

					// PRODUCTO
					if($len_prod <= 11)
						$text_producto = $lis->nombre."\t\t      ";
					elseif($len_prod >= 11 && $len_prod <= 19)
						$text_producto = $lis->nombre."\t      ";
					else
						$text_producto = substr($lis->nombre, 0, 20)."\t      ";

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
					array_push($array_prod_coman, $cuerpo_tck_c);
					
					// Actualiza el campo "Print_comanda" para saber que ya se mando a comanda el producto
					$des_tmp = $this->tpv_model->verTMPPuntoVenta($lis->id_tmp_cab, $lis->correlativo);
					if($des_tmp != NULL)
					{
						foreach ($des_tmp as $key => $value)
						{
							if(trim($value->nota_comanda) != '')
							{
								$nota_comanda = '';
								$c_n_c = '';
								$data_c = explode("|", trim($value->nota_comanda)); // array(1:SIN SAL, 2:CALIENTE)
								foreach ($data_c as $k => $val)
								{
									$notas_c = explode(':', $val);

									if($k>0) $c_n_c = ', ';
									$nota_comanda .= $c_n_c.$notas_c[1];
								}
								$cuerpo_tck_cn .= "- (".$nota_comanda.")";
								array_push($array_prod_coman, $cuerpo_tck_cn);
							}
						}
					}
					$data = array('print_comanda' => 1);
					$this->tpv_model->actualizarTMPPuntoVenta($lis->id_tmp_cab, $lis->correlativo, $data);
					// --					
					
					$print_comanda = true;
				} // Cierra verificación de "print_comanda"
			}

			if($print_comanda == true)
			{
				$this->printerTCKComanda($array_prod_coman, $this->g_ruta_printer_cocina, $dato_mesa, $usuario_venta);
			}
		// --
	}
	// --
	
	public function printerTCKComanda($array_prod_coman, $ruta_printer, $dato_mesa, $usuario_venta)
	{
		$enlace = printer_open($ruta_printer);
		printer_start_doc($enlace, "");
		printer_start_page($enlace);
		$font = printer_create_font("Arial", 30, 16, 400, false, false, false, 0);
		printer_select_font($enlace, $font);
		
		$cum = 120; //Acumula Array de Productos comanda
		$sum = 30; // Suma de 30 en 30
		printer_draw_text($enlace, 'Tck#: COMANDA       '.mdate("%d/%m/%y", time()).' '.mdate("%H:%i", time()), 1, 30);
		printer_draw_text($enlace, '--------------------------------------------------', 1, 60);
		printer_draw_text($enlace, '           '.strtoupper($dato_mesa[0]->mesa)."     |     ".$usuario_venta[0], 1, 90);
		printer_draw_text($enlace, '--------------------------------------------------', 1, 120);
		
		foreach($array_prod_coman as $lis)
		{
			$cum += $sum;
			printer_draw_text($enlace, $lis, 1, $cum);
		}
		printer_draw_text($enlace, '--------------------------------------------------', 1, ($cum+30));
		printer_draw_text($enlace, '              Atender por favor                   ', 1, ($cum+60));
		
		printer_delete_font($font);
		printer_end_page($enlace);
		printer_end_doc($enlace);
	
		printer_close($enlace);
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

			//$cuerpo_tck = "<pre>\n\n\n";
			$cuerpo_tck = "<pre>\n";
			$cuerpo_tck .= "   	   ".$this->g_nombre_corto."\n";
			$cuerpo_tck .= "	    ".$this->g_razon_social."\n";
			$cuerpo_tck .= "	    RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "	 ".$this->g_direccion."\n";
			$cuerpo_tck .= "	".$this->g_distrito." - ".$this->g_ciudad." - ".$this->g_ciudad."\n\n";

			$cuerpo_tck .= "Tck#: PRE VENTA        ".mdate("%d/%m/%y", time()).' '.$hora_fin."\n";
			$cuerpo_tck .= "----------------------------------------\n";
			$cuerpo_tck .= "   Descripcion        CantP.Unit P.Total\n";
			$cuerpo_tck .= "----------------------------------------\n";

			$precio_total = $precio_total_venta = 0;
			foreach ($lis_tv_deta as $lis)
			{
				$len_prod = strlen($lis->nombre);
				$len_cant = strlen($lis->cantidad);
				$len_venta = strlen($lis->venta);

				$precio_total = ($lis->cantidad * $lis->venta);
				$len_total = strlen(number_format($precio_total, 2));

				// PRODUCTO
				if($len_prod <= 6)
					$text_producto = $lis->nombre."\t\t       ";
				elseif($len_prod >= 6 && $len_prod <= 14)
					$text_producto = $lis->nombre."\t       ";
				else
					$text_producto = substr($lis->nombre, 0, 15)."\t       ";

				// CANTIDAD
				if($len_cant == 1)
					$text_cant = " ".$lis->cantidad;
				else
					$text_cant = $lis->cantidad;

				// VENTA
				if($len_venta == 4)
					$text_venta = "   ".$lis->venta;
				elseif($len_venta == 5)
					$text_venta = "  ".$lis->venta;
				else
					$text_venta = " ".$lis->venta;

				// TOTAL
				if($len_total == 4)			//8.00
					$text_total = "    ".number_format($precio_total, 2);
				elseif($len_total == 5)		//18.00
					$text_total = "   ".number_format($precio_total, 2);
				elseif($len_total == 6)		//180.00
					$text_total = "  ".number_format($precio_total, 2);
				else 						//1800.00
					$text_total = " ".number_format($precio_total, 2);

				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";

				$precio_total_venta += $precio_total;
			}
			$cuerpo_tck .= "----------------------------------------\n";

			// Formula correcta de Calculos:
			$mas_igv = ((100 + $this->g_igv) / 100); // Obtiene Ejm: 1.18
			$subtotal_venta = ($precio_total_venta /  $mas_igv);
			$total_igv = ($precio_total_venta - $subtotal_venta);
			// --

			// TOTAL NETO
			$len_neto = strlen(number_format($subtotal_venta, 2));
			if($len_neto == 4)			//8.00
				$total_neto = "    ".number_format($subtotal_venta, 2);
			elseif($len_neto == 5)		//18.00
				$total_neto = "   ".number_format($subtotal_venta, 2);
			elseif($len_neto == 6)		//180.00
				$total_neto = "  ".number_format($subtotal_venta, 2);
			else 						//1800.00
				$total_neto = " ".number_format($subtotal_venta, 2);

			// IGV
			$len_igv = strlen(number_format($total_igv, 2));
			if($len_igv == 4)
				$igv = "    ".number_format($total_igv, 2);
			elseif($len_igv == 5)
				$igv = "   ".number_format($total_igv, 2);
			elseif($len_igv == 6)
				$igv = "  ".number_format($total_igv, 2);
			else
				$igv = " ".number_format($total_igv, 2);

			// TOTAL VENTA
			$len_total = strlen(number_format($precio_total_venta, 2));
			if($len_total == 4)
				$total_venta = "    ".number_format($precio_total_venta, 2);
			elseif($len_total == 5)
				$total_venta = "   ".number_format($precio_total_venta, 2);
			elseif($len_total == 6)
				$total_venta = "  ".number_format($precio_total_venta, 2);
			else
				$total_venta = " ".number_format($precio_total_venta, 2);

			$cuerpo_tck .= "\t\t Total Neto   ".$moneda_ticket.$total_neto."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "\t\t        IGV   ".$moneda_ticket.$igv."\n";
			$cuerpo_tck .= "\t\t      TOTAL   ".$moneda_ticket.$total_venta."\n";
			$cuerpo_tck .= "----------------------------------------\n";
			//$cuerpo_tck .= "  	   Cajero(a): ".strtoupper($usuario_venta[0])."\n";
			$cuerpo_tck .= "  	 Atendido por: ".strtoupper($usuario_venta[0])."\n";
			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			$this->printerTCK($nv_cuerpo_tck, $this->g_ruta_printer);
			//echo $cuerpo_tck;
		// --
	}

	public function printerTCK($nv_cuerpo_tck, $ruta_printer)
	{
		$enlace = printer_open($ruta_printer);
		printer_write($enlace, $nv_cuerpo_tck);
		printer_close($enlace);
	}

	public function generarCodVenta($id_serie)
	{
		$lis_serie_doc = $this->series_documentos_model->ver($id_serie);
		$serie_doc = $lis_serie_doc[0]->serie;
		$tipo_doc = $lis_serie_doc[0]->tipo_doc;

		// --
		/*
		if($tipo_doc == 'FAC')
			$col_num_doc = 'num_fac_ven';
		elseif($tipo_doc == 'TCK')
			$col_num_doc = 'num_tck_ven';
		elseif($tipo_doc == 'CRT')
			$col_num_doc = 'num_crt_ven';
		*/
		$lis_global = $this->globales_model->ver($this->g_id_global);
		$cod_max = $this->tpv_model->generarCodMax($id_serie); //tipo_doc

		if($lis_global[0]->num_ven !== $cod_max) // TOMA EL CODIGO GLOBALES
		{
			$cod = $lis_global[0]->num_ven; //Antes 001-01-006964 = Ahora 006964

			//$serie_doc = substr($cod, 0, 3);
			//$num = substr($cod, -6);
			$num = $cod;
			$num = $num + 1;
			if($num <= 9)
				$num_oc = $serie_doc.'-01-00000'.$num;	//$num_oc = $tipo_doc.'-'.$serie_doc.'-00000'.$num;
			elseif($num >=9 && $num <= 99)
				$num_oc = $serie_doc.'-01-0000'.$num;
			elseif($num >=99 && $num <= 999)
				$num_oc = $serie_doc.'-01-000'.$num;
			elseif($num >=999 && $num <= 9999)
				$num_oc = $serie_doc.'-01-00'.$num;
			elseif($num >=9999 && $num <= 99999)
				$num_oc = $serie_doc.'-01-0'.$num;
			elseif($num >=99999 && $num <= 999999)
				$num_oc = $serie_doc.'-01-'.$num;
		}
		else
		{
			//$cod = $this->tpv_model->generarCodMax($id_serie); //tipo_doc
			//$cod = FAC-001-000001
			//		 001-01-006964
			//$num = substr($cod, -6); // -7
			$num = $cod_max; // -7
			$num = $num + 1;
			if($num <= 9)
				$num_oc = $serie_doc.'-01-00000'.$num;	//$tipo_doc.'-'.$serie_doc.'-00000'.$num;
			elseif($num >=9 && $num <= 99)
				$num_oc = $serie_doc.'-01-0000'.$num;
			elseif($num >=99 && $num <= 999)
				$num_oc = $serie_doc.'-01-000'.$num;
			elseif($num >=999 && $num <= 9999)
				$num_oc = $serie_doc.'-01-00'.$num;
			elseif($num >=9999 && $num <= 99999)
				$num_oc = $serie_doc.'-01-0'.$num;
			elseif($num >=99999 && $num <= 999999)
				$num_oc = $serie_doc.'-01-'.$num;
			//elseif($num >=999999 && $num <= 9999999)
			//	$num_oc = $tipo_doc.'-'.$serie_doc.'-'.$num;
		}
		// --

		return $num_oc;
	}

	public function generarVenta()
	{
		$id_tmp_cab = $this->input->post('id_tmp_cab');
		$doc_pago = $this->input->post('doc_pago');
		$tipo_pago = $this->input->post('tipo_pago');
			$tipo_pago_dif = $this->input->post('tipo_pago_dif');
		$total_venta = $this->input->post('total_venta');
		$pago_cliente = $this->input->post('pago_cliente');
		$vuelto_cliente = $this->input->post('vuelto_cliente');

		$fecha_registro = mdate("%Y-%m-%d", time());
		$hora_fin = mdate("%H:%i:%s", time());

		$cliente_venta = $this->input->post('id_cliente');
		//$cliente_venta = '';

		$num_doc = $this->generarCodVenta($doc_pago);

		if($cliente_venta)
		{
			$id_cliente = $cliente_venta;
			$cliente_activo = 'ok';
		}
		else
			$id_cliente = 0;


		$lis_serie_doc = $this->series_documentos_model->ver($doc_pago);
		$id_serie = $lis_serie_doc[0]->id_serie;
		$tipo_doc = $lis_serie_doc[0]->tipo_doc;

		if($id_serie == 7) //Es cortesía
			$total_venta = 0;

		// Formula correcta de Calculos:
		$mas_igv = ((100 + $this->g_igv) / 100); // Obtiene Ejm: 1.18
		$subtotal_venta = ($total_venta /  $mas_igv);
		$total_igv = ($total_venta - $subtotal_venta);
		// --

		$des_tmppventa = $this->tpv_model->listarTMPPuntoVenta($id_tmp_cab);
		$costo_prod = 0;
		foreach ($des_tmppventa as $value){
			$lista = $this->productos_model->buscarProducto($value->id_producto);
			$costo = ($lista[0]->precio_insumo * $value->cantidad);
			$costo_prod += $costo;
		}

		$data = array(
						'num_doc' => $num_doc,
							'subtotal_venta' => $subtotal_venta,
							'igv' => $total_igv,
						'costo' => $costo_prod,
							'desc_venta' => $tipo_pago_dif, // Guarda el id_tarjeta del Pago Diferido / Mixto.
							'total_venta' => $total_venta,
						'pago_cliente' => $pago_cliente,
						//'pago_billete' => ,
						'vuelto' => $vuelto_cliente,
						'tc' => $this->g_tc,
						'moneda' => $this->g_moneda,
						'id_cliente' => $id_cliente,
						'id_tp' => $tipo_pago,
						'id_serie' => $doc_pago,
						'id_tmp_cab' => $id_tmp_cab,
							'estado' => 'D',
						'fecha_registro' => $fecha_registro,
						'id_owner' => $this->session->userdata('person_id')
					);
		$id_transac = $this->tpv_model->insertarTransacVenta($data);

		$total = 0;
		foreach ($des_tmppventa as $value)
		{
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
		}

		// Proceso "Grabar en gs_globales el num_doc Venta"
			//$num_doc = FAC-001-000001 por este:
			//$num_doc = 001-01-006964
			/*
			if($tipo_doc == 'FAC')
				$col_num_doc = 'num_fac_ven';
			elseif($tipo_doc == 'TCK')
				$col_num_doc = 'num_tck_ven';
			elseif($tipo_doc == 'CRT')
				$col_num_doc = 'num_crt_ven';
			*/
			//$this->globales_model->actualizar($this->g_id_global, array( $col_num_doc => $num_doc));
			$this->globales_model->actualizar($this->g_id_global, array( 'num_ven' => substr($num_doc, -6)));
		// --

		// Proceso "Decremento de Almacen por Insumos del Productos"
			$this->actualizarVenta($id_tmp_cab);
		// --

		// Proceso "Cerrar mesas y venta Temporal"
			$data = array(
							'hora_fin' => $hora_fin,
							'total_venta' => $total_venta,
							'estado' => 'C'
					);
			$this->tpv_model->actualizarTMPTpvCab($data, $id_tmp_cab);
		// --

		// Proceso "Imprimir el TICKET"
			$lis_tv = $this->tpv_model->listarTransacVentaCAB($id_transac);
			$lis_tv_deta = $this->tpv_model->listarTransacVentaDetalle($id_transac);
			$lis_t_pago = $this->tpv_model->verTipoPago($tipo_pago);
			$lis_user_venta = $this->tpv_model->verUsuarioVenta($id_tmp_cab);
			$usuario_venta = explode(' ', $lis_user_venta[0]->first_name);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "SOLES"

			//$cuerpo_tck = "<pre>\n\n\n";
			
			$cuerpo_tck = "<pre>";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "   	   ".$this->g_nombre_corto."\n";
			$cuerpo_tck .= "   ".$this->g_razon_social."\n";
			$cuerpo_tck .= "	    RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "	 ".$this->g_direccion."\n";
			$cuerpo_tck .= "	".$this->g_distrito." - ".$this->g_ciudad." - ".$this->g_ciudad."\n\n";

			$cuerpo_tck .= "Tck#: ".$num_doc."    ".mdate("%d/%m/%y", time()).' '.$hora_fin."\n";
			$cuerpo_tck .= "========================================\n";

			if(@$cliente_activo == 'ok')
			{
				$lis_cliente = $this->tpv_model->verClienteVenta($id_cliente);
				$cuerpo_tck .= "RUC     : ".$lis_cliente[0]->nro_doc."\n";
				$cuerpo_tck .= "Cliente : ".$lis_cliente[0]->razon_social."\n";
				$cuerpo_tck .= "========================================\n";
			}

			//$cuerpo_tck .= "----------------------------------------\n";
			$cuerpo_tck .= "   Descripcion        CantP.Unit P.Total\n";
			$cuerpo_tck .= "========================================\n";
			foreach ($lis_tv_deta as $lis)
			{
				$len_prod = strlen($lis->producto);
				$len_cant = strlen($lis->cantidad);
				$len_venta = strlen($lis->venta);
				$len_total = strlen($lis->total);

				// PRODUCTO
				if($len_prod <= 6)
					$text_producto = $lis->producto."\t\t       ";
				elseif($len_prod >= 6 && $len_prod <= 14)
					$text_producto = $lis->producto."\t       ";
				else
					$text_producto = substr($lis->producto, 0, 15)."\t       ";

				// CANTIDAD
				if($len_cant == 1)
					$text_cant = " ".$lis->cantidad;
				else
					$text_cant = $lis->cantidad;

				// VENTA
				if($len_venta == 4)
					$text_venta = "   ".$lis->venta;
				elseif($len_venta == 5)
					$text_venta = "  ".$lis->venta;
				else
					$text_venta = " ".$lis->venta;

				// TOTAL
				if($len_total == 4)			//8.00
					$text_total = "    ".$lis->total;
				elseif($len_total == 5)		//18.00
					$text_total = "   ".$lis->total;
				elseif($len_total == 6)		//180.00
					$text_total = "  ".$lis->total;
				else 						//1800.00
					$text_total = " ".$lis->total;

				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";
			}
			$cuerpo_tck .= "========================================\n";

			// TOTAL NETO
			$len_neto = strlen($lis_tv[0]->subtotal_venta);
			if($len_neto == 4)			//8.00
				$total_neto = "    ".$lis_tv[0]->subtotal_venta;
			elseif($len_neto == 5)		//18.00
				$total_neto = "   ".$lis_tv[0]->subtotal_venta;
			elseif($len_neto == 6)		//180.00
				$total_neto = "  ".$lis_tv[0]->subtotal_venta;
			else 						//1800.00
				$total_neto = " ".$lis_tv[0]->subtotal_venta;

			// IGV
			$len_igv = strlen($lis_tv[0]->igv);
			if($len_igv == 4)
				$igv = "    ".$lis_tv[0]->igv;
			elseif($len_igv == 5)
				$igv = "   ".$lis_tv[0]->igv;
			elseif($len_igv == 6)
				$igv = "  ".$lis_tv[0]->igv;
			else
				$igv = " ".$lis_tv[0]->igv;

			// TOTAL VENTA
			$len_total = strlen($lis_tv[0]->total_venta);
			if($len_total == 4)
				$total_venta = "    ".$lis_tv[0]->total_venta;
			elseif($len_total == 5)
				$total_venta = "   ".$lis_tv[0]->total_venta;
			elseif($len_total == 6)
				$total_venta = "  ".$lis_tv[0]->total_venta;
			else
				$total_venta = " ".$lis_tv[0]->total_venta;

			// PAGO CLIENTE
			$len_pcliente = strlen($lis_tv[0]->pago_cliente);
			if($len_pcliente == 4)
				$pago_cliente = "    ".$lis_tv[0]->pago_cliente;
			elseif($len_pcliente == 5)
				$pago_cliente = "   ".$lis_tv[0]->pago_cliente;
			elseif($len_pcliente == 6)
				$pago_cliente = "  ".$lis_tv[0]->pago_cliente;
			else
				$pago_cliente = " ".$lis_tv[0]->pago_cliente;

			// VUELTO
			$len_vuelto = strlen($lis_tv[0]->vuelto);
			if($len_vuelto == 4)
				$vuelto = "    ".$lis_tv[0]->vuelto;
			elseif($len_vuelto == 5)
				$vuelto = "   ".$lis_tv[0]->vuelto;
			elseif($len_vuelto == 6)
				$vuelto = "  ".$lis_tv[0]->vuelto;
			else
				$vuelto = " ".$lis_tv[0]->vuelto;

			if($lis_t_pago[0]->id_tp == 1)
				$text_tipo_pago = "\t\t   EFECTIVO   ";
			elseif($lis_t_pago[0]->id_tp == 2)
				$text_tipo_pago = "\t\t       VISA   ";
			elseif($lis_t_pago[0]->id_tp == 3)
				$text_tipo_pago = "\t\t MASTERCARD   ";
			elseif($lis_t_pago[0]->id_tp == 4)
				$text_tipo_pago = "\t\tDINERS CLUB   ";
			elseif($lis_t_pago[0]->id_tp == 5)
				$text_tipo_pago = "\t   AMERICAN EXPRESS   ";
			else
				$text_tipo_pago = "\t EFECTIVO Y TARJETA   ";

			$cuerpo_tck .= "\t\t Total Neto   ".$moneda_ticket.$total_neto."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "\t\t        IGV   ".$moneda_ticket.$igv."\n";
			$cuerpo_tck .= "\t\t      TOTAL   ".$moneda_ticket.$total_venta."\n";

			if($id_serie <> 7)
			{
				$cuerpo_tck .= "========================================\n";
				if($lis_t_pago[0]->id_tp == 6) // Pago Diferido o Mixto
				{
					$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$total_venta."\n";
				}
				else
					$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$pago_cliente."\n";
			}

			if($lis_t_pago[0]->id_tp == 1 && $id_serie <> 7) // 7 = Cortesia
				$cuerpo_tck .= "\t\t     Cambio   ".$moneda_ticket.$vuelto."\n";

			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "  	   Cajero(a): ".strtoupper($usuario_venta[0])."\n";

			if($id_serie == 7)
				$cuerpo_tck .= "               CORTESIA                 \n";

			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";
			//$cuerpo_tck .= "	  Feliz dia de la Madre\n";
			//$cuerpo_tck .= "	Estamos de aniversario,\n\tlleve nuestros productos.";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			$this->printerTCK($nv_cuerpo_tck, $this->g_ruta_printer);
			//echo $cuerpo_tck;
		// --
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
											'fecha_modifica' => mdate("%Y-%m-%d", time())
											//'id_owner' => $this->session->userdata('person_id')
										);
					$this->almacen_model->actualizarAlmacenServicio($lis->id_serv_prov, $data_alm);
				// --
			}
		}
	}


	public function listarMesas()
  {
      $fecha = mdate("%Y-%m-%d", time());
      $lis_mesas = $this->tpv_model->listarMesas();

			echo '<h3 style="margin-top:10px;">Seleccione una Sala!</h3>';
			foreach($lis_mesas as $lis)
      {
          @$lis_pv_cab = $this->tpv_model->verDatoPVCab($fecha, 'P', $lis->id_mesa);
          if(@$lis_pv_cab[0]->id_mesa == $lis->id_mesa)
          {
                  $dato_empleado = $this->tpv_model->verEmpleado(@$lis_pv_cab[0]->id_emple);
          ?>
						<button id="btnmesa_<?=$lis->id_mesa?>" class="col-xs-3 btn-danger text-center cls_mesas" style="margin: 0px; font-size: 17px; height: 82px;" onclick="identificarMesaReservada('<?=@$lis_pv_cab[0]->id_emple?>', '<?=$lis->id_mesa?>');">
	                <?=$lis->mesa?><br />
	                <small style="font-size: 13px;"><?=$dato_empleado[0]->first_name?></small>
	        	</button>
	<?php   }
	        else
	        { ?>
	        	<button id="btnmesa_<?=$lis->id_mesa?>" class="col-xs-3 btn-success text-center cls_mesas" style="margin: 0px; font-size: 18px; height: 82px;" onclick="identificarMesa('<?=$lis->id_mesa?>');"><?=$lis->mesa?></button>
	<?php   }
			}
  }

	public function identificarEmpleadoMesa()
	{
		$fecha = mdate("%Y-%m-%d", time());
		$session['id_emple'] = $this->input->post('id_emple');
		$this->session->set_userdata($session);
	}

	public function identificarMesa()
	{
		$id_emple = $this->session->userdata('id_emple');
		$hora_ini = mdate("%H:%i:%s", time());
		$fecha = mdate("%Y-%m-%d", time());
		$id_mesa = $this->input->post('id_mesa');

		//$this->tpv_model->actualizarEvento(array('evento_1' => '0'), $fecha);
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
		print json_encode($array);
	}

	public function identificarMesaReservada()
	{
		$id_emple = $this->input->post('id_emple');
		$id_mesa = $this->input->post('id_mesa');
		$fecha = mdate("%Y-%m-%d", time());

		$lis_pv_cab = $this->tpv_model->verDatoPVCab($fecha, 'P', $id_mesa, $id_emple);
		$dato_empleado = $this->tpv_model->verEmpleado($id_emple);
		$dato_mesa = $this->tpv_model->listarMesas($id_mesa);

		// Calcula el Total de Venta por Mesa
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($lis_pv_cab[0]->id_tmp_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		foreach($lis_tmp_pventa as $lis){
			$precio_total = ($lis->cantidad * $lis->venta);
			$precio_total_venta += $precio_total;
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

		$con_tmp = 0;
		foreach ($lis_tmp_pventa as $con => $lis)
			$con_tmp = $con;

		$precio_total = 0;
		print '{';
						foreach ($lis_tmp_pventa as $key => $lis)
						{
							$precio_total = ($lis->cantidad * $lis->venta);
							//print '"Objeto '.$key.'":';
							print '"Objeto '.$key.'":';
							print '{
												"tipo" : "producto",
												"id_tmp_cab" : "'.$lis->id_tmp_cab.'",
												"correlativo" : "'.$lis->correlativo.'",
												"cantidad" : "'.$lis->cantidad.'",
												"nombre" : "'.$lis->nombre.'",
												"precio_unitario" : "'.$lis->venta.'",
												"precio_total" : "'.number_format($precio_total, 2).'"
											}';

								// Notas Comanda
								if(trim($lis->nota_comanda) != '')
								{
									print ',';
									$data_c = explode("|", trim($lis->nota_comanda));
									$c_tmp = 0;
									foreach ($data_c as $c => $l)
										$c_tmp = $c;

									foreach ($data_c as $k => $l)
									{
										print '"Objeto '.$key.'-'.$k.'":';
										$d_n_c = explode(":", $l);
										print '{
												"tipo" : "nota_comanda",
												"id_tmp_cab" : "'.$lis->id_tmp_cab.'",
												"correlativo" : "'.$lis->correlativo.'",
												"id_nota_comanda" : "'.$d_n_c[0].'",
												"cantidad" : "-",
												"nombre" : "'.$d_n_c[1].'",
												"precio_unitario" : "-",
												"precio_total" : "-"
											}';
											if($c_tmp <> $k) print ',';
									}
								}
								// --
							if($con_tmp <> $key) print ',';
						}
		print '}';
		//ejemplo:
		/*
			{
			"Objeto 0:"
				{
					"id_tmp_cab" : "123",
					"correlativo" : "1",
					"cantidad" : "2"
				}
			}
		*/
	}

	public function eliminarProdTMPTpv()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_cab = $cod_array[0];
		$correlativo = $cod_array[1];
		$this->tpv_model->eliminarProdTMPTpv($id_cab, $correlativo);

		// Calcula el Total de Venta por Mesa
		$lis_tmp_pventa = $this->tpv_model->listarTMPPuntoVenta($id_cab);
		$precio_total = 0;
		$precio_total_venta = 0;
		foreach($lis_tmp_pventa as $lis){
			$precio_total = ($lis->cantidad * $lis->venta);
			$precio_total_venta += $precio_total;
		}
		// --
		$array = array(
					array(
				   		'precio_total_venta' => number_format($precio_total_venta, 2)
				   	)
			 	);
		print json_encode($array);
	}

	public function eliminarNotaComandaTMPTpv()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_cab = $cod_array[0];
		$correlativo = $cod_array[1];
		$id_nota_comanda = $cod_array[2];

		$des_tmp = $this->tpv_model->verTMPPuntoVenta($id_cab, $correlativo);
		foreach ($des_tmp as $key => $value)
		{
			// --
			$data_c = explode("|", trim($value->nota_comanda)); // array(1:SIN SAL, 2:CALIENTE)
			$borrar_nota = array_search((int)$id_nota_comanda, $data_c);
			unset($data_c[(int)$borrar_nota]);
			$nota_comanda = implode("|", $data_c);
			// --

			$data = array('nota_comanda' => $nota_comanda);
			$this->tpv_model->actualizarTMPPuntoVenta($id_cab, $correlativo, $data);
		}
		// --
		$data_update = array(
							'estado' => 0,
							'date_updated' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
							'persona_id_updated' => $this->session->userdata('person_id')
						);
		$this->tpv_model->actualizarNotaComandaProd($id_nota_comanda, $data_update);
		// --
		print '[{"estado":"ok"}]';
	}

	public function borraVentaProducto()
	{
		$this->tpv_model->eliminarTMPTpv($this->input->post('id_tmp_cab'));
		$this->tpv_model->eliminarTMPTpvCab($this->input->post('id_tmp_cab'));

		$hora = mdate("%H:%i:%s", time());
		$fecha = mdate("%Y-%m-%d", time());
		$data = array(
					'id_owner' => $this->session->userdata('id_emple'),
					'accion_log' => 'pv_suprimir_venta',
					'fecha_log' => $fecha.' '.$hora
					);
		$this->tpv_model->insertarLogUsuarios($data);
	}

	public function verVentasXDia()
	{
		// Buscar datos para actualizar
		$lis_ventas = $this->ventas_model->verVentasXDia($this->fecha_actual);
		//$lis_ventas = $this->ventas_model->verVentasXDia('2017-01-23');
		$id_emple = $this->session->userdata('id_emple');
	?>
		<div class="row">
            <div class="panel panel-default custom"><span class="glyphicon glyphicon-usd"></span> LISTADO DE VENTA DIARIA</div>
            <div class="col-md-12" style="background: #FFF;">
         		 <div class="table-responsive" id="tabla_personal2"  style="overflow-y: scroll; height: 412px;">
                    <table id="datos_tabla_ajax" class="table table-striped" cellspacing="0" width="100%">
			        <thead>
			          <tr>
			            <th style=""></th>
		                  <th># VTA</th>
		                  <th>BARISTA</th>
		                  <th>MESA</th>
		                  <th>TP</th>
		                  <th>CIERRE</th>
		                  <th>NETO (<?=$this->g_moneda?>)</th>
		                  <th>IGV (<?=$this->g_moneda?>)</th>
		                  <th>TOTAL (<?=$this->g_moneda?>)</th>
			          </tr>
			        </thead>
			        <tbody>
			          <?php $total_venta = 0;
			          		if($lis_ventas):
				          		foreach($lis_ventas as $i=>$lis): ?>
					            <tr style="">
					              <td>
					              		<a href="#" style="" onclick="reimprimirVentaTicket('<?=$lis->id_transac?>');"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-print"></span></a>
						            <!-- <a href="#" style=""  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a> -->
					              </td>
			                      <td><?=substr($lis->num_doc, -6)?></td>
			                      <td><?=$lis->username?></td>
			                      <td><?=$lis->alias?></td>
			                      <td><?=$lis->tipo_pago?></td>
			                      <?php $estado = ($lis->id_cierre)? 'C' : 'A';?>
			                      <td class="text-center"><?=$estado?></td>
			                      <td class="text-right"><?=$lis->subtotal_venta?></td>
			                      <td class="text-right"><?=$lis->igv?></td>
			                      <td class="text-right" style="font-weight: bold;"><?=$lis->total_venta?></td>
			                      <?php $total_venta += $lis->total_venta;?>
					            </tr>
				          <?php endforeach;
				          	else: ?>
				          		<tr style="">
					              <td style="text-align: center;" colspan="9">No ha generado todav&iacute;a ventas del d&iacute;a.</td>
					            </tr>
				      <?php endif; ?>
			        </tbody>
			      </table>
			  </div>

			  	  <div class="col-md-12" style="margin-top: 0px; padding-right: 0px;">
                      <div class="col-md-8" >&nbsp;</div>
                      <div class="col-md-4" style="padding: 0px;">
                              <table class="table table-striped" style="text-align: left;">
                                  <thead>
                                      <tr>
                                      	<th style="font-weight: bold; font-size: 18px;">TOTAL VENTA</th>
                                      	<th style="text-align: right; font-weight: bold; font-size: 18px;" id="c_subtotal"><?=$this->g_moneda.' '.number_format($total_venta, 2)?></th>
                                      </tr>
                                  </thead>
                              </table>
                      </div>
                  </div>

			</div>
		</div>

		<div class="row" style="margin-top: 10px;">
            <div class="col-xs-4 " >
              <div class="">
              	<?php 	if($id_emple){
              				$estado_btn_ict = 'enabled';
              				$evento_icc = "imprimirCierreCaja('".$id_emple."');";
              				$evento_ict = "imprimirCambioTurno('".$id_emple."');";
              			}else{
              				$estado_btn_ict = 'disabled';
              				$evento_icc = '';
              				$evento_ict = '';
              			}
              	?>

                <bottom class="btn btn-default " id="btncerrar_caja" onclick="<?=$evento_icc?>" <?=$estado_btn_ict?>><span class="glyphicon glyphicon-print"></span> PRINT CIERRE CAJA</bottom>
              </div>
            </div>
            <div class="col-xs-4 " >
              <div class="">
                <bottom class="btn btn-success " id="btnprint_cambio_turno" onclick="<?=$evento_ict?>" <?=$estado_btn_ict?>><span class="glyphicon glyphicon-print"></span> CAMBIO DE TURNO</bottom>
              </div>
            </div>
            <div class="col-xs-4" >
              <div>
                <bottom class="btn btn-danger btn-block"  id="btnretornarCarritoVenta_2" onclick="retornarCajaVenta();"><span class="glyphicon glyphicon-arrow-left"></span> RETORNAR</bottom>
              </div>
            </div>
        </div>
	<?php
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

			//$cuerpo_tck = "<pre>\n\n\n";
			$cuerpo_tck = "<pre>";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "   	   ".$this->g_nombre_corto."\n";
			$cuerpo_tck .= "	    ".$this->g_razon_social."\n";
			$cuerpo_tck .= "	    RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "	 ".$this->g_direccion."\n";
			$cuerpo_tck .= "	".$this->g_distrito." - ".$this->g_ciudad." - ".$this->g_ciudad."\n\n";

			$cuerpo_tck .= "Tck#: ".$lis_tv[0]->num_doc."    ".mdate("%d/%m/%y", time()).' '.mdate("%H:%i:%s", time())."\n";
			$cuerpo_tck .= "========================================\n";

			if($lis_tv[0]->id_cliente <> 0)
			{
				$lis_cliente = $this->tpv_model->verClienteVenta($lis_tv[0]->id_cliente);
				$cuerpo_tck .= "RUC     : ".$lis_cliente[0]->nro_doc."\n";
				$cuerpo_tck .= "Cliente : ".$lis_cliente[0]->razon_social."\n";
				$cuerpo_tck .= "========================================\n";
			}

			//$cuerpo_tck .= "----------------------------------------\n";
			$cuerpo_tck .= "   Descripcion        CantP.Unit P.Total\n";
			$cuerpo_tck .= "========================================\n";
			foreach ($lis_tv_deta as $lis)
			{
				$len_prod = strlen($lis->producto);
				$len_cant = strlen($lis->cantidad);
				$len_venta = strlen($lis->venta);
				$len_total = strlen($lis->total);

				// PRODUCTO
				if($len_prod <= 6)
					$text_producto = $lis->producto."\t\t       ";
				elseif($len_prod >= 6 && $len_prod <= 14)
					$text_producto = $lis->producto."\t       ";
				else
					$text_producto = substr($lis->producto, 0, 15)."\t       ";

				// CANTIDAD
				if($len_cant == 1)
					$text_cant = " ".$lis->cantidad;
				else
					$text_cant = $lis->cantidad;

				// VENTA
				if($len_venta == 4)
					$text_venta = "   ".$lis->venta;
				elseif($len_venta == 5)
					$text_venta = "  ".$lis->venta;
				else
					$text_venta = " ".$lis->venta;

				// TOTAL
				if($len_total == 4)			//8.00
					$text_total = "    ".$lis->total;
				elseif($len_total == 5)		//18.00
					$text_total = "   ".$lis->total;
				elseif($len_total == 6)		//180.00
					$text_total = "  ".$lis->total;
				else 						//1800.00
					$text_total = " ".$lis->total;

				$cuerpo_tck .= $text_producto.$text_cant.$text_venta.$text_total."\n";
			}
			$cuerpo_tck .= "========================================\n";

			// TOTAL NETO
			$len_neto = strlen($lis_tv[0]->subtotal_venta);
			if($len_neto == 4)			//8.00
				$total_neto = "    ".$lis_tv[0]->subtotal_venta;
			elseif($len_neto == 5)		//18.00
				$total_neto = "   ".$lis_tv[0]->subtotal_venta;
			elseif($len_neto == 6)		//180.00
				$total_neto = "  ".$lis_tv[0]->subtotal_venta;
			else 						//1800.00
				$total_neto = " ".$lis_tv[0]->subtotal_venta;

			// IGV
			$len_igv = strlen($lis_tv[0]->igv);
			if($len_igv == 4)
				$igv = "    ".$lis_tv[0]->igv;
			elseif($len_igv == 5)
				$igv = "   ".$lis_tv[0]->igv;
			elseif($len_igv == 6)
				$igv = "  ".$lis_tv[0]->igv;
			else
				$igv = " ".$lis_tv[0]->igv;

			// TOTAL VENTA
			$len_total = strlen($lis_tv[0]->total_venta);
			if($len_total == 4)
				$total_venta = "    ".$lis_tv[0]->total_venta;
			elseif($len_total == 5)
				$total_venta = "   ".$lis_tv[0]->total_venta;
			elseif($len_total == 6)
				$total_venta = "  ".$lis_tv[0]->total_venta;
			else
				$total_venta = " ".$lis_tv[0]->total_venta;

			// PAGO CLIENTE
			$len_pcliente = strlen($lis_tv[0]->pago_cliente);
			if($len_pcliente == 4)
				$pago_cliente = "    ".$lis_tv[0]->pago_cliente;
			elseif($len_pcliente == 5)
				$pago_cliente = "   ".$lis_tv[0]->pago_cliente;
			elseif($len_pcliente == 6)
				$pago_cliente = "  ".$lis_tv[0]->pago_cliente;
			else
				$pago_cliente = " ".$lis_tv[0]->pago_cliente;

			// VUELTO
			$len_vuelto = strlen($lis_tv[0]->vuelto);
			if($len_vuelto == 4)
				$vuelto = "    ".$lis_tv[0]->vuelto;
			elseif($len_vuelto == 5)
				$vuelto = "   ".$lis_tv[0]->vuelto;
			elseif($len_vuelto == 6)
				$vuelto = "  ".$lis_tv[0]->vuelto;
			else
				$vuelto = " ".$lis_tv[0]->vuelto;

			if($lis_t_pago[0]->id_tp == 1)
				$text_tipo_pago = "\t\t   EFECTIVO   ";
			elseif($lis_t_pago[0]->id_tp == 2)
				$text_tipo_pago = "\t\t       VISA   ";
			elseif($lis_t_pago[0]->id_tp == 3)
				$text_tipo_pago = "\t\t MASTERCARD   ";
			elseif($lis_t_pago[0]->id_tp == 4)
				$text_tipo_pago = "\t\tDINERS CLUB   ";
			elseif($lis_t_pago[0]->id_tp == 5)
				$text_tipo_pago = "\t   AMERICAN EXPRESS   ";
			else
				$text_tipo_pago = "\t EFECTIVO Y TARJETA    ";

			$cuerpo_tck .= "\t\t Total Neto   ".$moneda_ticket.$total_neto."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "\t\t        IGV   ".$moneda_ticket.$igv."\n";
			$cuerpo_tck .= "\t\t      TOTAL   ".$moneda_ticket.$total_venta."\n";

			if($id_serie <> 7)
			{
				$cuerpo_tck .= "========================================\n";
				$cuerpo_tck .= $text_tipo_pago.$moneda_ticket.$pago_cliente."\n";
			}

			if($lis_t_pago[0]->id_tp == 1 && $id_serie <> 7) // 7 = Cortesia
				$cuerpo_tck .= "\t\t     Cambio   ".$moneda_ticket.$vuelto."\n";

			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "  	   Cajero(a): ".strtoupper($usuario_venta[0])."\n";

			if($id_serie == 7)
				$cuerpo_tck .= "               CORTESIA                 \n";

			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";
			//$cuerpo_tck .= "	  Feliz dia de la Madre\n";
			//$cuerpo_tck .= "	Estamos de aniversario,\n\tlleve nuestros productos.";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			$this->printerTCK($nv_cuerpo_tck, $this->g_ruta_printer);
			//echo $cuerpo_tck;
		// --
	}


	public function imprimirCambioTurno()
	{
		$id_emple = $this->input->post('id_emple');
		$fecha_cierre = mdate("%Y-%m-%d", time());
		$hora_cierre = mdate("%H:%i:%s", time());

		$turno = $this->ventas_model->verTurnoCaja($fecha_cierre);
		$turno = $turno + 1;

		$total_venta_turno = $this->ventas_model->verTotalVentaXTurto($fecha_cierre);
		$lis_ventas = $this->ventas_model->verListaVentaCierreCaja($fecha_cierre);

		// Declaración de variables:
		$total_efectivo = $total_tarjetas = 0;
		foreach ($lis_ventas as $lis)
		{
			if($lis->id_tp == 1) // Efectivo
				$total_efectivo = $total_efectivo + $lis->total_venta;
			else 				 // Tarjetas
				$total_tarjetas = $total_tarjetas + $lis->total_venta;
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
						'id_emple' => $id_emple
					);
		$id_cierre_caja = $this->ventas_model->insertarCierreCaja($data);

		// Genera Codigo CAJA
			//$cod = CAJA-00000001
			$num = $id_cierre_caja;
			if($num <= 9)
				$num_caja = 'CAJA-0000000'.$num;
			elseif($num >=9 && $num <= 99)
				$num_caja = 'CAJA-000000'.$num;
			elseif($num >=99 && $num <= 999)
				$num_caja = 'CAJA-00000'.$num;
			elseif($num >=999 && $num <= 9999)
				$num_caja = 'CAJA-0000'.$num;
			elseif($num >=9999 && $num <= 99999)
				$num_caja = 'CAJA-000'.$num;
			elseif($num >=99999 && $num <= 999999)
				$num_caja = 'CAJA-00'.$num;
			elseif($num >=999999 && $num <= 9999999)
				$num_caja = 'CAJA-0'.$num;
			elseif($num >=9999999 && $num <= 99999999)
				$num_caja = 'CAJA-'.$num;
		// --

		// Proceso "Imprimir el TICKET"
			//$lis_t_pago = $this->tpv_model->verTipoPago($tipo_pago);
			$lis_user_venta = $this->tpv_model->verEmpleadoVenta($id_emple);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "S/"

			//$cuerpo_tck = "<pre>\n\n\n";
			$cuerpo_tck = "<pre>";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "   	   ".$this->g_nombre_corto."\n";
			$cuerpo_tck .= "	    ".$this->g_razon_social."\n";
			$cuerpo_tck .= "	    RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "	 ".$this->g_direccion."\n";
			$cuerpo_tck .= "	".$this->g_distrito." - ".$this->g_ciudad." - ".$this->g_ciudad."\n\n";

			$cuerpo_tck .= "Tck#: ".$num_caja."    ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			$cuerpo_tck .= "========================================\n";
			//$cuerpo_tck .= "----------------------------------------\n";
			$cuerpo_tck .= "      Reporte de Cambio de Turno        \n";
			$cuerpo_tck .= "      Cajero(a): ".strtoupper($lis_user_venta[0]->first_name)."\n";
			$cuerpo_tck .= "Turno: ".$turno."\n";
			$cuerpo_tck .= "Guia de Cierre: ".$id_cierre_caja."\n";
			$cuerpo_tck .= "Fecha Impresion: ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			// Aqui calculo de horas trabajadas en los turnos.
			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "PROMEDIOS:\n";

			$len_tot_tick = strlen($total_venta_turno);
			if($len_tot_tick == 1)
				$tot_ticket = "  ".$total_venta_turno;
			elseif($len_tot_tick == 2)
				$tot_ticket = " ".$total_venta_turno;
			else
				$tot_ticket = $total_venta_turno;

			$cuerpo_tck .= "Ticket Local:                        ".$tot_ticket."\n";
			$cuerpo_tck .= "Clientes Local:                      ".$tot_ticket."\n";
			// Aqui se puede adicionar los totales por Mesa
			$cuerpo_tck .= "========================================\n";

			$lis_ventas_tp = $this->ventas_model->verListaVentaGrupalesTP($fecha_cierre);
			if($lis_ventas_tp)
			{
				foreach ($lis_ventas_tp as $lis)
				{
					$lis_venta_reg = $this->ventas_model->verListaVentaXTPago($fecha_cierre, $lis->id_tp);
					$lis_t_pago = $this->tpv_model->verTipoPago($lis->id_tp);

					if($lis_t_pago[0]->id_tp == 1)
						$cuerpo_tck .= strtoupper($lis_t_pago[0]->tipo_pago)." (".$this->g_moneda.")\n";
					else
						$cuerpo_tck .= strtoupper($lis_t_pago[0]->tipo_pago)."\n";

					$cuerpo_tck .= "   Ticket#                      P.Total\n";

					$total_cobrado = 0;
					foreach ($lis_venta_reg as $lisd)
					{
						$len_total = strlen($lisd->total_venta);
						if($len_total == 4)			//8.00
							$text_total = "    ".$lisd->total_venta;
						elseif($len_total == 5)		//18.00
							$text_total = "   ".$lisd->total_venta;
						elseif($len_total == 6)		//180.00
							$text_total = "  ".$lisd->total_venta;
						else 						//1800.00
							$text_total = " ".$lisd->total_venta;

						if($lisd->estado == 'V') //Es cortesía
							$estado_ticket = "A";
						elseif($lisd->id_serie == 7)
							$estado_ticket = "C";
						else
							$estado_ticket = " ";

						$cuerpo_tck .= $lisd->num_doc."        ".$estado_ticket."         ".$text_total."\n";

						$total_cobrado = $total_cobrado + $lisd->total_venta;
					}

					// Total Cobrado
					$len_cobro = strlen(number_format($total_cobrado, 2));
					if($len_cobro == 4)			//8.00
						$total_cobro = "    ".number_format($total_cobrado, 2);
					elseif($len_cobro == 5)		//18.00
						$total_cobro = "   ".number_format($total_cobrado, 2);
					elseif($len_cobro == 6)		//180.00
						$total_cobro = "  ".number_format($total_cobrado, 2);
					else 						//1800.00
						$total_cobro = " ".number_format($total_cobrado, 2);

					$cuerpo_tck .= "                                        \n";
					$cuerpo_tck .= "Total Cobrado                ".$moneda_ticket.$total_cobro."\n";
					$cuerpo_tck .= "========================================\n";
				}

				foreach ($lis_ventas as $lis)
				{
					// Actualiza las ventas con el CODIGO de CIERRE CAJA!
						$this->tpv_model->actualizarTransacVenta(array('id_cierre' => $id_cierre_caja), $lis->id_transac);
					// --
				}
			}
			else
			{
				$cuerpo_tck .= "Sin Ventas                          0.00\n";
				$cuerpo_tck .= "========================================\n";
			}

			// TOTAL EFECTIVO
			$len_total_efectivo = strlen(number_format($total_efectivo, 2));
			if($len_total_efectivo == 4)			//8.00
				$total_efectivo = "    ".number_format($total_efectivo, 2);
			elseif($len_total_efectivo == 5)		//18.00
				$total_efectivo = "   ".number_format($total_efectivo, 2);
			elseif($len_total_efectivo == 6)		//180.00
				$total_efectivo = "  ".number_format($total_efectivo, 2);
			else 									//1800.00
				$total_efectivo = " ".number_format($total_efectivo, 2);

			// TOTAL TARJETAS
			$len_total_tarjetas = strlen(number_format($total_tarjetas, 2));
			if($len_total_tarjetas == 4)
				$total_tarjetas = "    ".number_format($total_tarjetas, 2);
			elseif($len_total_tarjetas == 5)
				$total_tarjetas = "   ".number_format($total_tarjetas, 2);
			elseif($len_total_tarjetas == 6)
				$total_tarjetas = "  ".number_format($total_tarjetas, 2);
			else
				$total_tarjetas = " ".number_format($total_tarjetas, 2);

			// TOTAL CAJA
			$len_total_caja = strlen(number_format($total_caja, 2));
			if($len_total_caja == 4)
				$total_caja = "    ".number_format($total_caja, 2);
			elseif($len_total_caja == 5)
				$total_caja = "   ".number_format($total_caja, 2);
			elseif($len_total_caja == 6)
				$total_caja = "  ".number_format($total_caja, 2);
			else
				$total_caja = " ".number_format($total_caja, 2);

			$cuerpo_tck .= "                RESUMEN                 \n";
			$cuerpo_tck .= "Efectivo Soles y Dolares     ".$moneda_ticket.$total_efectivo."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "Tarjetas                     ".$moneda_ticket.$total_tarjetas."\n";
			$cuerpo_tck .= "TOTAL CAJA                   ".$moneda_ticket.$total_caja."\n";
			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
			$this->printerTCK($nv_cuerpo_tck, $this->g_ruta_printer);
		  //echo $cuerpo_tck;
		// --
	}


	public function imprimirCierreCaja()
	{
		$id_emple = $this->input->post('id_emple');
		$fecha_cierre = mdate("%Y-%m-%d", time());
		//$fecha_cierre = '2017-01-23';
		$hora_cierre = mdate("%H:%i:%s", time());

		$total_ticket = $this->ventas_model->verTotalTicketCajaXDia($fecha_cierre);

		// Proceso "Imprimir el TICKET"
			$lis_user_venta = $this->tpv_model->verEmpleadoVenta($id_emple);
			$moneda_ticket = substr($this->g_moneda, 0, 2); // CORREGIR AQUI EN CASO SEA OTRA MONEDA QUE NO SEA "S/"

			//$cuerpo_tck = "<pre>\n\n\n";
			$cuerpo_tck = "<pre>";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "   	   ".$this->g_nombre_corto."\n";
			$cuerpo_tck .= "	    ".$this->g_razon_social."\n";
			$cuerpo_tck .= "	    RUC ".$this->g_ruc."\n";
			$cuerpo_tck .= "	 ".$this->g_direccion."\n";
			$cuerpo_tck .= "	".$this->g_distrito." - ".$this->g_ciudad." - ".$this->g_ciudad."\n\n";

			//$cuerpo_tck .= "Tck#: ".$num_caja."   ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			//$cuerpo_tck .= "========================================\n";

			$cuerpo_tck .= "         REPORTE CIERRE DE CAJA        \n";
			//$cuerpo_tck .= "      Cajero(a): ".strtoupper($lis_user_venta[0]->first_name)."\n";
			//$cuerpo_tck .= "Turno: ".$turno."\n";
			$cuerpo_tck .= "Guia de Cierre: Final\n";
			$cuerpo_tck .= "Fecha Impresion: ".mdate("%d/%m/%y", time()).' '.$hora_cierre."\n";
			// Aqui calculo de horas trabajadas en los turnos.
			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "PROMEDIOS:\n";

			$len_tot_tick = strlen($total_ticket);
			if($len_tot_tick == 1)
				$tot_ticket = "  ".$total_ticket;
			elseif($len_tot_tick == 2)
				$tot_ticket = " ".$total_ticket;
			else
				$tot_ticket = $total_ticket;

			$cuerpo_tck .= "Ticket Local:                        ".$tot_ticket."\n";
			$cuerpo_tck .= "Clientes Local:                      ".$tot_ticket."\n";
			// Aqui se puede adicionar los totales por Mesa
			$cuerpo_tck .= "========================================\n";

			$lis_ventas_tp = $this->ventas_model->verListaVentaGrupalesTPCaja($fecha_cierre);
			if($lis_ventas_tp)
			{
				foreach ($lis_ventas_tp as $lis)
				{
					$lis_venta_reg = $this->ventas_model->verListaVentaXTPagoCaja($fecha_cierre, $lis->id_tp);
					$lis_t_pago = $this->tpv_model->verTipoPago($lis->id_tp);

					$cuerpo_tck .= strtoupper($lis_t_pago[0]->tipo_pago).":\n";

					$con_ope = $total_cobrado = $total_operaciones = 0;
					foreach ($lis_venta_reg as $lisd)
					{
						$con_ope++;
						$total_cobrado = $total_cobrado + $lisd->total_venta;
					}
					$total_operaciones = $con_ope;

					// Total Operaciones
					$len_ope = strlen($total_operaciones);
					if($len_ope == 1)			//8
						$total_ope = "  ".$total_operaciones;
					elseif($len_ope == 2)		//18
						$total_ope = " ".$total_operaciones;
					else 						//180
						$total_ope = $total_operaciones;

					// Total Cobrado
					$len_cobro = strlen(number_format($total_cobrado, 2));
					if($len_cobro == 4)			//8.00
						$total_cobro = "    ".number_format($total_cobrado, 2);
					elseif($len_cobro == 5)		//18.00
						$total_cobro = "   ".number_format($total_cobrado, 2);
					elseif($len_cobro == 6)		//180.00
						$total_cobro = "  ".number_format($total_cobrado, 2);
					else 						//1800.00
						$total_cobro = " ".number_format($total_cobrado, 2);

					$cuerpo_tck .= "                                        \n";
					$cuerpo_tck .= "Total operaciones:                  ".$total_ope."\n";
					$cuerpo_tck .= "Total Cobrado:               ".$moneda_ticket.$total_cobro."\n";
					$cuerpo_tck .= "Total Recibido:              ".$moneda_ticket.$total_cobro."\n";
					$cuerpo_tck .= "========================================\n";
				}
			}
			else
			{
				$cuerpo_tck .= "Sin Ventas                          0.00\n";
				$cuerpo_tck .= "========================================\n";
			}

			$lis_cc = $this->ventas_model->verTotalesCierreCaja($fecha_cierre);
			$total_efectivo = $lis_cc[0]->total_efectivo;
			$total_tarjetas = $lis_cc[0]->total_tarjetas;
			$total_caja = $lis_cc[0]->total_caja;

			// TOTAL EFECTIVO
			$len_total_efectivo = strlen($total_efectivo);
			if($len_total_efectivo == 4)			//8.00
				$total_efectivo = "    ".$total_efectivo;
			elseif($len_total_efectivo == 5)		//18.00
				$total_efectivo = "   ".$total_efectivo;
			elseif($len_total_efectivo == 6)		//180.00
				$total_efectivo = "  ".$total_efectivo;
			else 									//1800.00
				$total_efectivo = " ".$total_efectivo;

			// TOTAL TARJETAS
			$len_total_tarjetas = strlen($total_tarjetas);
			if($len_total_tarjetas == 4)
				$total_tarjetas = "    ".$total_tarjetas;
			elseif($len_total_tarjetas == 5)
				$total_tarjetas = "   ".$total_tarjetas;
			elseif($len_total_tarjetas == 6)
				$total_tarjetas = "  ".$total_tarjetas;
			else
				$total_tarjetas = " ".$total_tarjetas;

			// TOTAL CAJA
			$len_total_caja = strlen($total_caja);
			if($len_total_caja == 4)
				$total_caja = "    ".$total_caja;
			elseif($len_total_caja == 5)
				$total_caja = "   ".$total_caja;
			elseif($len_total_caja == 6)
				$total_caja = "  ".$total_caja;
			else
				$total_caja = " ".$total_caja;

			$cuerpo_tck .= "                RESUMEN                 \n";
			$cuerpo_tck .= "Efectivo Soles y Dolares     ".$moneda_ticket.$total_efectivo."\n"; //$lis_tv[0]->subtotal_venta
			$cuerpo_tck .= "Tarjetas                     ".$moneda_ticket.$total_tarjetas."\n";
			$cuerpo_tck .= "TOTAL DIA                    ".$moneda_ticket.$total_caja."\n";
			$cuerpo_tck .= "========================================\n";
			$cuerpo_tck .= "	 ".$this->g_firma_ticket."\n";

			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";
			$cuerpo_tck .= "                                        \n";

			$nv_cuerpo_tck = str_replace("<pre>","", $cuerpo_tck);
		  $this->printerTCK($nv_cuerpo_tck, $this->g_ruta_printer);
			//echo $cuerpo_tck;
		// --
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

}
