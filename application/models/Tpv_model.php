<?php
class Tpv_model extends CI_Model {

	public $query;

	function __construct()
	{
		parent::__construct();
	}
	
	public function listarventas($desde, $hasta)
	{
		$this->db->select("*");
		$this->db->from('tb_transac_pventa a');
		$this->db->where("a.fecha_registro between '".$desde."' and '".$hasta."'");
		$this->db->where("a.tdoc IN ('01','03')");
		$this->db->order_by("FORMAT(a.date_created ,'HHmmss')");
        $query = $this->db->get();
		return $query->result();
	}
	
	public function listarEmpleados($id_perfil, $person_id)
	{
		$this->db->select("a.id AS id_emple, b.first_name,b.last_name,b.imagen,b.email");
		$this->db->from('tb_empleados a');
		$this->db->join('tb_datos b', 'a.person_id = b.person_id');
		if($id_perfil==5){//Mozo
			$this->db->where('a.id_perfil',$id_perfil);
		}else{
			$this->db->where('a.person_id',$person_id);
		}
		$this->db->where('a.deleted', 0);
		$query = $this->db->get();
		return $query->result();
	}

	public function listarMesas($id_mesa = 0)
	{
		$this->db->where("estado", 1);
		if($id_mesa <> 0)
			$this->db->where("id_mesa", $id_mesa);

		$this->query = $this->db->get("tb_pv_mesas");
		return $this->query->result();
	}

	public function listarCategorias()
	{
		$this->db->where("estado", 1);
		$this->query = $this->db->get("tb_productos_cat");
		return $this->query->result();
	}

	public function verEmpleado($id_emple)
	{
		$this->db->select("b.*");
		$this->db->from('tb_empleados a');
		$this->db->join('tb_datos b', 'a.person_id = b.person_id');
		$this->db->where('a.id', $id_emple);
        $query = $this->db->get();
		return $query->result();
	}
	
	/*
	public function actualizarEvento($data, $fecha)
	{
		$this->db->where("fecha", $fecha);
		$this->db->update("tb_tmp_cab_pventa", $data);
	}
	*/

	public function generarCodMaxPVCab($fecha)
	{
		$this->db->select_max("correlativo");
		$this->db->where("fecha", $fecha);
		$query = $this->db->get('tb_tmp_cab_pventa');
		$lis = $query->result();
		return $lis[0]->correlativo;
	}
	
	public function verDatoPVCab($fecha, $id_mesa, $id_emple = 0)
	{
		// $this->db->where("fecha", $fecha);
		$this->db->where_not_in("estado", 'C');
		$this->db->where("id_mesa", $id_mesa);
		/*solo activos */
		$this->db->where("isDelete", 0);
		if($id_emple <> 0)
			$this->db->where("id_emple", $id_emple);

		$this->query = $this->db->get("tb_tmp_cab_pventa");
		return $this->query->result();
	}

	public function listarEmpleadosGen()
    {
        $this->db->select("a.id AS id_emple, b.first_name,b.last_name,b.imagen,b.email,b.fecha_nace");
        $this->db->from('tb_empleados a');
        $this->db->join('tb_datos b', 'a.person_id = b.person_id');
        $this->db->where('a.id NOT IN(1)');
        $this->db->where('a.deleted=0');
    	$query = $this->db->get();
        return $query->result();
    }

    public function listarTipoPagos()
    {
		$this->query = $this->db->get("tb_tipo_pago");
		return $this->query->result();
    }

    public function verTipoPago($id_tp)
    {
    	$this->db->where('id_tp', $id_tp);
		$this->query = $this->db->get("tb_tipo_pago");
		return $this->query->result();
    }
	public function filtrarProductos($id_categoria)
	{
		$this->db->select("p.*, c.nombre AS categoria");
		$this->db->from('tb_productos p');
		$this->db->join('tb_productos_cat c', 'p.id_categoria = c.id_categoria');
		$this->db->where('p.id_categoria', $id_categoria);
		$this->db->order_by("p.nro_producto", "ASC");
        $query = $this->db->get();
		return $query->result();
	}

