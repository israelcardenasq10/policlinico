<?php
class Mesas_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
        $this->db->select("a.*");               
        $this->db->from('tb_pv_mesas a');
        $this->db->order_by('a.id_mesa', 'DESC');
        $query = $this->db->get();
        //echo "<br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_pv_mesas', $data);
	}

	public function actualizar($id, $data)
    {
		$this->db->where('id_mesa', $id);
		$this->db->update('tb_pv_mesas', $data);
	}

	public function ver($id)
	{
		$this->db->where('id_mesa', $id);
		$query = $this->db->get('tb_pv_mesas');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->delete('tb_pv_mesas', array('id_mesa' => $id));
	}

}