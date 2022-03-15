<?php
class Ventas_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar($tdoc,$sfactu,$nfactu,$desde,$hasta)
	{
		$this->db->select("pv.id_transac
		, pv.num_doc
		, concat(d.last_name,' ',d.first_name) AS empleado
		, m.alias
		, tp.tipo_pago
		, pv.moneda
		, pv.fecha_registro
		, ISNULL(tmpcab.hora_ini,'') hora_ini
		, ISNULL(tmpcab.hora_fin,'') hora_fin
		, pv.subtotal_venta
		, pv.igv
		, pv.total_venta
		, CONCAT(pv.tp_ruc,' | ',pv.n_ruc,' | ',pv.n_rs) as cliente
		, pv.tdoc
		, pv.isNC
		");
		$this->db->from('tb_transac_pventa pv');
		$this->db->join('tb_tipo_pago tp', 'pv.id_tp = tp.id_tp');
		$this->db->join('tb_tmp_cab_pventa tmpcab', 'pv.id_tmp_cab = tmpcab.id_tmp_cab','left');
		$this->db->join('tb_empleados e', 'tmpcab.id_emple = e.id','left');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id','left');
		$this->db->join('tb_pv_mesas m', 'tmpcab.id_mesa = m.id_mesa','left');
		$this->db->where("pv.fecha_registro BETWEEN '$desde' AND '$hasta' ");
		if($sfactu <> '' and $nfactu<>'' ){
			$this->db->where("pv.sfactu", $sfactu);
			$this->db->where("pv.nfactu", $nfactu);		
		}
		if($tdoc<>0) {
			$this->db->where("pv.tdoc", $tdoc);
		}		
		$this->db->order_by("pv.id_transac", "desc");
        $query = $this->db->get();
		return $query->result();
	}

	public function verCierres()
	{
		$this->db->select("a.*,concat(b.first_name,' ',b.last_name) AS empleado");
		$this->db->from('tb_cerrar_caja a');
		$this->db->join('tb_datos b', 'a.persona_id_created = b.person_id');		
        $query = $this->db->get();
		return $query->result();
	}

	public function verdetcierre($id_cierre)
	{
		$this->db->select("tp.tipo_pago,a.num_doc,a.total_venta,a.fecha_registro");
		$this->db->from('tb_transac_pventa a');		
		$this->db->join('tb_tipo_pago tp', 'a.id_tp=tp.id_tp');		
		$this->db->where('a.id_cierre', $id_cierre);		
		$this->db->order_by('tp.tipo_pago', 'ASC');	
		$this->db->order_by('a.id_transac', 'ASC');	
        $query = $this->db->get();
		return $query->result();
	}

	public function grupodetcierre($id_cierre)
	{
		$this->db->select("tp.tipo_pago,SUM(a.total_venta) AS total_venta");
		$this->db->from('tb_transac_pventa a');		
		$this->db->join('tb_tipo_pago tp', 'a.id_tp=tp.id_tp');		
		$this->db->where('a.id_cierre', $id_cierre);		
		$this->db->group_by("tp.tipo_pago");
		$this->db->order_by('tp.tipo_pago', 'ASC');	
        $query = $this->db->get();
		return $query->result();
	}


	public function ver($id)
	{
		$this->db->select("pv.*, tp.tipo_pago, tmpcab.hora_ini, tmpcab.hora_fin, tmpcab.correlativo AS correlativo_venta, tmpcab.estado, e.username, concat(d.last_name, ' ', d.first_name) AS empleado, m.mesa");
		$this->db->from('tb_transac_pventa pv');
		$this->db->join('tb_tipo_pago tp', 'pv.id_tp = tp.id_tp');
		// Inner Join para Clientes Aqui!
		$this->db->join('tb_tmp_cab_pventa tmpcab', 'pv.id_tmp_cab = tmpcab.id_tmp_cab');
		$this->db->join('tb_empleados e', 'tmpcab.id_emple = e.id');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id');
		$this->db->join('tb_pv_mesas m', 'tmpcab.id_mesa = m.id_mesa');
		$this->db->where('pv.id_transac', $id);		
        $query = $this->db->get();
		return $query->result();
	}

	public function verVentasXDia($fecha)
	{
		$this->db->select("pv.*, tp.tipo_pago, tmpcab.hora_ini, tmpcab.hora_fin, tmpcab.correlativo AS correlativo_venta, tmpcab.estado, e.username, concat(d.last_name, ' ', d.first_name) AS empleado, m.mesa, m.alias");
		$this->db->from('tb_transac_pventa pv');
		$this->db->join('tb_tipo_pago tp', 'pv.id_tp = tp.id_tp');
		// Inner Join para Clientes Aqui!
		$this->db->join('tb_tmp_cab_pventa tmpcab', 'pv.id_tmp_cab = tmpcab.id_tmp_cab');
		$this->db->join('tb_empleados e', 'tmpcab.id_emple = e.id');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id');
		$this->db->join('tb_pv_mesas m', 'tmpcab.id_mesa = m.id_mesa');
		$this->db->where('pv.fecha_registro',$fecha );
		$this->db->or_where('pv.id_cierre is null');
		$this->db->order_by("pv.id_transac", "desc");
        $query = $this->db->get();
		return $query->result();
	}

	public function sumaVentasXDia($fecha)
	{
		$this->db->select_sum("total_venta");
		$this->db->from('tb_transac_pventa pv');
		$this->db->where('pv.fecha_registro',$fecha );
		$this->db->or_where('pv.id_cierre is null');
        $query = $this->db->get();
		return $query->result();
	}

	public function verDetalleVenta($id)
	{
		$this->db->where('id_transac', $id);
		$query = $this->db->get('tb_transac_pventa_detalle');
		return $query->result();
	}

	public function listarTP()
	{
		$query = $this->db->get('tb_tipo_pago');
		return $query->result();
	}

	// -- PROCESO CERRAR CAJA
	public function verTurnoCaja($fecha)
	{
		$this->db->select("ISNULL(MAX(turno),0) as turno");
		$this->db->where("fecha_cierre", $fecha);
		$query = $this->db->get('tb_cerrar_caja');
		$lis = $query->result();
		return $lis[0]->turno;
	}
	public function verTotalVentaXTurto($fecha)
	{
		$this->db->select("COUNT(*) AS total_venta_turno");
		$this->db->from('tb_transac_pventa');
		$this->db->where('id_cierre IS NULL');
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->total_venta_turno;
	}

	// public function verTotalVentaCierreCaja($fecha)
	// {
	// 	$this->db->select("COUNT(*) AS total_venta_turno");
	// 	$this->db->from('tb_cerrar_caja');
	// 	$this->db->where('fecha_cierre',$fecha);
	// 	$query = $this->db->get();
	// 	$lis = $query->result();
	// 	return $lis[0]->total_venta_turno;
	// }

	public function verListaVentaCierreCaja($fecha)
	{
		$this->db->select("*");
		$this->db->from('tb_transac_pventa');
		$this->db->where("fecha_registro", $fecha);
		// $this->db->where('id_cierre IS NULL');
		$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}
	public function verListaVentaCierreCajaCTurno($fecha)
	{
		$this->db->select("*");
		$this->db->from('tb_transac_pventa');
		$where =" id_cierre IS NULL";
		$this->db->where($where);
		// $this->db->where("fecha_registro", $fecha);
		// $this->db->where('id_cierre IS NULL');
		//$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}
	public function verListaVentaGrupalesTPCturno($fecha)
	{
		$this->db->select("id_tp");
		$this->db->from('tb_transac_pventa');
		$where =" id_cierre IS NULL";
		$this->db->where($where);
		// $this->db->where("fecha_registro", $fecha);
		// $this->db->where('id_cierre IS NULL');
		$this->db->group_by('id_tp'); 
		$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}
	public function verListaVentaGrupalesTP($fecha)
	{
		$this->db->select("id_tp");
		$this->db->from('tb_transac_pventa');
		$where =" fecha_registro='$fecha' OR id_cierre IS NULL";
		$this->db->where($where);
		// $this->db->where("fecha_registro", $fecha);
		// $this->db->where('id_cierre IS NULL');
		$this->db->group_by('id_tp'); 
		$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}
	//cierre de caja
	public function verListaVentaXTPago($fecha, $id_tp)
	{
		$this->db->select("*");
		$this->db->from('tb_transac_pventa');
		$this->db->where("id_tp", $id_tp);
		$where =" (fecha_registro='$fecha' OR id_cierre IS NULL)";
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	//cambio de turno
	public function verListaVentaXTPagoCaja( $id_tp)
	{
		$this->db->select("*");
		$this->db->from('tb_transac_pventa');
		$this->db->where("id_tp", $id_tp);
		// $where ="(fecha_registro='$fecha' OR id_cierre IS NULL)";
		// $this->db->where($where);
		$this->db->where('id_cierre IS NULL');
		$query = $this->db->get();
		return $query->result();
	}

	public function insertarCierreCaja($data)
	{
		$this->db->insert('tb_cerrar_caja', $data);
		return $this->db->insert_id();
	}


	public function verTotalTicketCajaXDia($fecha)
	{
		$this->db->select("SUM(total_ticket) AS total_ticket");
		$this->db->from('tb_cerrar_caja');
		$this->db->where("fecha_cierre", $fecha);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->total_ticket;
	}

	public function verListaVentaGrupalesTPCaja($fecha)
	{
		$this->db->select("id_tp");
		$this->db->from('tb_transac_pventa');
		// $where =" fecha_registro='$fecha' OR id_cierre IS NULL";
		// $this->db->where($where);
		$this->db->where("fecha_registro", $fecha);
		// $this->db->where('id_cierre IS NULL');
		$this->db->group_by('id_tp'); 
		$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}

	public function verTotalesCierreCaja($fecha)
	{
		$this->db->select("SUM(total_efectivo) AS total_efectivo,
							 SUM(total_tarjetas) AS total_tarjetas,
							 SUM(total_caja) AS total_caja");
		$this->db->from('tb_cerrar_caja');
		$this->db->where("fecha_cierre", $fecha);
		//$this->db->order_by("id_tp", "ASC");
		$query = $this->db->get();
		return $query->result();
	}

	public function verEstadoTicketVenta($id)
	{
		$this->db->select(" id_cierre AS id_cierre ");
		$this->db->from('tb_transac_pventa');
		$this->db->where("id_transac", $id);
		$this->db->limit(1);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->id_cierre;
	}

	public function anularTicketVenta($data, $id)
	{
		$this->db->where("id_transac", $id);
		$this->db->update("tb_transac_pventa", $data);	
	}
	// --

	
    // Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2)
	{
        $sql = "SELECT * FROM EXCEL_VENTAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_serie = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_serie = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
				
		if($anulado == 'V')
			$sql .= "  AND a.estado = 'V' ";
		
		if($cbo_2 > 0)
			$sql .= "  AND a.id_tp = '$cbo_2' ";
	
			$sql .= " ORDER BY a.fecha_registro DESC;";
		//echo "<br><br><br>".$sql;
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function filtrarRC($fecha1, $fecha2)
	{
       $sql = "SELECT * FROM EXCEL_VENTAS_RC a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function filtrarRDP($fecha1, $fecha2, $cbo_1)
	{
		$sql = "SELECT a.producto, SUM(a.cantidad) AS venta, SUM(a.total) as total FROM EXCEL_VENTAS_RDP a ";

		if($cbo_1 == '0' && $fecha1 != '0' && $fecha2 != '0') 
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') AND a.id_producto = '$cbo_1'";

			$sql .= " GROUP BY a.producto";
			$sql .= " ORDER BY venta DESC";
			
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function filtrarVentaBar($fecha1, $fecha2, $cbo_1)
	{
		$sql = "SELECT a.nro_doc, a.id, a.barista, a.username, a.fecha, SUM(a.total_venta) AS total_venta FROM EXCEL_VENTAS_BARISTA a";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " GROUP BY a.nro_doc, a.id, a.barista, a.username, a.fecha";
			$sql .= " ORDER BY a.fecha DESC;";
		
		//echo "<br><br><br>".$sql;
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function filtrarMB($fecha1, $fecha2)
	{
       $sql = "SELECT * FROM EXCEL_MESAS_BORRADAS a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro DESC";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function filtrarCCB($fecha1, $fecha2)
	{
       $sql = "SELECT * FROM EXCEL_COMANDAS a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro DESC";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function filtrarMBCOM($fecha1, $fecha2)
	{
       $sql = "SELECT * FROM excel_comandas_borradas a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_borrada >= '$fecha1' and a.fecha_borrada <= '$fecha2') ";

			$sql .= " ORDER BY a.id DESC";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function filtrar_vfdm($fecha1, $fecha2)
	{
       $sql = "SELECT tpv.num_doc,
       tpv.tdoc,
       tpv.sfactu,
       tpv.nfactu,
       tpv.moneda,
       tpv.subtotal_venta,
       tpv.igv,
       tpv.total_venta,
       (CASE tpv.id_tp WHEN 6 THEN tpv.pago_cliente ELSE 0 END) AS efectivo,
       (CASE tpv.id_tp WHEN 6 THEN tpv.vuelto ELSE 0 END) AS tarjeta,
       tpv.fecha_registro,
       tp.tipo_pago,
       d.nro_doc,
	   c.razon_social,
       tpv.icbper_total,
       tpv.icbper_cant
		FROM   dbo.tb_transac_pventa AS tpv INNER JOIN
		dbo.tb_tipo_pago AS tp ON tpv.id_tp = tp.id_tp LEFT OUTER JOIN
		dbo.tb_datos AS d ON tpv.id_cliente = d.person_id LEFT OUTER JOIN
		dbo.tb_clientes AS c ON d.person_id = c.person_id
		WHERE tpv.fecha_registro BETWEEN '".$fecha1."' AND '".$fecha2."' AND 
		tpv.tdoc IN ('01','03','07')
		ORDER BY d.fecha_registro,tpv.num_doc";

		$query = $this->db->query($sql);
		return $query->result();
	}



	public function resumenDiario($fecha,$id_user){
		
		$query = $this->db->query("EXEC usp_ic_insert_resumen '$fecha',$id_user ");
		// $query = $this->db->query("EXEC usp_ic_insert_resumen '20200930',1");
		return $query->result();
	}
	public function resumencabecera(){

		$query = $this->db->get('tb_resumendiario_cab');
		return $query->result();
	}
	public function ver_resumen($id){
		$this->db->where('id_resumen',$id);
		$query = $this->db->get('tb_resumendiario_cab');
		return $query->result();
	}

	public function actresumen($id,$data){
		// $this->db->query("UPDATE tb_resumendiario_cab set ntickect='$ntickect' where id_resumen=$id_resumen");
		$this->db->where("id_resumen", $id);
		$this->db->update("tb_resumendiario_cab", $data);	
	}

	public function generarDetalleNC($id_nc,$id_transac){
		$this->db->query("INSERT INTO tb_transac_pventa_detalle (id_transac,id_producto,id_categoria,correlativo,producto,categoria,cantidad,venta,total)
		select $id_nc,id_producto,id_categoria,correlativo,producto,categoria,cantidad,venta,total FROM tb_transac_pventa_detalle WHERE id_transac=$id_transac
		ORDER BY correlativo");
	}

	public function veriTipoPago($id)
	{
		$this->db->select("tipo_pago");
		$this->db->where("id_tp", $id);
		$query = $this->db->get('tb_tipo_pago');
		$lis = $query->result();
		return $lis[0]->tipo_pago;
	}

	public function actvtaNC($id)
	{
		$data = array('isNC' => 1);
		$this->db->where("id_transac", $id);
		$this->db->update("tb_transac_pventa", $data);	
	}


	/**datos PDF */
	public function ventaCab($id_transac)
	{
		$query = $this->db->query("EXEC sp_cab_transac_cabecera 1,$id_transac ");
		return $query->result();
	}
}