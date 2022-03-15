<?php
class Orden_compra_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select('o.*, p.razon_social');
		$this->db->from('tb_orden_compra o');
		$this->db->join('tb_proveedores p', 'o.person_id = p.person_id');
		$this->db->order_by("o.num_oc", "desc");   
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	// --
	public function generarCodMax()
	{
		$this->db->select_max("num_oc");
		$query = $this->db->get('tb_orden_compra');
		$lis = $query->result();
		return $lis[0]->num_oc;
	}

	public function listarServiciosXProvOC($person_id, $num_oc)
	{
		$this->db->select("oc.person_id, ocd.*, s.nombres, u.valor AS unidad");
		$this->db->from('tb_orden_compra oc');
		$this->db->join('tb_orden_compra_detalle ocd', 'oc.id_oc = ocd.id_oc');
		$this->db->join('tb_unidades u', 'ocd.id_unidad = u.id_unidad');
		$this->db->join('tb_servicio s', 'ocd.id_serv_prov = s.id_serv_prov');
		//$this->db->join('tb_servicio_prov sp', 's.id_serv_prov = sp.id_serv_prov');
		$this->db->where('oc.person_id', $person_id);  
		$this->db->where('oc.num_oc', $num_oc);
        $query = $this->db->get();
		return $query->result();
	}

	public function obtenerNumOC($id)
	{
		$this->db->where('id_oc', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_orden_compra');
		$lis = $query->result();
		return @$lis[0]->num_oc;
	}

	public function verDatosProveedor($id)
	{
		$this->db->where('person_id', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_datos');
		$lis = $query->result();
		return $lis;
	}

	public function verCodAlmacenOC($id_almacen)
	{
		$this->db->where('id_almacen', $id_almacen);
		$this->db->limit(1);
		$query = $this->db->get('tb_orden_compra');
		if($query->result())
			return true;
		else
			return false;
	}
	// --

	public function insertarCab($data)
	{
		$this->db->insert('tb_orden_compra', $data);
		return $this->db->insert_id();
	}

	public function insertarDetalle($data)
	{
		$this->db->insert('tb_orden_compra_detalle', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("num_oc", $id);
		$this->db->update("tb_orden_compra", $data);
		$this->db->trans_complete();
	}

	public function actualizarDetalle($id, $id2, $data)
	{
		$this->db->where("id_oc", $id);
		$this->db->where("correlativo", $id2);
		$this->db->update("tb_orden_compra_detalle", $data);
	}

	public function ver($id)
	{

	}

	public function eliminar($id)
	{
		
	}

}