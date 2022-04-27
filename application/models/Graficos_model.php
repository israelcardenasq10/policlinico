<?php
class Graficos_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function graficarVentaProductoXDia()
	{
		$fecha  = mdate("%Y-%m-%d");
		$this->db->select("tpvd.producto, SUM(tpvd.cantidad) as cantidad_venta");
		$this->db->from('tb_transac_pventa tpv');
		$this->db->join('tb_transac_pventa_detalle tpvd', 'tpv.id_transac = tpvd.id_transac');
		$this->db->where("tpv.fecha_registro",$fecha);
		$this->db->where('tpvd.id_producto <> 216');
        $this->db->group_by('tpvd.producto');
		$this->db->order_by("cantidad_venta", "desc");
		$this->db->limit(5);
        $query = $this->db->get();
		return $query->result();
	}

	/*
	public function graficarVentaXDiaSemana()
	{
		$this->db->select("tpv.fecha_registro, tpv.moneda, SUM(tpv.costo) costo, SUM(tpv.total_venta) total_venta");
		$this->db->from('tb_transac_pventa tpv');
        $this->db->where('tpv.fecha_registro 
							BETWEEN
								DATE_SUB(CURRENT_DATE(), INTERVAL DAYOFWEEK(CURRENT_DATE())-2 DAY)
							AND 
								DATE_ADD(CURRENT_DATE(), INTERVAL 7-DAYOFWEEK(CURRENT_DATE()) DAY)');
        $this->db->group_by('tpv.fecha_registro');
		$this->db->order_by("tpv.fecha_registro", "ASC");
        $query = $this->db->get();
		return $query->result();
	}
	*/
	public function graficarVentaXDiaSemana($fecha)
	{
		// $fecha='2020-11-05';
		$this->db->select("tpv.fecha_registro, tpv.moneda, SUM(tpv.costo) costo, SUM(tpv.total_venta) total_venta");
		$this->db->from('tb_transac_pventa tpv');
        $this->db->where('tpv.fecha_registro', $fecha);
        $this->db->group_by('tpv.fecha_registro, tpv.moneda');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	public function graficarCostoVentaXMesesAnio()
	{
        // $sql = "EXEC SP_COSTOS_VENTAS_MENSUAL";
        $sql = "CALL SP_COSTOS_VENTAS_MENSUAL";
        $query = $this->db->query($sql);
		return $query->result();
	}

    // // Reportes
    // public function filtrar($fecha1, $fecha2, $cbo_1)
	// {
        
	// }
}