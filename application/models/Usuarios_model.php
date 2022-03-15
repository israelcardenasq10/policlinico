<?php
class Usuarios_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("e.id, d.person_id, d.nro_doc, concat(d.last_name, ' ', d.first_name) AS nombres, d.email, e.username, e.deleted, p.nom_perfil, d.imagen");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->join('tb_perfiles p', 'e.id_perfil = p.id_perfil');
		//$this->db->where('e.deleted', 0);
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();     
		return $query->result();
	}
	// --
	public function listarEmpleados()
	{
		
		$this->db->select("d.person_id, concat(d.last_name, ' ', d.first_name) AS nombres");
		$this->db->from('tb_datos d');
		$this->db->where('d.tipo_user', 'emp');
		$this->db->where('NOT EXISTS
									(
										SELECT 1 FROM tb_empleados e
										WHERE e.person_id = d.person_id
									)');
		$query = $this->db->get();
		//echo "<br/><br/><br/><br/>".$this->db->last_query();   
		return $query->result();
	}

	public function listarPerfiles()
	{
		$this->db->where('id_perfil <> 1');
		$query = $this->db->get('tb_perfiles');
		return $query->result();
	}

	public function verEmpleado($person_id)
	{
		$this->db->select("d.person_id, concat(d.last_name, ' ', d.first_name) AS nombres");
		$this->db->from('tb_datos d');
		$this->db->where('d.person_id', $person_id);
		$query = $this->db->get();
		return $query->result();
	}
	// --

	public function insertar($data)
	{
		$this->db->trans_start();
		$this->db->insert('tb_empleados', $data);
		$this->db->trans_complete();
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where('id', $id);
		$this->db->update('tb_empleados', $data);
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('tb_empleados');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->delete('tb_empleados', array('id' => $id));
		//$this->db->where('id', $id);
		//$this->db->update('tb_empleados', array('deleted' => 1));
		$this->db->trans_complete();
	}

}