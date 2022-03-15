<?php
class Mermas_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("am.id_merma, am.stock_actual, am.stock_merma, a.id_almacen, a.id_prov, a.id_serv_prov, a.id_unidad, s.nombres, u.valor, p.razon_social, am.fecha_registro, am.id_owner");
		$this->db->from('tb_almacen_mermas am');
		$this->db->join('tb_almacen a', 'am.id_almacen = a.id_almacen');
		$this->db->join('tb_servicio s', 'a.id_serv_prov = s.id_serv_prov');
		$this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->join('tb_proveedores p', 'a.id_prov = p.person_id');
        $this->db->join('tb_datos d', 'p.person_id = d.person_id');
        $this->db->order_by("am.fecha_registro", "DESC");
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_almacen_mermas', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_merma", $id);
		$this->db->update("tb_almacen_mermas", $data);
		$this->db->trans_complete();
	}

	public function ver($id)
	{

	}

	public function eliminar($id)
	{
		
	}

	// Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_MERMAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_serv_prov = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_serv_prov = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

}