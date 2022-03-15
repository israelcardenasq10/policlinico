<?php
class InventarioCat_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
        $query = $this->db->get('tb_inventario_cat');
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_inventario_cat', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->where('id_cat', $id);
		$this->db->update('tb_inventario_cat', $data);
	}

	public function ver($id)
	{
		$this->db->where('id_cat', $id);
		$query = $this->db->get('tb_inventario_cat');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->delete('tb_inventario_cat', array('id_cat' => $id));
	}


	// -- Dato duplicado
	public function validarCod($id)
    {
        $this->db->where('id_cat', $id);                                 
        $this->db->from('tb_inventario_cat');
        $query = $this->db->get();
        if($query->result())
            return 1;
        else
            return 0;  
    }

}