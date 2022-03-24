<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Productos extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('productos_model');
	    $this->load->model('servicios_model');
	    $this->load->model('almacen_model');

		$this->load->model('proveedores_model');
		$this->load->model('series_documentos_model');
		$this->load->model('tipo_cambio_model');
		$this->load->model('productoscate_model');

		$this->load->model('orden_compra_model');

		$this->load->library(array('session','form_validation'));
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$data['lista_categorias_prod'] = $this->productos_model->listarCategoriasProd();
		$data['lista_producto_camanda'] = $this->productos_model->listarProductoEnvioComanda();
		
		$data['lista_servicios'] = $this->almacen_model->listar('1001');
		$data['lista_unidades'] = $this->productos_model->listarUnidades();

		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		$id_categoria = $this->input->post('idx_categ');
		$data['cbo_1'] = $id_categoria;
		$data['lista'] = $this->productos_model->listar($id_categoria);

		$this->load->view("productos/main", $data);
	}

	// public function generarCod()
	// {
	// 	$cod = 0;
	// 	$cod = $this->productos_model->generarCodMax();
	// 	$num = $cod + 1;
	// 	return $num;
	// }

	public function insertar()
	{
		// $num = $this->generarCod();
		$fecha_registro = mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time());
		$data = array(
					'id_categoria' => $this->input->post('id_categoria'),
					'producto_comanda_id' => $this->input->post('producto_comanda_id'),
					'nro_producto' => $this->input->post('nro_producto'),
					'nombre' => strtoupper($this->input->post('nombre')),
					'precio_venta' => $this->input->post('precio_venta'),
					'id_owner' => $this->session->userdata('person_id'),
					'persona_id_created' => $this->session->userdata('person_id'),
					'date_created'=>$fecha_registro,
					'activo' => $this->input->post('activo'),
					'unidades' => $this->input->post('unidades'),
				);
		$num = $this->productos_model->insertar($data);
		echo $num;
	}

	public function insertarDetalle()
	{
		if($this->input->post('unidad') === 'LTS')
		{
			$costo_mlts = $this->input->post('costo_porcion');
			$porcion_mlts = $this->input->post('valor_porcion');
		}
		else if($this->input->post('unidad') === 'KLG')
		{
			$costo_mlts = $this->input->post('costo_porcion');
			$porcion_mlts = $this->input->post('valor_porcion');
		}
		else if($this->input->post('unidad') === 'UND')
		{
			$costo_mlts = $this->input->post('costo_porcion');
			$porcion_mlts = $this->input->post('valor_porcion');
		}
		else if($this->input->post('unidad') === 'GRM')
		{
			$costo_mlts = $this->input->post('costo_porcion');
			$porcion_mlts = $this->input->post('valor_porcion');
		}
		else
		{
			$costo_mlts = 0;
			$porcion_mlts = 0;
		}

		$data = array(
				'id_producto' => $this->input->post('id_cab'),
				'id_almacen' => $this->input->post('id_almacen'),
				'costo_mlts' => $costo_mlts, //costo_mlts -> Este campo es tanto para LITROS Y KILOS.
				'porcion_mlts' => $porcion_mlts //porcion_mlts -> Este campo es tanto para LITROS Y KILOS.
			);
		$this->productos_model->insertarDetalle($data);

		// Actualiza el PRECIO_INSUMO
			$lista = $this->productos_model->verInsumosXProd($this->input->post('id_cab'));

			$total_deta = 0;
			foreach($lista as $i=>$lis)
			{
				$total_deta = $total_deta + $lis->costo_porcion;
			}
			$data = array(
					'precio_insumo' => $total_deta
				);
			$this->productos_model->actualizar($this->input->post('id_cab'), $data);
		// --

		$data['lista'] = $this->productos_model->verInsumosXProd($this->input->post('id_cab'));
		$data['v_ajax'] = 'detalle';
		$data['v_ajax_moneda'] = $this->g_moneda; //'S/.'
		$this->load->view("productos/ajax", $data);
	}


	public function ver($id)
	{
		$id_categoria = $this->input->post('idx_categ');
		$data['cbo_1'] = $id_categoria;
		$data['bus_dato'] = $this->productos_model->ver($id);
		$data['user_creador_data'] = $this->privilegios_model->getUserCreator($data['bus_dato'][0]->id_owner);		
		$data['modo'] = 'actualizar';
		$data['moneda'] = $this->g_moneda; 
		$data['lista_deta'] = $this->productos_model->verInsumosXProd($data['bus_dato'][0]->id_producto);
		
		$this->load->view("productos/main", $data);
	}

	public function actualizar()
	{
		if($this->input->post('nombre')) // Actualiza por INSERTAR
		{
			$data = array(
						'producto_comanda_id' => strtoupper($this->input->post('producto_comanda_id')),
						'nro_producto' => $this->input->post('nro_producto'),
        				'nombre' => strtoupper($this->input->post('nombre')),
						'precio_venta' => $this->input->post('precio_venta'),
						'date_updated' => mdate("%Y-%m-%d", time()).'T'.mdate("%H:%i:%s", time()),
						'persona_id_updated' => $this->session->userdata('person_id'),
						'precio_insumo' => $this->input->post('precio_insumo'),
						'activo' => $this->input->post('activo'),
						'unidades' => $this->input->post('unidades'),
					);
		}
		else // Actualiza por MODIFICAR
		{
			$data = array(
						'precio_venta' => $this->input->post('precio_venta'),
						'persona_id_updated' => $this->session->userdata('person_id'),
						'precio_insumo' => $this->input->post('precio_insumo'),
						'activo' => $this->input->post('activo'),
						'unidades' => $this->input->post('unidades'),
					);
		}
		$this->productos_model->actualizar($this->input->post('id_producto'), $data);
	}

	public function eliminar()
	{
		$this->productos_model->eliminar($this->input->post('id'));
	}

	public function eliminarDetalle()
	{
		$cod_array = explode('-', $this->input->post('id'));
		$id_producto = $cod_array[0];
		$id_almacen = $cod_array[1];
		$this->productos_model->eliminarDetalle($id_producto, $id_almacen);

		// Actualiza el PRECIO_INSUMO
			$lista = $this->productos_model->verInsumosXProd($id_producto);

			$total_deta = 0;
			foreach($lista as $i=>$lis)
			{
				$total_deta = $total_deta + $lis->costo_porcion;
			}
			$data = array(
						'precio_insumo' => $total_deta
					);
			$this->productos_model->actualizar($id_producto, $data);
		// --

  		$data['lista'] = $this->productos_model->verInsumosXProd($id_producto);
		$data['v_ajax'] = 'detalle';
		$data['v_ajax_moneda'] = $this->g_moneda; //'S/.'
		$this->load->view("productos/ajax", $data);
	}

	public function verUnidadServicio()
	{
		$lis_servicios_prov = $this->almacen_model->ver($this->input->post('id_almacen'));

		if($lis_servicios_prov)
		{
			$lis_unidad = $this->productos_model->verUnidad($lis_servicios_prov[0]->id_unidad);

			$array = array(
						array(
							'valor' => $lis_unidad[0]->valor,
							'valor_insumo' => $lis_servicios_prov[0]->valor_porcion,
							'stock_insumo' => str_replace(',', '', number_format($lis_servicios_prov[0]->stock_porcion)),
					   		'costo_insumo' => $lis_servicios_prov[0]->costo_porcion
					   	)
					 );
			print json_encode($array);
		}
		else
			print '[{"estado":"sin_datos"}]';
	}

	public function verNroProducto()
	{
		$nro_producto = 0;
		$nro_producto = $this->productos_model->verNroProductoXCategoria($this->input->post('id_categoria'));
		$nro_producto = $nro_producto + 1;
		$nro_prod = str_pad($nro_producto, 3 ,"0", STR_PAD_LEFT);
		print '[{"nro_producto":"'.$nro_prod.'"}]';
	}

	// Subir Archivos
	public function cargarFile()
    {
        if($_FILES["archivo"]["name"])
        {
            $id = $this->input->post('id');

            $archivo = $_FILES["archivo"]["name"];
            // FCPATH -> Obtiene la ruta del directorio principal del proyecto.
            $ruta_archivo = FCPATH."public/images/productos/".$archivo;
            $tmp_imagen = $_FILES["archivo"]["tmp_name"];
            copy($tmp_imagen, $ruta_archivo);

            $this->productos_model->updateImagen($id, $archivo);
            echo $id;
        }
        else
        {
            echo 0;
        }
    }

    // Reportes
	public function report()
	{
		$data['lista'] = NULL;
		$data['titulo_main'] = 'Reporte de Productos';
		$this->load->view("productos/report", $data);
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
		$data['titulo_main'] = 'Reporte de Productos';
		$data['lista'] = $this->productos_model->filtrar($fecha1, $fecha2, $cbo_1);
		$data['fecha_1'] = $fecha1;
		$data['fecha_2'] = $fecha2;
		$data['cbo_1'] = $cbo_1;
		$this->load->view("productos/report", $data);
	}

	// MANTENIMIENTO DE CATEGORIA DE PRODUCTOS
	public function listarCategorias()
	{
		$data['modo'] = 'categorias';
    	$data['p_modulo'] = 'categorias';
		$data['page_js'] = 'productos_cat.js';

		$data['lista'] = $this->productoscate_model->listar();
		$this->load->view("productos/main", $data);
	}

	public function insertarCategorias()
	{
		$data = array(
					'id_categoria' => $this->input->post('id_categoria'),
					'nombre' => strtoupper($this->input->post('nombre')),
		      		'estado' => $this->input->post('estado')
				);

    	$this->productoscate_model->insertar($data);
		$data['lista'] = $this->productoscate_model->listar();
		$data['v_ajax'] = 'categorias';
    	$data['p_modulo'] = 'categorias';
		$this->load->view("productos/ajax", $data);

	}

	public function verCategorias()
	{
		$data['bus_dato'] = $this->productoscate_model->ver($this->input->post('id')); //No Tocar el post('id')
		header('Content-type: application/json; charset=utf-8');
		print json_encode($data['bus_dato']);
	}

	public function actualizarCategorias()
	{
		$data = array(
					'id_categoria' => $this->input->post('id_categoria'),
					'nombre' => strtoupper($this->input->post('nombre')),
					'estado' => $this->input->post('estado')
				);

    	$this->productoscate_model->actualizar($this->input->post('id_categoria'),$data);
		$data['lista'] = $this->productoscate_model->listar();
		$data['v_ajax'] = 'categorias';
    	$data['p_modulo'] = 'categorias';
		$this->load->view("productos/ajax", $data);
	}

	public function eliminarCategorias()
	{
		$this->productoscate_model->eliminar($this->input->post('id')); //No Tocar el post('id')
	}

}
