<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Compras extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('compras_model');
    	$this->load->model('proveedores_model');
    	$this->load->model('series_documentos_model');
    	$this->load->model('tipo_cambio_model');

    	$this->load->model('orden_compra_model');

		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		// --
		$data['lista_documentos'] = $this->series_documentos_model->listar();
		$data['lista_monedas'] = $this->compras_model->listarMonedas();
		$data['lista_unidades'] = $this->compras_model->listarUnidades();
		
		
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['lista'] = $this->compras_model->listar();
		$this->load->view("compras/main", $data);
	}

	public function insertarCab()
	{
		if($this->compras_model->validarCod($this->input->post('doc_serie').'-'.$this->input->post('doc_numero')) === 1) // Si el dato existe!
  			echo 'existe';
  		else
  		{
  			if($this->input->post('id_oc') !== 'NO')
  				$id_oc = $this->input->post('id_oc');
  			else
  				$id_oc = '';
  			
			$data = array(
					'prov_id' => $this->input->post('prov_id'),
					'condicion' => $this->input->post('condicion'),
					'detraccion' => $this->input->post('detraccion'),
					//'activo' => $activo,
					'tipo_doc' => $this->input->post('tipo_doc'),
					'nro_doc' => $this->input->post('doc_serie').'-'.$this->input->post('doc_numero'),
					'moneda' => $this->input->post('moneda'),
					'fecha_compra' => $this->input->post('fecha_compra'),
					'tc' => $this->input->post('tipo_cambio'),
					'porc_igv' => $this->input->post('igv_global'),
					'estado_compra' => $this->input->post('estado_compra'),
						'id_oc' => $id_oc,
					'fecha_vence' => $this->input->post('fecha_vence'),
					'fecha_registro' => mdate("%Y-%m-%d", time()),
					'id_owner' => $this->session->userdata('person_id')
				);
			$id_compra = $this->compras_model->insertarCab($data);


			// Proceso de Grabar O/C en la COMPRA
				if($this->input->post('id_oc') !== 'NO')
				{
					$person_id = $this->input->post('prov_id');
					$num_oc = $this->orden_compra_model->obtenerNumOC($this->input->post('id_oc'));
			
					// Actualiza el documento de la O/C
					$data = array(
						'doc_oc' => $this->input->post('doc_serie').'-'.$this->input->post('doc_numero')
						//'fecha_oc' => mdate("%Y-%m-%d", time()),
						//'id_owner' => $this->session->userdata('person_id')
					);
					$this->orden_compra_model->actualizar($num_oc, $data);
					// --

					$lis_servicios_prov = $this->orden_compra_model->listarServiciosXProvOC($person_id, $num_oc);

					foreach($lis_servicios_prov as $i=>$lis)
	  				{
	  					$data = array(
								'id_compra' => $id_compra,
								'id_serv_prov' => $lis->id_serv_prov,
								'id_unidad' => $lis->id_unidad,
								'correlativo' => $lis->correlativo,
								'cantidad' => $lis->cantidad,
								'precio' => $lis->precio,
								'inafecto' => $lis->inafecto,
								'igv' => $lis->igv,
								'total' => $lis->total
							);
						$this->compras_model->insertarDetalle($data);
	  				}

					$lista_serv_compra = $this->compras_model->listarDetalles($id_compra);
					$moneda = $this->compras_model->verMoneda($id_compra);
					$tc = $this->compras_model->verTC($id_compra);
					$porc_igv = $this->compras_model->verIGV($id_compra);

					// Actualizar Cabecera de la Compra
			  		$total_deta = 0;
			  		foreach($lista_serv_compra as $i=>$lis)
			  		{
			  			$total_deta += $lis->total;

						if($lis->igv == 'S' && $lis->inafecto == 'N') // IGV
							$valor = 'IGV';
						else if($lis->igv == 'N' && $lis->inafecto == 'S') // INAFECTO
							$valor = 'INAFECTO';
						else //AMBOS
							$valor = 'AMBOS';
						//break;
			  		}
			  			if($valor == 'IGV')
						{
							$igv = ($total_deta * $porc_igv / (100 + $porc_igv));
							$total_afecto = ($total_deta - $igv);
							$total_inafecto = 0;
								$soles_total_sin_igv = $total_afecto;
							$total = $total_deta;
								$dolares_afec = ($total_afecto / $tc);
								$dolares_inaf = 0;
								$dolares_total_sin_igv = $dolares_afec;
								$dolares_total = ($total / $tc);
						}
						else if($valor == 'INAFECTO')
						{
							$total_afecto = 0;
							$igv = 0;
							$total_inafecto = $total_deta;
								$soles_total_sin_igv = $total_inafecto;
							$total = $total_inafecto;
								$dolares_afec = 0;
								$dolares_inaf = ($total_inafecto / $tc);
								$dolares_total_sin_igv = $dolares_inaf;
								$dolares_total = ($total / $tc);
						}
						else
						{
							$igv = ($total_deta * $porc_igv / 100);
							$total_afecto = $total_deta;
							$total_inafecto = 0;
								$soles_total_sin_igv = $total_afecto;
							$total = ($total_deta + $igv);
								$dolares_afec = ($total_afecto / $tc);
								$dolares_inaf = 0;
								$dolares_total_sin_igv = $dolares_afec;
								$dolares_total = ($total / $tc);
						}
			  			$data = array(
								'total' => $total,
								'afecto' => $total_afecto,
								'igv' => $igv,
								'inafecto' => $total_inafecto,
									'soles_afec' => $total_afecto,
									'soles_inaf' => $total_inafecto,
									'soles_total_sin_igv' => $soles_total_sin_igv,
								'soles_total' => $total,
									'dolares_afec' => $dolares_afec,
									'dolares_inaf' => $dolares_inaf,
									'dolares_total_sin_igv' => $dolares_total_sin_igv,
									'dolares_total' => $dolares_total
						);
			  			$this->compras_model->actualizar($id_compra, $data);
					// --
			  	}
		  	// --
			echo $id_compra;
		}
	}

	public function insertarDetalle()
	{
		$tipopago = $this->input->post('rbtipopago');
		if($tipopago == 'IGV')
		{
			$inafecto = 'N';
			$igv = 'S';
		}
		else if($tipopago == 'INAFECTO')
		{
			$inafecto = 'S';
			$igv = 'N';
		}
		else
		{
			$inafecto = 'N';
			$igv = 'N';
		}

		// Muestra el último "correlativo" detalle Compra
		$correlativo = $this->compras_model->obtenerCorrelativoDetaCompra($this->input->post('id_cab'));
		if($correlativo > 0)
			$correlativo = $correlativo + 1;
		else
			$correlativo = 1;

		$data = array(
				'id_compra' => $this->input->post('id_cab'),
				'id_serv_prov' => $this->input->post('id_serv_prov'),
				'id_unidad' => $this->input->post('id_unidad'),
				'correlativo' => $correlativo,
				'cantidad' => $this->input->post('cantidad'),
				'precio' => $this->input->post('precio'),
				'inafecto' => $inafecto,
				'igv' => $igv,
				'total' => $this->input->post('total')
			);
		$this->compras_model->insertarDetalle($data);

		$lista_serv_compra = $this->compras_model->listarDetalles($this->input->post('id_cab'));
		$moneda = $this->compras_model->verMoneda($this->input->post('id_cab'));
		$tc = $this->compras_model->verTC($this->input->post('id_cab'));
		$porc_igv = $this->compras_model->verIGV($this->input->post('id_cab'));

		// Actualizar Cabecera de la Compra
  		$total_deta = 0;
  		foreach($lista_serv_compra as $i=>$lis)
  		{
  			$total_deta += $lis->total;

			if($lis->igv == 'S' && $lis->inafecto == 'N') // IGV
				$valor = 'IGV';
			else if($lis->igv == 'N' && $lis->inafecto == 'S') // INAFECTO
				$valor = 'INAFECTO';
			else //AMBOS
				$valor = 'AMBOS';
			//break;
  		}
  			if($valor == 'IGV')
			{
				$igv = ($total_deta * $porc_igv / (100 + $porc_igv));
				$total_afecto = ($total_deta - $igv);
				$total_inafecto = 0;
					$soles_total_sin_igv = $total_afecto;
				$total = $total_deta;
					$dolares_afec = ($total_afecto / $tc);
					$dolares_inaf = 0;
					$dolares_total_sin_igv = $dolares_afec;
					$dolares_total = ($total / $tc);
			}
			else if($valor == 'INAFECTO')
			{
				$total_afecto = 0;
				$igv = 0;
				$total_inafecto = $total_deta;
					$soles_total_sin_igv = $total_inafecto;
				$total = $total_inafecto;
					$dolares_afec = 0;
					$dolares_inaf = ($total_inafecto / $tc);
					$dolares_total_sin_igv = $dolares_inaf;
					$dolares_total = ($total / $tc);
			}
			else
			{
				$igv = ($total_deta * $porc_igv / 100);
				$total_afecto = $total_deta;
				$total_inafecto = 0;
					$soles_total_sin_igv = $total_afecto;
				$total = ($total_deta + $igv);
					$dolares_afec = ($total_afecto / $tc);
					$dolares_inaf = 0;
					$dolares_total_sin_igv = $dolares_afec;
					$dolares_total = ($total / $tc);
			}
  			$data = array(
					'total' => $total,
					'afecto' => $total_afecto,
					'igv' => $igv,
					'inafecto' => $total_inafecto,
						'soles_afec' => $total_afecto,
						'soles_inaf' => $total_inafecto,
						'soles_total_sin_igv' => $soles_total_sin_igv,
					'soles_total' => $total,
						'dolares_afec' => $dolares_afec,
						'dolares_inaf' => $dolares_inaf,
						'dolares_total_sin_igv' => $dolares_total_sin_igv,
						'dolares_total' => $dolares_total
			);
  			$this->compras_model->actualizar($this->input->post('id_cab'), $data);
		// --

  		$data['lista'] = $this->compras_model->listarDetalles($this->input->post('id_cab'));
		$data['v_ajax'] = 'compras_detalle';
		$data['v_ajax_moneda'] = $moneda;
		$data['v_ajax_igv'] = $porc_igv;
		$this->load->view("compras/ajax", $data);
	}


	public function ver($id)
	{
		// Buscar datos para actualizar
		$data['bus_dato'] = $this->compras_model->ver($id);
		//Muestra el User creador:
		$data['user_creador_data'] = $this->privilegios_model->getUserCreator($data['bus_dato'][0]->id_owner);
		//--
		$data['modo'] = 'actualizar';

		$data['lista_deta'] = $this->compras_model->listarDetalles($data['bus_dato'][0]->id_compra);
		$data['num_oc'] = $this->orden_compra_model->obtenerNumOC($data['bus_dato'][0]->id_oc);
		/*
		echo "<br><br><br><pre>";
		print_r($data);
		echo "</pre>";
		*/
		$this->load->view("compras/main", $data);
	}

	public function actualizar()
	{
		$data = array(
				'estado_compra' => $this->input->post('estado_compra'),
				'detraccion' => $this->input->post('detraccion'),
				'fecha_modifica' => mdate("%Y-%m-%d", time())
			);
		$this->compras_model->actualizar($this->input->post('id_compra'), $data);
	}

	public function eliminarDetalle()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_compra = $cod_array[0];
		$correlativo = $cod_array[1];
		$this->compras_model->eliminarDetalle($id_compra, $correlativo);

		//-- Proceso de mostrar detalles de la Compra actualizados:
		$lista_serv_compra = $this->compras_model->listarDetalles($id_compra);
		$moneda = $this->compras_model->verMoneda($id_compra);
		$tc = $this->compras_model->verTC($id_compra);
		$porc_igv = $this->compras_model->verIGV($id_compra);

		if($lista_serv_compra != NULL)
		{
			// Actualizar Cabecera de la Compra
	  		$total_deta = 0;
	  		foreach($lista_serv_compra as $i=>$lis)
	  		{
	  			$total_deta += $lis->total;

				if($lis->igv == 'S' && $lis->inafecto == 'N') // IGV
					$valor = 'IGV';
				else if($lis->igv == 'N' && $lis->inafecto == 'S') // INAFECTO
					$valor = 'INAFECTO';
				else //AMBOS
					$valor = 'AMBOS';
				//break;
	  		}
	  			if($valor == 'IGV')
				{
					$igv = ($total_deta * $porc_igv / (100 + $porc_igv));
					$total_afecto = ($total_deta - $igv);
					$total_inafecto = 0;
						$soles_total_sin_igv = $total_afecto;
					$total = $total_deta;
						$dolares_afec = ($total_afecto / $tc);
						$dolares_inaf = 0;
						$dolares_total_sin_igv = $dolares_afec;
						$dolares_total = ($total / $tc);
				}
				else if($valor == 'INAFECTO')
				{
					$total_afecto = 0;
					$igv = 0;
					$total_inafecto = $total_deta;
						$soles_total_sin_igv = $total_inafecto;
					$total = $total_inafecto;
						$dolares_afec = 0;
						$dolares_inaf = ($total_inafecto / $tc);
						$dolares_total_sin_igv = $dolares_inaf;
						$dolares_total = ($total / $tc);
				}
				else
				{
					$igv = ($total_deta * $porc_igv / 100);
					$total_afecto = $total_deta;
					$total_inafecto = 0;
						$soles_total_sin_igv = $total_afecto;
					$total = ($total_deta + $igv);
						$dolares_afec = ($total_afecto / $tc);
						$dolares_inaf = 0;
						$dolares_total_sin_igv = $dolares_afec;
						$dolares_total = ($total / $tc);
				}
	  			$data = array(
					'total' => $total,
					'afecto' => $total_afecto,
					'igv' => $igv,
					'inafecto' => $total_inafecto,
						'soles_afec' => $total_afecto,
						'soles_inaf' => $total_inafecto,
						'soles_total_sin_igv' => $soles_total_sin_igv,
					'soles_total' => $total,
						'dolares_afec' => $dolares_afec,
						'dolares_inaf' => $dolares_inaf,
						'dolares_total_sin_igv' => $dolares_total_sin_igv,
						'dolares_total' => $dolares_total
				);

				$this->compras_model->actualizar($id_compra, $data);
		}
		else // No tiene ningun detalle la Compra
		{
				$data = array(
					'total' => 0,
					'afecto' => 0,
					'igv' => 0,
					'inafecto' => 0,
						'soles_afec' => 0,
						'soles_inaf' => 0,
						'soles_total_sin_igv' => 0,
					'soles_total' => 0,
						'dolares_afec' => 0,
						'dolares_inaf' => 0,
						'dolares_total_sin_igv' => 0,
						'dolares_total' => 0
				);

				$this->compras_model->actualizar($id_compra, $data);
		}
	  			
			// --
	  		$data['lista'] = $this->compras_model->listarDetalles($id_compra);
			$data['v_ajax'] = 'compras_detalle';
			$data['v_ajax_moneda'] = $moneda;
			$data['v_ajax_igv'] = $porc_igv;
			$this->load->view("compras/ajax", $data);
		//--
	}


	// Buscar Datos del Proveedor por RUC
	public function verProveedor()
	{
		$bus_dato = $this->proveedores_model->verProveedor($this->input->post('ruc'));
		
		header('Content-type: application/json; charset=utf-8');
		if($bus_dato)
			print json_encode($bus_dato);
		else
			print '[{"estado":"error"}]';
	}

	// Ver TC por Fecha de Compra
	public function verTCxFecha()
	{
		$data['bus_dato'] = $this->tipo_cambio_model->verTCxFecha($this->input->post('fecha_compra'));
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	// Lista de Servicios por Proveedor
	public function verListaServicios()
	{
		$lis_servicios_prov = $this->proveedores_model->listarServiciosXProv($this->input->post('prov_id'));

		$select_servicios = '<select class="form-control" name="id_serv_prov" id="id_serv_prov">';
		if($lis_servicios_prov)
		{
			$select_servicios .= '<option value="0"> --------- Seleccione un Servicio --------- </option>';
			foreach ($lis_servicios_prov as $key => $lisd)
			{
				$select_servicios .= '<option value="'.$lisd->id_serv_prov.'">'.$lisd->nombres.'</option>';
			}
		}
		else
		{
			$select_servicios .= '<option value="0"> No existe servicios asociados! </option>';
		}

		$select_servicios .= '</select>';
		echo $select_servicios;
	}


	// Muestra listado de OC por Proveedor
	public function verOCXProveedor()
	{
		$lis_oc = $this->compras_model->verOCXProveedor($this->input->post('person_id'));

		$select_cbo = '<select class="form-control" name="id_oc" id="id_oc">';
		if($lis_oc)
		{
			// $select_cbo .= '<option value="0"> --------- Seleccione un Servicio --------- </option>';
			foreach ($lis_oc as $key => $lis)
			{
				$select_cbo .= '<option value="'.$lis->id_oc.'">'.$lis->num_oc.'</option>';
			}
		}
		else
		{
			$select_cbo .= '<option value="NO"> No hay O/C asociados! </option>';
		}

		$select_cbo .= '</select>';
		echo $select_cbo;
	}


    // Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Compras';
		$this->load->view("compras/report", $data);  
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Compras';
		$data['lista'] = $this->compras_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("compras/report", $data);
	}
	// --

}