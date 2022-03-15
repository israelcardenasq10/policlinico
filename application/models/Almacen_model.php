<?php
class Almacen_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("a.id_almacen, a.id_serv_prov, s.nombres, a.id_unidad, u.valor AS unidad, a.cantidad, a.costo, a.stock_min, a.valor_porcion, a.stock_porcion, a.costo_porcion, a.tipo_almacen, a.fecha_registro, a.fecha_modifica, a.id_owner, d.tipo_doc as tipo_doc_prov, d.nro_doc as doc_prov, p.person_id, p.razon_social, p.nombre_corto, p.tipo_prov");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->join('tb_servicio s', 'a.id_serv_prov = s.id_serv_prov');
		$this->db->join('tb_proveedores p', 'a.id_prov = p.person_id');
        $this->db->join('tb_datos d', 'p.person_id = d.person_id');
        $this->db->where('a.deleted IS NULL');
		//$this->db->order_by("a.id_almacen", "desc");
			$this->db->order_by("s.nombres", "ASC");
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	// --
	public function listarServiciosXProvAlmacen($person_id)
	{
		$this->db->select("a.id_prov, s.id_serv_prov, s.id_categoria, s.nombres, a.id_unidad, u.valor AS unidad, a.costo");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->join('tb_servicio s', 'a.id_serv_prov = s.id_serv_prov');
		//$this->db->join('tb_servicio_prov sp', 's.id_serv_prov = sp.id_serv_prov');
		$this->db->join('tb_proveedores p', 'a.id_prov = p.person_id');
		$this->db->where('a.id_prov', $person_id);
		$this->db->where('a.deleted IS NULL');
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function verDatosAlmacenServ($id)
	{
		$this->db->where('id_serv_prov', $id);
		$this->db->where("deleted IS NULL");
		$this->db->limit(1);
		$query = $this->db->get('tb_almacen');
		//$lis = $query->result();
		return $query->result();
		//return $lis[0]->cantidad;
	}

	public function actualizarAlmacenServicio($id, $data)
	{
		$this->db->where("id_serv_prov", $id);
		$this->db->where("deleted IS NULL");
		$this->db->update("tb_almacen", $data);
	}

	public function verProductoAlmacen($id_almacen)
	{
		$this->db->select("DISTINCT(ap.id_producto) AS id_producto, a.id_almacen, a.id_serv_prov, a.id_unidad");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_almacen_productos ap', 'a.id_almacen = ap.id_almacen');
		$this->db->where('ap.id_almacen', $id_almacen);
		$query = $this->db->get();
		return $query->result();
	}
	// --

	// Metodo de Mermas
	public function verAlmacenMerma($id_almacen)
	{
		$this->db->select("a.id_almacen, a.id_prov, a.id_serv_prov, a.id_unidad, a.stock_porcion, u.valor, p.person_id, p.razon_social");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->join('tb_proveedores p', 'a.id_prov = p.person_id');
        $this->db->join('tb_datos d', 'p.person_id = d.person_id');
        $this->db->where('a.id_almacen', $id_almacen);
        $query = $this->db->get();
		return $query->result();
	}
	// --

	public function insertar($data)
	{
		$this->db->trans_start();
		$this->db->insert('tb_almacen', $data);
		$this->db->trans_complete();
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_almacen", $id);
		$this->db->update("tb_almacen", $data);		
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->select("a.* ");
		$this->db->from('tb_almacen a');
		$this->db->where('a.id_almacen', $id);
        $query = $this->db->get();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->where('id_almacen', $id);
		$this->db->update('tb_almacen', array('deleted' => 1));
		$this->db->trans_complete();
	}

    // Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_ALMACEN a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.unidad = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.unidad = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}