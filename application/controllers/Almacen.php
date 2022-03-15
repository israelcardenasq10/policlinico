<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Almacen extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('almacen_model');
    	$this->load->model('orden_compra_model');
    	$this->load->model('proveedores_model');
    	$this->load->model('compras_model');
    	$this->load->model('servicios_model');
    	$this->load->model('mermas_model');
    	$this->load->model('productos_model');

		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$data['lista_servicios'] = $this->servicios_model->listarServiciosXCat();
		$data['lista_categorias_serv'] = $this->servicios_model->listaCategorias();
		$data['lista_unidades'] = $this->compras_model->listarUnidades();
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		//echo "<br /><br />Cod: ".$this->generarCodOC();
		$data['lista'] = $this->almacen_model->listar();
		$this->load->view("almacen/main", $data);
	}

	public function insertar()
	{
		if($this->input->post('chktipo_almacen') == 'C')
            $tipo_almacen = 'C';
        else
            $tipo_almacen = 'A';

		$data = array(
				'id_prov' => $this->input->post('id_prov'),
				'id_serv_prov' => $this->input->post('id_serv_prov'),
				'id_unidad' => $this->input->post('id_unidad'),
				'cantidad' => $this->input->post('cantidad'),
				'unidad_medida' => $this->input->post('unidad_medida'),
				'costo' => $this->input->post('costo_serv'),
				'stock_min' => $this->input->post('stock_min'),
					'valor_porcion' => $this->input->post('valor_porcion'),
					'stock_porcion' => $this->input->post('stock_porcion'),
					'costo_porcion' => $this->input->post('costo_porcion'),
				'tipo_almacen' => $tipo_almacen,
				'fecha_registro' => mdate("%Y-%m-%d", time()),
				'id_owner' => $this->session->userdata('person_id')
			);
		$this->almacen_model->insertar($data);

		$this->servicios_model->actualizar($this->input->post('id_serv_prov'), array('descripcion' => 'almacen' ));
	}


	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->almacen_model->ver($id);
		//Muestra el User creador:
		$data['user_creador_data'] = $this->privilegios_model->getUserCreator($data['bus_dato'][0]->id_owner);
		//--
		$data['modo'] = 'actualizar';
		
		//$lis_servicio = $this->servicios_model->ver($data['bus_dato'][0]->id_serv_prov);
		//$data['id_cate_serv'] = $lis_servicio[0]->id_categoria;
		$data['lista_servicios'] = $this->servicios_model->listar();
		$data['lista_proveedor'] = $this->servicios_model->listarProveedoresXServ($data['bus_dato'][0]->id_serv_prov);

		$this->load->view("almacen/main", $data);
	}

	public function actualizar()
	{
		if($this->input->post('chktipo_almacen') == 'C')
            $tipo_almacen = 'C';
        else
            $tipo_almacen = 'A';
        
		$data = array(
				'id_prov' => $this->input->post('id_prov'),
				'id_serv_prov' => $this->input->post('id_serv_prov'),
				'id_unidad' => $this->input->post('id_unidad'),
				//'cantidad' => $this->input->post('cantidad'),
				'unidad_medida' => $this->input->post('unidad_medida'),
				'costo' => $this->input->post('costo_serv'),
				'stock_min' => $this->input->post('stock_min'),
					//'valor_porcion' => $this->input->post('valor_porcion'),
					'stock_porcion' => $this->input->post('stock_porcion'),
					'costo_porcion' => $this->input->post('costo_porcion'),
				'tipo_almacen' => $tipo_almacen,
				'fecha_modifica' => mdate("%Y-%m-%d", time()),
				'id_owner' => $this->session->userdata('person_id')
			);
		$this->almacen_model->actualizar($this->input->post('id_almacen'), $data);

		$this->servicios_model->actualizar($this->input->post('id_serv_prov'), array('descripcion' => 'almacen' ));
	}

	public function eliminar()
	{
		if($this->orden_compra_model->verCodAlmacenOC($this->input->post('id')) == true)
			echo 'error_existe_relacion';
		else
		{
			$lis_almacen = $this->almacen_model->ver($this->input->post('id'));
			$cantidad = $lis_almacen[0]->cantidad;
			if($cantidad == 0)
			{
				$this->almacen_model->eliminar($this->input->post('id'));
				$this->servicios_model->actualizar($this->input->post('id_serv_prov'), array('descripcion' => '' ));

				$data = array(
					'cantidad' => 0,
					'costo' => 0,
					'fecha_modifica' => mdate("%Y-%m-%d", time()),
					'id_owner' => $this->session->userdata('person_id')
				);
				$this->almacen_model->actualizar($this->input->post('id'), $data);
			}
			else
				echo 'error_cantidad';
		}
	}
	

	// -- PROCESO ORDEN DE COMPRA
	public function listarOrdenesCompra()
	{
		$data['modo'] = 'orden_compra';
		$data['lista_ordenes_compra'] = $this->orden_compra_model->listar();
		$this->load->view("almacen/main", $data);
	}

	public function generarCodOC()
	{
		$cod = $this->orden_compra_model->generarCodMax();
		//$cod = 'OC-8999999';
		$num = substr($cod, -7);
		$num = $num + 1;
		if($num <= 9)
			$num_oc = 'OC-000000'.$num;
		elseif($num >=9 && $num <= 99)
			$num_oc = 'OC-00000'.$num;
		elseif($num >=99 && $num <= 999)
			$num_oc = 'OC-0000'.$num;
		elseif($num >=999 && $num <= 9999)
			$num_oc = 'OC-000'.$num;
		elseif($num >=9999 && $num <= 99999)
			$num_oc = 'OC-00'.$num;
		elseif($num >=99999 && $num <= 999999)
			$num_oc = 'OC-0'.$num;
		elseif($num >=999999 && $num <= 9999999)
			$num_oc = 'OC-'.$num;
		return $num_oc;
	}

	public function insertarOrdenCompra()
	{
		//VERIFICAR Q SEA AUTOINCREMENT GS_ORDEN_PAGO!
		$num_oc = $this->generarCodOC();
		$fecha_registro = mdate("%Y-%m-%d", time());
		$data = array(
				'id_almacen' => $this->input->post('id_almacen'),
				'person_id' => $this->input->post('person_id'),
				'num_oc' => $num_oc,
				'estado' => 'P',
				'fecha_registro' => $fecha_registro,
				'id_owner' => $this->session->userdata('person_id')
			);
		$id_orden_compra = $this->orden_compra_model->insertarCab($data);
		
		$lis_servicios_prov = $this->almacen_model->listarServiciosXProvAlmacen($this->input->post('person_id'));

		if($this->input->post('rbtipocosto') == 'IGV')
			$igv = 'S';

		$correlativo = 0;
		$totales = 0;
		foreach($lis_servicios_prov as $i=>$lis)
  		{
  			$cantidad = $this->input->post('cantidad'.$lis->id_serv_prov);
  			if($cantidad <> 0 && $cantidad != '')
  			{
  				$correlativo = $correlativo + 1;
  				$precio = $this->input->post('precio'.$lis->id_serv_prov);
  				$total = $this->input->post('total'.$lis->id_serv_prov);

  				$data = array(
						'id_oc' => $id_orden_compra,
						'id_serv_prov' => $this->input->post('id_serv_prov'.$lis->id_serv_prov),
						'id_unidad' => $this->input->post('id_unidad'.$lis->id_serv_prov),
						'correlativo' => $correlativo,
						'cantidad' => $cantidad,
						'precio' => $precio,
						'inafecto' => 'N',
						'igv' => $igv,
						'total' => $total
					);
				$this->orden_compra_model->insertarDetalle($data);
				$totales = $totales + $total;
  			}
  		}

  		$this->orden_compra_model->actualizar($num_oc, array('total' => $totales));

  		$lis_servicios_prov = $this->orden_compra_model->listarServiciosXProvOC($this->input->post('person_id'), $num_oc);
  		$proveedor_serv = $this->input->post('hd_proveedor');
  		$user_creador_data = $this->privilegios_model->getUserCreator($this->session->userdata('person_id'));

  		$lis_proveedor = $this->orden_compra_model->verDatosProveedor($this->input->post('person_id'));
  		$ruc_proveedor = $lis_proveedor[0]->nro_doc;
  		$email_proveedor = $lis_proveedor[0]->email;
		//echo "<br><br><br><pre>";
		//print_r($this->input->post());
		//echo "</pre>";

  		// Genera Correo
  		if($id_orden_compra) //id_orden_compra
  		{
  			$mail = new PHPMailer();
  			//$this->My_PHPMailer->SMTPAuth   = false;
	        $mail->IsSMTP();
	        $mail->Mailer     = 'smtp';
	        $mail->SMTPAuth   = true;
	        $mail->Host       = 'smtp.gmail.com';
	        $mail->Port       = 587;
	        $mail->Username   = 'noreply.elgrancharlee@gmail.com';
	        $mail->Password   = '1234cincoX';

	        $mail->SetFrom($this->g_mail_envio, $this->g_mail_envio_alias);  //Quien envía el correo
	        $mail->AddReplyTo($this->g_mail_responde, $this->g_mail_responde_alias);  //A quien debe ir dirigida la respuesta
	        //$mail->AddReplyTo('info@cateperu.com', 'CATE PERU');

	        //$mail->AddAddress("contacto@cateperu.com", "Cate Tasting Room");
	        $mail->AddAddress($email_proveedor, $proveedor_serv);
	       	
	        $mail->AddCC($this->g_mail_copia,"");
	        $mail->AddBCC("ricardenas.developer@gmail.com","Israel Cardenas");
	        $mail->IsHTML(true);

	        $mail->Subject    = 'Orden de Compra Nro. '.$num_oc;
	        
	        $body_correo 	  = '<table border="0" width="700" style="font-family: trebuchet MS; border: 4px solid #F5F5F5; font-size: 12px;" cellpadding="2">
									  <tr>
									    <td style="text-align: center;">
									      <div style="margin: 7px; font-size: 28px">ORDEN DE COMPRA</div>
									      <div>'.$proveedor_serv.'</div>
									      <div style="margin: 5px; font-weight: bold;">'.$ruc_proveedor.'</div>
									    </td>
									  </tr>
									  <tr>
									    <td style="text-align: left;">
									      <table width="80%" border="0" cellpadding="1" style="border: 0px solid #CCCCCC; font-size: 12px;">
									        <tr>
									          <td width="19%" style="border: 0px solid #CCCCCC;">FECHA PEDIDO </td>
									          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
									          <td width="80%" style="border: 0px solid #CCCCCC;">'.$fecha_registro.'</td>
									        </tr>
									        <tr>
									          <td width="19%" style="border: 0px solid #CCCCCC;">ORDEN COMPRA </td>
									          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
									          <td width="80%" style="border: 0px solid #CCCCCC;">'.$num_oc.'</td>
									        </tr>
									        <tr>
									          <td width="19%" style="border: 0px solid #CCCCCC;">PEDIDO POR </td>
									          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
									          <td width="80%" style="border: 0px solid #CCCCCC;">'.$user_creador_data.'</td>
									        </tr>
									        <tr>
									          <td width="19%" style="border: 0px solid #CCCCCC;">ESTADO </td>
									          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
									          <td width="80%" style="border: 0px solid #CCCCCC;">Pendiente</td>
									        </tr>
									      </table>
									      <br/><br/>
									    </td>
									  </tr>
									  
									  <tr>
									    <td>
									      <table width="100%" border="0" cellpadding="3" style="border: 0px solid #CCCCCC; font-size: 12px;">
									        <tr style="font-weight: bold;">
									          <td width="4%" style="border: 1px solid #CCCCCC;">#</td>
									          <td width="40%" style="border: 1px solid #CCCCCC;">SERVICIO</td>
									          <td width="10%" style="border: 1px solid #CCCCCC;">UNIDAD</td>
									          <td width="10%" style="border: 1px solid #CCCCCC;">CANT.</td>
									          <td width="16%" style="border: 1px solid #CCCCCC;">COSTO</td>
									          <td width="20%" style="border: 1px solid #CCCCCC;">TOTAL</td>
									        </tr>';
                                       
                                       if($lis_servicios_prov)
                                       {
	                                       foreach($lis_servicios_prov as $c=>$lis)
	                                       { 
		                    $body_correo  	  .= '<tr>
										          <td width="4%" style="border: 1px solid #CCCCCC;">'.($c + 1).'</td>
										          <td width="40%" style="border: 1px solid #CCCCCC;">'.ucwords($lis->nombres).'</td>
										          <td width="10%" style="border: 1px solid #CCCCCC;">'.$lis->unidad.'</td>
										          <td width="10%" style="border: 1px solid #CCCCCC;">'.$lis->cantidad.'</td>
										          <td width="16%" style="border: 1px solid #CCCCCC;">'.$lis->precio.'</td>
										          <td width="20%" style="border: 1px solid #CCCCCC;">'.$lis->total.'</td>
										        </tr>';
	                                        }
                                    	}
								        
					$body_correo  	  .= '<tr>
								          <td width="20%" colspan="2" style="border: 0px solid #CCCCCC;"> Items : '.($c + 1).'</td>
								          <td width="80%" colspan="4" style="border: 0px solid #CCCCCC;"></td>
								        </tr>
								          
								        <tr>
								          <td width="20%" colspan="2" style="border: 0px solid #CCCCCC;"></td>
								          <td width="80%" colspan="4" style="border: 0px solid #CCCCCC; text-align: right; font-size: 21px">Total : '.$this->g_moneda.' '.number_format($totales, 2).'</td>
								        </tr> 
								      </table>
								    </td>
								  </tr>
								  <tr>
								    <td style="text-align: center;">
								      <img alt="" height="62" src="public/images/'.$this->g_logotipo.'" width="" /><br />
								      <label style="font-weight:bold; font-size: 15px;">Area de Almac&eacute;n '.$this->g_nombre_corto.'</label><br />
								      Tlf: 51+ '.$this->g_telefono.'<br />
								      '.$this->g_email.'<br />
								      <a href="'.$this->g_web.'" style="text-decoration: none;">'.$this->g_web.'</a>
								    </td>
								  </tr>
								  </table>';

			$mail->Body = $body_correo;
	        $mail->AltBody    = "";
	       	$mail->Send();
  		}
  		// --

		echo $num_oc;
	}

	public function actualizarOrdenCompra()
	{
		if($this->input->post('estado') === 'A') // ANULADO
		{
			$fecha_oc = mdate("%Y-%m-%d", time());

			$data = array(
				'estado' => $this->input->post('estado'),
				'fecha_oc' => $fecha_oc,
				'id_owner' => $this->session->userdata('person_id')
			);

			$this->orden_compra_model->actualizar($this->input->post('num_oc'), $data);
			$proveedor_serv = $this->input->post('hd_proveedor');

			$lis_proveedor = $this->orden_compra_model->verDatosProveedor($this->input->post('person_id'));
	  		$ruc_proveedor = $lis_proveedor[0]->nro_doc;
	  		$email_proveedor = $lis_proveedor[0]->email;

			$mail = new PHPMailer();
  			//$this->My_PHPMailer->SMTPAuth   = false;
	        $mail->IsSMTP();
	        $mail->Mailer     = 'smtp';
	        $mail->SMTPAuth   = true;
	        $mail->Host       = 'smtp.gmail.com';
	        $mail->Port       = 465;
	        $mail->Username   = 'isra100pre@gmail.com';
	        $mail->Password   = '12unodosQ';

	        $mail->SetFrom($this->g_mail_envio, $this->g_mail_envio_alias);  //Quien envía el correo
	        $mail->AddReplyTo($this->g_mail_responde, $this->g_mail_responde_alias);

	        $mail->AddAddress($email_proveedor, $proveedor_serv);
	        
	        $mail->AddCC($this->g_mail_copia,"");
	        $mail->AddBCC("isra100pre@gmail.com","Israel Cardenas");
	        $mail->IsHTML(true);


	        $mail->Subject    = 'Orden de Compra Nro. '.$this->input->post('num_oc');

			$mail->Body = '<table border="0" width="700" style="font-family: trebuchet MS; border: 4px solid #F5F5F5; font-size: 12px;" cellpadding="2">
							  <tr>
							    <td style="text-align: center;">
							      <div style="margin: 7px; font-size: 28px">ORDEN DE COMPRA / MISKI KACHI SAC</div>
							      <div>'.$proveedor_serv.'</div>
							      <div style="margin: 5px; font-weight: bold;">'.$ruc_proveedor.'</div>
							    </td>
							  </tr>
							  <tr>
							    <td style="text-align: left;">
							      <table width="80%" border="0" cellpadding="1" style="border: 0px solid #CCCCCC; font-size: 12px;">
							        <tr>
							          <td width="19%" style="border: 0px solid #CCCCCC;">FECHA PEDIDO </td>
							          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
							          <td width="80%" style="border: 0px solid #CCCCCC;">'.$fecha_oc.'</td>
							        </tr>
							        <tr>
							          <td width="19%" style="border: 0px solid #CCCCCC;">ORDEN COMPRA </td>
							          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
							          <td width="80%" style="border: 0px solid #CCCCCC;">'.$this->input->post('num_oc').'</td>
							        </tr>
							        <tr>
							          <td width="19%" style="border: 0px solid #CCCCCC;">ESTADO </td>
							          <td width="1%" style="border: 0px solid #CCCCCC;">: </td>
							          <td width="80%" style="border: 0px solid #CCCCCC;">Anulado</td>
							        </tr>
							      </table>
							      <br/><br/>
							    </td>
							  </tr>
							  <tr>
							    <td>
							     
							    </td>
							  </tr>
							  <tr>
							    <td style="text-align: center;">
							      <img alt="" height="62" src="public/images/'.$this->g_logotipo.'" width="" /><br />
							      <label style="font-weight:bold; font-size: 15px;">Area de Almac&eacute;n '.$this->g_nombre_corto.'</label><br />
							      Tlf: 51+ '.$this->g_telefono.'<br />
							      '.$this->g_email.'<br />
							      <a href="'.$this->g_web.'" style="text-decoration: none;">'.$this->g_web.'</a>
							    </td>
							  </tr>
							  </table>';
	        $mail->AltBody    = "";
	        $mail->Send();

			echo 'Anulado';
		}
		else // CONCILIADO
		{
			$person_id = $this->input->post('person_id');
			$num_oc = $this->input->post('num_oc');
			$lis_servicios_prov = $this->orden_compra_model->listarServiciosXProvOC($person_id, $num_oc);

			if($this->input->post('rbtipocosto') == 'IGV')
			{
				$igv = 'S';
				$inafecto = 'N';
			}	
			else
			{
				$igv = 'N';
				$inafecto = 'S';
			}
			
			$totales = 0;
			foreach($lis_servicios_prov as $i=>$lis)
	  		{
	  			$precio = $this->input->post('precio'.$lis->id_serv_prov);
	  			if($precio <> 0 && $precio != '')
	  			{
	  				$cantidad = $this->input->post('cantidad'.$lis->id_serv_prov);
	  				$total = $this->input->post('total'.$lis->id_serv_prov);

	  				$data = array(
							'precio' => $precio,
							'inafecto' => $inafecto,
							'igv' => $igv,
							'total' => $total
						);
					$this->orden_compra_model->actualizarDetalle($lis->id_oc, $lis->correlativo, $data);
					$totales = $totales + $total;

					// PROCESO DE CALCULO (Cantidad, Costo Unit., Stock Ins. y Costo Ins.) ALMACEN
						$lis_data_almacen = $this->almacen_model->verDatosAlmacenServ($lis->id_serv_prov);
						$cant_alm_actual = $lis_data_almacen[0]->cantidad;
						$unidad_medida_alm_actual = $lis_data_almacen[0]->unidad_medida;
						$valor_porcion_alm_actual = $lis_data_almacen[0]->valor_porcion;
						$stock_porcion_alm_actual = $lis_data_almacen[0]->stock_porcion;
						//$costo_porcion_alm_actual = $lis_data_almacen[0]->costo_porcion;

						//$cantidad_alm = ($cantidad + $cant_alm_actual);
						$cantidad_alm = $cantidad;
						$costo_unit = $precio;

						/*
						if($id_unidad == 3 || $id_unidad == 5 || $id_unidad == 4) // GRM - MLD - PRN
						{
							$stock_insumo = ($cantidad_alm * $unidad_medida_alm_actual);
							$costo_insumo = (($costo_unit / $stock_insumo));
						}
						else
						{
							$stock_insumo = ($cantidad_alm * $unidad_medida_alm_actual);
							$costo_insumo = ($costo_unit / $unidad_medida_alm_actual);
						}
						*/
												
						if($lis_data_almacen[0]->id_unidad == 3 || $lis_data_almacen[0]->id_unidad == 4) // GRM - PRN
						{
							$stock_insumo = ($stock_porcion_alm_actual + ($cantidad_alm * $unidad_medida_alm_actual));
							$costo_insumo = (($costo_unit / $stock_insumo));
						}
						else if($lis_data_almacen[0]->id_unidad == 5) // MLD 
						{
							$stock_insumo = ($stock_porcion_alm_actual + ($cantidad_alm * $unidad_medida_alm_actual));
							$costo_insumo = ($costo_unit / $unidad_medida_alm_actual);
						}
						else
						{
							$stock_insumo =  ($stock_porcion_alm_actual + ($cantidad_alm * $unidad_medida_alm_actual));
							$costo_insumo = ($costo_unit / $unidad_medida_alm_actual);
						}						

						$data_alm = array(							
								'cantidad' => $cantidad_alm,
							'costo' => $costo_unit,
								'stock_porcion' => str_replace(',', '', number_format($stock_insumo)),
							'costo_porcion' => $costo_insumo,
							'fecha_modifica' => mdate("%Y-%m-%d", time()),
							'id_owner' => $this->session->userdata('person_id')
						);
						$this->almacen_model->actualizarAlmacenServicio($lis->id_serv_prov, $data_alm);
					// --

					// PROCESO ACTUALIZAR COSTOS DE INSUMOS DEL PRODUCTO
						$lis_prod_alm = $this->almacen_model->verProductoAlmacen($lis_data_almacen[0]->id_almacen);
						foreach($lis_prod_alm as $lispa)
						{
							if($lispa->id_unidad == 1) //UND
							{
								$data = array( 'costo_mlts' => $costo_insumo );
								$this->productos_model->actualizarDetalle($lispa->id_producto, $lispa->id_almacen, $data);

								$lista = $this->productos_model->verInsumosXProd($lispa->id_producto);

								$total_deta = 0;
								foreach($lista as $i=>$lis)
								{
									if($lis->valor == 'UND')
									{
										$nv_costo_porcion = ($lis->valor_porcion * $lis->costo_porcion);
										
										$data = array('costo_mlts' => $nv_costo_porcion);
										$this->productos_model->actualizarDetalle($lis->id_producto, $lis->id_almacen, $data);

										$total_deta = $total_deta + $nv_costo_porcion;
									}
								}
								
								$data = array(
										'precio_insumo' => $total_deta
									);
								$this->productos_model->actualizar($lispa->id_producto, $data);
							}
						}
					// --
	  			}
	  		}
	  		

			$data = array(
				'estado' => $this->input->post('estado'),
				'fecha_oc' => mdate("%Y-%m-%d", time()),
				'total' => $totales,
				'id_owner' => $this->session->userdata('person_id')
			);
			$this->orden_compra_model->actualizar($num_oc, $data);

			echo 'Conciliado';
		}

	}

	// -- CIERRA ORDEN DE COMPRA!


	// PROCESO DE CALCULO (Cantidad, Costo Unit., Stock Ins. y Costo Ins.) ALMACEN
	public function calcularStockAlmacen()
	{
		$id_unidad = $this->input->post('id_unidad');
		$cantidad_alm = $this->input->post('cantidad');
		$unidad_medida_alm_actual = $this->input->post('unidad_medida');
		$valor_porcion_alm_actual = $this->input->post('valor_porcion');

		if($unidad_medida_alm_actual == '' || $valor_porcion_alm_actual == '')
			$stock_insumo = 0;
		else
		{
			$stock_insumo = ($cantidad_alm * $unidad_medida_alm_actual);
		}

		print '[{"stock_insumo":"'.str_replace(',', '', number_format($stock_insumo)).'"}]';
	}

	public function calcularCostoServAlmacen()
	{
		$id_unidad = $this->input->post('id_unidad');
		$cantidad_alm = $this->input->post('cantidad');
		$costo_unit = $this->input->post('costo_serv');
		$unidad_medida_alm_actual = $this->input->post('unidad_medida');
		$valor_porcion_alm_actual = $this->input->post('valor_porcion');

		if($unidad_medida_alm_actual == '') // || $valor_porcion_alm_actual == ''
			$costo_insumo = 0;
		else
		{
			if($id_unidad == 3 || $id_unidad == 5 || $id_unidad == 4) // GRM - MLD - PRN
			{
				$costo_insumo = ($costo_unit / (($cantidad_alm * $unidad_medida_alm_actual)));
			}
			else
			{
				//$costo_insumo = (($costo_unit * $valor_porcion_alm_actual) / $unidad_medida_alm_actual);
				$costo_insumo = ($costo_unit / $unidad_medida_alm_actual);
			}
		}
		print '[{"costo_insumo":"'.number_format($costo_insumo, 3).'"}]';
	}

	public function calcularStockCostosAlmacen()
	{
		$id_unidad = $this->input->post('id_unidad');
		$cantidad_alm = $this->input->post('cantidad');
		$costo_unit = $this->input->post('costo_serv');
		$unidad_medida_alm_actual = $this->input->post('unidad_medida');
		$valor_porcion_alm_actual = $this->input->post('valor_porcion');

		if($unidad_medida_alm_actual == '' || $valor_porcion_alm_actual == '')
		{
			$stock_insumo = 0;
			$costo_insumo = 0;
		}
		else
		{
			if($id_unidad == 3 || $id_unidad == 5 || $id_unidad == 4) // GRM - MLD - PRN
			{
				$stock_insumo = ($cantidad_alm * $unidad_medida_alm_actual);
				$costo_insumo = (($costo_unit / $stock_insumo));
			}
			else
			{
				$stock_insumo = ($cantidad_alm * $unidad_medida_alm_actual);
				$costo_insumo = ($costo_unit / $unidad_medida_alm_actual);
			}
		}

		$array = array(
					array(
						'stock_insumo' => str_replace(',', '', number_format($stock_insumo)),
				   		'costo_insumo' => number_format($costo_insumo, 3)
				   	)
				 );
		print json_encode($array);
	}
	// --


	// Lista de Servicios por Proveedor
	public function verListaServicios()
	{
		$data['lis_servicios_prov'] = $this->almacen_model->listarServiciosXProvAlmacen($this->input->post('prov_id'));
		$data['v_ajax'] = 'genera_oc';
		$data['v_ajax_id_serv_prov'] = $this->input->post('id_serv_prov');
		$this->load->view("almacen/ajax", $data);
	}

	// Lista de Servicios por Proveedor asociado a OC
	public function verListaServiciosOC()
	{
		$data['lis_servicios_prov'] = $this->orden_compra_model->listarServiciosXProvOC($this->input->post('prov_id'), $this->input->post('num_oc'));
		$data['v_ajax'] = 'ver_oc';
		$data['v_ajax_estado'] = $this->input->post('estado');
		$this->load->view("almacen/ajax", $data);
	}


	// Lista de Proveedores por Servicio
	public function verListaProveedoresXServ()
	{
		$lis_proveedores = $this->servicios_model->listarProveedoresXServ($this->input->post('id_serv_prov'));

		$select = '<select class="form-control" name="id_prov" id="id_prov"  style="">';
		if($lis_proveedores)
		{
			$select .= '<option value="0">----------------- Seleccione un Proveedor -----------------</option>';
			foreach ($lis_proveedores as $key => $lisd)
			{
				$select .= '<option value="'.$lisd->person_id.'">'.$lisd->nombre_corto.'</option>';
			}
		}
		else
		{
			$select .= '<option value="0"> No existe proveedores asociados! </option>';
		}

		$select .= '</select>';
		echo $select;
	}
	
    // Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Almacen';
		$this->load->view("almacen/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Almacen';
		$data['lista'] = $this->almacen_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("almacen/report", $data);
	}
	// --


	// PROCESO DE MERMAS
	public function verFormMerma()
	{
		$data['lis_almacen'] = $this->almacen_model->verAlmacenMerma($this->input->post('id_almacen'));
		$data['v_ajax'] = 'mermas';
		$this->load->view("almacen/ajax", $data);
	}

	public function listarAlmMermas()
	{
		$data['lista_servicios'] = $this->servicios_model->listar();
		
		$data['modo'] = 'mermas';
		$data['lista_mermas'] = $this->mermas_model->listar();
		$this->load->view("almacen/main", $data);
	}

	public function insertarAlmMerma()
	{
		$fecha_registro = mdate("%Y-%m-%d", time());

		$data = array(
				'id_almacen' => $this->input->post('id_almacen'),
				'stock_actual' => $this->input->post('stock_actual'),
				'stock_merma' => $this->input->post('stock_merma'),
				'fecha_registro' => $fecha_registro,
				'id_owner' => $this->session->userdata('person_id')
			);
		$this->mermas_model->insertar($data);
		
		$stock_insumo = ($this->input->post('stock_actual') - $this->input->post('stock_merma'));
		
		$data_alm = array(							
						'stock_porcion' => str_replace(',', '', number_format($stock_insumo))
						//'fecha_modifica' => mdate("%Y-%m-%d", time()),
						//'id_owner' => $this->session->userdata('person_id')
					);
		$this->almacen_model->actualizarAlmacenServicio($this->input->post('id_serv_prov'), $data_alm);
	}

	public function filtrarMermas($fecha1, $fecha2, $cbo_1)
	{
		//$data['titulo_main'] = 'Reporte de Almacen';
		$data['modo'] = 'mermas';
		$data['lista_servicios'] = $this->servicios_model->listar();
		
		$data['lista_mermas'] = $this->mermas_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("almacen/main", $data);
	}
	// --
}