	// Verifica los STOCK del Insumo
	public function verificarStockInsumoProd($id_producto)
	{
		$this->db->select("a.id_serv_prov, a.id_unidad, u.valor, a.stock_porcion, a.stock_min");
		$this->db->from('tb_almacen_productos ap');
		$this->db->join('tb_almacen a', 'ap.id_almacen = a.id_almacen');
		$this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->where('ap.id_producto', $id_producto);
        $query = $this->db->get();
		return $query->result();
	}

	public function verificarStockInsumoProdAlmacen($id_producto)
	{
		$this->db->select("a.id_almacen, a.id_serv_prov, s.nombres, a.id_unidad, u.valor, a.cantidad,
							a.unidad_medida, a.costo,
								CASE
									WHEN (SELECT ap.porcion_mlts AS porcion_mlts
													FROM tb_almacen_productos ap
													WHERE ap.id_producto = p.id_producto AND ap.id_almacen = a.id_almacen) > 0  THEN ap.porcion_mlts
									ELSE a.valor_porcion
								END AS valor_porcion,
							a.stock_porcion,
								CASE
									WHEN (SELECT ap.costo_mlts AS costo_mlts
													FROM tb_almacen_productos ap
													WHERE ap.id_producto = p.id_producto AND ap.id_almacen = a.id_almacen) > 0  THEN ap.costo_mlts
									ELSE a.costo_porcion
								END AS costo_porcion,
							a.stock_min,
							p.id_producto, p.nombre");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_almacen_productos ap', 'a.id_almacen = ap.id_almacen');
        $this->db->join('tb_productos p', 'ap.id_producto = p.id_producto');
        $this->db->join('tb_servicio s', 'a.id_serv_prov = s.id_serv_prov');
        $this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->where('p.id_producto', $id_producto);
        $query = $this->db->get();
		return $query->result();
	}

	public function verCantProdSelectTMP($id_producto)
	{
		$this->db->select(" SUM(tpv.cantidad) as cant_prod_ins ");
		$this->db->from('tb_tmp_cab_pventa tcpv');
		$this->db->join('tb_tmp_pventa tpv', 'tcpv.id_tmp_cab = tpv.id_tmp_cab');
		$this->db->where('tcpv.fecha = CAST(getdate() as date)');
		$this->db->where('tcpv.estado', 'P');
		$this->db->where('tpv.id_producto', $id_producto);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->cant_prod_ins;
	}

	public function obtenerCorrelativoDetaTMP($id)
	{
		$this->db->select_max("correlativo");
		$this->db->where('id_tmp_cab', $id);
		$this->db->where('isDelete', 0);
		$query = $this->db->get('tb_tmp_pventa');
		$lis = $query->result();
		return $lis[0]->correlativo;
	}

	public function obtenerCorrelativoDetalle($id)
	{
		$this->db->select_max("correlativo");
		$this->db->where('id_tmp_cab', $id);
		$query = $this->db->get('tb_tmp_pventa');
		$lis = $query->result();
		return $lis[0]->correlativo;
	}

	public function verPrint($id,$row)
	{
		$this->db->select("print_comanda");
		$this->db->where('id_tmp_cab', $id);
		$this->db->where('correlativo', $row);
		$query = $this->db->get('tb_tmp_pventa');
		$lis = $query->result();
		return $lis[0]->print_comanda;
	}
	
	public function obtenerDesProducto($id_producto)
	{
		$this->db->select("p.*, c.nombre AS categoria");
		$this->db->from('tb_productos p');
		$this->db->join('tb_productos_cat c', 'p.id_categoria = c.id_categoria');
		$this->db->where('p.id_producto', $id_producto);
        $query = $this->db->get();
		return $query->result();
	}

	public function insertarTMPPuntoVentaCAB($data)
	{
		$this->db->insert('tb_tmp_cab_pventa', $data);
		return $this->db->insert_id();
	}

	public function insertarTMPPuntoVenta($data)
	{
		$this->db->insert('tb_tmp_pventa', $data);
	}

	public function listarTMPPuntoVenta($id_tmp_cab, $id_producto = 0, $id_categoria = 0)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("transac_venta", 0);
		$this->db->where("isDelete", 0);
		if($id_producto <> 0 && $id_categoria <> 0)
		{
			$this->db->where("id_producto", $id_producto);
			$this->db->where("id_categoria", $id_categoria);
		}
		$this->db->order_by("correlativo", "asc");
		$query = $this->db->get("tb_tmp_pventa");
		return $query->result();
	}

