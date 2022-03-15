<?php
class Compras_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("c.*, d.tipo_doc as tipo_doc_prov, d.nro_doc as doc_prov, p.person_id, p.razon_social, p.nombre_corto, p.tipo_prov");
		$this->db->from('tb_compras c');
		$this->db->join('tb_proveedores p', 'c.prov_id = p.person_id');
        $this->db->join('tb_datos d', 'p.person_id = d.person_id');
		$this->db->order_by("c.id_compra", "desc");   
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	// --
	public function listarMonedas()
	{
        $query = $this->db->get('tb_monedas');
		return $query->result();
	}

	public function listarUnidades()
	{
        $query = $this->db->get('tb_unidades');
		return $query->result();
	}

	public function verMoneda($id)
	{
		$this->db->where('id_compra', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_compras');
		$lis = $query->result();
		return $lis[0]->moneda;
	}

	public function verTC($id)
	{
		$this->db->where('id_compra', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_compras');
		$lis = $query->result();
		return $lis[0]->tc;
	}

	public function verIGV($id)
	{
		$this->db->where('id_compra', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_compras');
		$lis = $query->result();
		return $lis[0]->porc_igv;
	}

	public function obtenerCorrelativoDetaCompra($id)
	{
		$this->db->select_max("correlativo");
		$this->db->where('id_compra', $id);
		$query = $this->db->get('tb_compras_detalle');
		$lis = $query->result();
		return $lis[0]->correlativo;
	}

	public function verOCXProveedor($person_id)
	{
		$this->db->select('o.id_oc, o.num_oc');
		$this->db->from('tb_orden_compra o');
		$this->db->join('tb_proveedores p', 'o.person_id = p.person_id');
		$this->db->where('o.person_id', $person_id);
		$this->db->where('o.estado', 'C');
		$this->db->where('NOT EXISTS 
								(
									SELECT 1 FROM tb_compras c WHERE c.id_oc = o.id_oc
								)');
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}
	// --

	public function insertarCab($data)
	{
		$this->db->insert('tb_compras', $data);
		return $this->db->insert_id();
	}

	public function insertarDetalle($data)
	{
		$this->db->insert('tb_compras_detalle', $data);
	}

	public function listarDetalles($id)
	{
        $this->db->select("c.*, s.nombres as servicio, u.valor as unidad");
		$this->db->from('tb_compras_detalle c');
		$this->db->join('tb_servicio s', 'c.id_serv_prov = s.id_serv_prov');
        $this->db->join('tb_unidades u', 'c.id_unidad = u.id_unidad');
        $this->db->where('c.id_compra', $id);
        $query = $this->db->get();
		return $query->result();
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_compra", $id);
		$this->db->update("tb_compras", $data);		
		$this->db->trans_complete();
	}

	/*
	public function actualizarDetalle($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("person_id", $id);
		$this->db->update("tb_compras_detalle", $data);		
		$this->db->trans_complete();
	}
	*/

	public function ver($id)
	{
		$this->db->select("c.*, d.tipo_doc as tipo_doc_prov, d.nro_doc as doc_prov, p.person_id, p.razon_social, p.nombre_corto, p.tipo_prov");
		$this->db->from('tb_compras c');
		$this->db->join('tb_proveedores p', 'c.prov_id = p.person_id');
        $this->db->join('tb_datos d', 'p.person_id = d.person_id');
		$this->db->where('c.id_compra', $id);
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function eliminarDetalle($id, $id2)
	{
		$this->db->delete('tb_compras_detalle', 
								array(
										'id_compra' => $id,
										'correlativo' => $id2
								)
						 );
	}

	// -- Dato duplicado
	public function validarCod($id)
    {
        $this->db->where('nro_doc', $id);                                 
        $this->db->from('tb_compras');
        $query = $this->db->get();
        if($query->result())
            return 1;
        else
            return 0;  
    }

    // Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_COMPRAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.situacion = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.situacion = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}