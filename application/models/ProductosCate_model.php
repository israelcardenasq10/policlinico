<?php
class ProductosCate_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$query = $this->db->get('tb_productos_cat');
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_productos_cat', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_categoria", $id);
		$this->db->update("tb_productos_cat", $data);
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->where('id_categoria', $id);
		$query = $this->db->get('tb_productos_cat');
		return $query->result();
	}

	public function eliminar($id)
	{
		//$this->db->where("id_categoria", $id);
		//$this->db->update("tb_productos_cat", array('estado' => '0' ));
		$this->db->delete('tb_productos_cat', array('id_categoria' => $id));
	}

}