	public function listarTMPPuntoVentaDivMe($id_tmp_cab)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("isDelete", 0);
		$this->db->order_by("correlativo", "asc");
		$query = $this->db->get("tb_tmp_pventa");
		return $query->result();
	}


	public function listarTMPPuntoVentaCAB($id_tmp_cab)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$query = $this->db->get("tb_tmp_cab_pventa");
		return $query->result();
	}

	public function obtenerProdTMPTpv($id_tmp_cab, $correlativo)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("correlativo", $correlativo);
		$query = $this->db->get("tb_tmp_pventa");
		return $query->result();
	}

	public function eliminarProdTMPTpv($id, $id2,$empleado)
	{
		$this->db->query("UPDATE tb_tmp_pventa SET isDelete=1 ,persona_id_updated =$empleado ,date_updated=getdate() where id_tmp_cab=".$id." and correlativo=".$id2.";");
	}

	public function eliminarTMPTpvCab($id)
	{
		$this->db->query("UPDATE tb_tmp_cab_pventa SET isDelete=1, estado='C' where id_tmp_cab=".$id.";");
	}

	public function eliminarTMPTpv($id)
	{
		// $this->db->delete('tb_tmp_pventa', array('id_tmp_cab' => $id));
		$this->db->query("UPDATE tb_tmp_pventa SET isDelete=1 where transac_venta = 0 AND  id_tmp_cab=".$id.";");
	}

	public function insertarLogUsuarios($data)
	{
		$this->db->insert('tb_log_usuarios', $data);
	}

	// GRABAR TRANSACCIÓN DE VENTA	
	public function generarCodMax($id_serie) //tipo_doc
	{
		$this->db->select(" ISNULL(MAX(nfactu),0) AS num_doc ");
		$this->db->where('id_serie', $id_serie);
		$query = $this->db->get('tb_transac_pventa');
		$lis = $query->result();
		return $lis[0]->num_doc;
	}

	public function insertarTransacVenta($data)
	{
		$this->db->insert('tb_transac_pventa', $data);
		return $this->db->insert_id();
	}

	public function insertarTransacVentaDetalle($data)
	{
		$this->db->insert('tb_transac_pventa_detalle', $data);
	}

	public function actualizarTMPTpvCab($data, $id)
	{
		$this->db->where("id_tmp_cab", $id);
		$this->db->update("tb_tmp_cab_pventa", $data);
	}

	public function actualizarTransacVenta($data, $id)
	{
		$this->db->where("id_transac", $id);
		$this->db->update("tb_transac_pventa", $data);
	}

	public function listarTransacVentaCAB($id)
	{
		$this->db->where("id_transac", $id);
		$query = $this->db->get("tb_transac_pventa");
		return $query->result();
	}

	public function listarTransacVentaDetalle($id)
	{
		$this->db->where("id_transac", $id);
		$query = $this->db->get("tb_transac_pventa_detalle");
		return $query->result();
	}

	public function verTaxAnioBolsa($anio)
	{
		$this->db->where("anio", $anio);
		$query = $this->db->get("tb_pv_icbper");
		return $query->result();
	}

	public function verUsuarioVenta($id_tmp_cab)
	{
		$this->db->select("d.first_name");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->join('tb_tmp_cab_pventa cv', 'e.id = cv.id_emple');
		$this->db->where('cv.id_tmp_cab', $id_tmp_cab);
        $query = $this->db->get();
		return $query->result();
	}

	public function verEmpleadoVenta($id_emple)
	{
		$this->db->select("d.first_name");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->where('e.id', $id_emple);
        $query = $this->db->get();
		return $query->result();
	}

	public function verClienteVenta($id_cliente)
	{
		$this->db->select("b.*, a.razon_social");
		$this->db->from('tb_clientes a');
		$this->db->join('tb_datos b', 'a.person_id = b.person_id');
		$this->db->where('a.person_id', $id_cliente);
        $query = $this->db->get();
		return $query->result();
	}
	
	public function verClienteBoleta($id_cliente)
	{
		$this->db->select("a.*");
		$this->db->from('tb_pv_cliente_boleta a');
		$this->db->where('a.id', $id_cliente);
        $query = $this->db->get();
		return $query->result();
	}
	// Proceso de Notas COMANDA
	public function verTMPPuntoVenta($id_tmp_cab, $correlativo)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("correlativo", $correlativo);
		$query = $this->db->get("tb_tmp_pventa");
		return $query->result();
	}

	public function obtenerDatosNotaComanda($id)
	{
		$this->db->where("id", $id);
		$query = $this->db->get("tb_productos_nota_comanda");
		return $query->result();
	}

	public function actualizarTMPPuntoVenta($id, $id2, $data)
	{
		$this->db->where('id_tmp_cab', $id);
		$this->db->where('correlativo', $id2);
		$this->db->update('tb_tmp_pventa', $data);
	}

	public function insertarNotaComandaProd($data)
	{
		$this->db->insert('tb_productos_nota_comanda', $data);
		return $this->db->insert_id();
	}

	public function actualizarNotaComandaProd($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->where('estado', 1);
		$this->db->update('tb_productos_nota_comanda', $data);
	}

	// Proceso DIVIDIR CUENTA
	public function listarTMPPuntoVentaDCuenta($id_tmp_cab, $correlativo)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("correlativo", $correlativo);
		$query = $this->db->get("tb_tmp_pventa");
		return $query->result();
	}

	public function actualizarTMPPuntoVentaDCuenta($id, $data)
	{
		$this->db->where('id_tmp_cab', $id);
		$this->db->where("isDelete", 0);
		$this->db->where_not_in('dividir_cuenta', 2);		
		$this->db->update('tb_tmp_pventa', $data);
	}

	public function listarTMPPVTransacVentaDCuenta($id_tmp_cab, $transac_venta)
	{	
		$this->db->select(" COUNT(id_tmp_cab) AS valor ");
		$this->db->from('tb_tmp_pventa');
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->db->where("isDelete", 0);
		$this->db->where("transac_venta", $transac_venta);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->valor;
	}
	// Proceso CAMBIAR MESA
	public function listarMesasDisponibles($fecha)
	{
		$this->db->select('m.*');
		$this->db->from('tb_pv_mesas m');
		$this->db->where('m.estado',1);
		// $this->db->where("NOT EXISTS (
		// 						SELECT 1 FROM tb_tmp_cab_pventa tcp 
		// 						WHERE tcp.fecha = '".$fecha."'
		// 						AND tcp.id_mesa = m.id_mesa
		// 						AND tcp.estado NOT IN ('C')
		// 						AND tcp.isDelete=0
		// 					)");
		$this->db->where("NOT EXISTS (
			SELECT 1 FROM tb_tmp_cab_pventa tcp 
			WHERE tcp.id_mesa = m.id_mesa AND tcp.estado NOT IN ('C') AND tcp.isDelete=0
		)");
        $query = $this->db->get();
		return $query->result();
	}

	public function verDatoPVCabMDisponible($id_tmp_cab)
	{
		$this->db->where("id_tmp_cab", $id_tmp_cab);
		$this->query = $this->db->get("tb_tmp_cab_pventa");
		return $this->query->result();
	}

	public function actualizarTmpCabVenta($id_tmp_cab, $data)
	{
		$this->db->where('id_tmp_cab', $id_tmp_cab);
		$this->db->update('tb_tmp_cab_pventa', $data);
	}

	public function listarClientes()
	{
		$this->db->select("d.*, c.razon_social");
		$this->db->from('tb_datos d');
		$this->db->join('tb_clientes c', 'd.person_id = c.person_id');
        $query = $this->db->get();
		return $query->result();
	}

	public function insertVtaLog($data){
		$this->db->insert('tb_ventas_log', $data);
	}

	public function updateTmp_pventa($where, $data)
	{
		$this->db->where($where);
		$this->db->update('tb_tmp_pventa', $data);
	}

	public function updatetransac($where, $data)
	{
		$this->db->where($where);
		$this->db->update('tb_transac_pventa', $data);

	}

	public function limpMesas()
	{
		$this->db->query("EXEC usp_ic_act_vts_dia ");
	}

	public function disponibleMesa($id_mesa)
	{
		$this->db->select(" COUNT(*) AS valor ");
		$this->db->from('tb_tmp_cab_pventa');
		$this->db->where("id_mesa", $id_mesa);
		$this->db->where("isDelete", 0);
		$this->db->where("estado", 'P');
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->valor;
	}

	
}
