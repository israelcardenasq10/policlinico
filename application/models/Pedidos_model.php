<?php
class Pedidos_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar($desde,$hasta)
	{
		$this->db->select("a.id_tmp_cab
		,d.first_name AS empleado
		,LEFT(a.hora_ini,8) AS hora_ini
		,LEFT(a.hora_fin,8) AS hora_fin
		,FORMAT(a.fecha,'yyyy-MM-dd') AS fecha
		,a.correlativo
		,a.estado
		,IIF(a.isDelete=0,'','SI') AS isDelete
		,m.mesa
		,a.total_venta
		");
		$this->db->from('tb_tmp_cab_pventa a');
		$this->db->join('tb_empleados e', 'a.id_emple = e.id','left');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id','left');
		$this->db->join('tb_pv_mesas m', 'a.id_mesa = m.id_mesa','left');
		$this->db->where("a.fecha BETWEEN '$desde' AND '$hasta' ");		
		
        $query = $this->db->get();
		return $query->result();
	}

	public function ver($id)
	{
		$this->db->select("a.*,e1.first_name AS created ,e2.first_name AS updated");
		$this->db->from('tb_tmp_pventa a');
		$this->db->join('tb_datos e1', 'e1.person_id = a.persona_id_created','left');
		$this->db->join('tb_datos e2', 'e2.person_id = a.persona_id_updated','left');
		$this->db->where('a.id_tmp_cab', $id);		
        $query = $this->db->get();
		return $query->result();
	}
	
	public function listar2($desde,$hasta)
	{
		$this->db->select("a.*,e1.first_name AS created ,e2.first_name AS updated, b.correlativo AS nro_ped		
		,CONVERT(varchar(20),b.hora_ini,100) AS hora_ini
		,CONVERT(varchar(20),b.hora_fin,100) AS hora_fin
		,FORMAT(b.fecha,'yyyy-MM-dd') AS fecha");
		$this->db->from('tb_tmp_pventa a');
		$this->db->join('tb_tmp_cab_pventa b','a.id_tmp_cab = b.id_tmp_cab','left');
		$this->db->join('tb_datos e1', 'e1.person_id = a.persona_id_created','left');
		$this->db->join('tb_datos e2', 'e2.person_id = a.persona_id_updated','left');
		
		$this->db->where("FORMAT(a.date_created,'yyyy-MM-dd') BETWEEN '$desde' AND '$hasta' ");		
		
        $query = $this->db->get();
		return $query->result();
	}

}