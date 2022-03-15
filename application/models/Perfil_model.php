<?php
class Perfil_model extends CI_Model {

	public $query;

	function __construct()
	{
		parent::__construct();
	}
	
	public function listar($perfil)
	{
		$this->db->select("a.*,b.nom_perfil"); 
		$this->db->from("tb_modules_accion a");
		$this->db->join('tb_perfiles b', 'a.id_perfil = b.id_perfil');
		$this->db->where("a.id_perfil", $perfil);
        $query = $this->db->get();
		return $query->result();
	}
	
	public function insert($data)
	{
		$this->db->insert('tb_modules_accion', $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->where($where);
		$this->db->update('tb_modules_accion', $data);
	}

	public function getById($id)
	{
		$this->db->where("id_ma",$id);
        $query = $this->db->get('tb_modules_accion');
		return $query->result();
	}
	
	public function eliminar($id)
	{
		$this->db->where("id_ma", $id);
        $this->db->delete('tb_modules_accion');
	}
}
