<?php
class Privilegios_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_allowed_modules($person_id, $perfil_id)
	{
		$this->db->select('DISTINCT(p.id_perfil), p.nom_perfil, m.module_id, m.name_lang_key, m.desc_lang_key, m.sort, m.parent, m.alias'); 
		$this->db->from('tb_empleados e');
		$this->db->join('tb_perfiles p','e.id_perfil = p.id_perfil');
		$this->db->join('tb_modules_accion ma','p.id_perfil = ma.id_perfil');
		$this->db->join('tb_modules m','ma.module_id = m.module_id');
		$this->db->where("e.person_id", $person_id);
		$this->db->where("e.id_perfil", $perfil_id);
		$this->db->order_by("m.sort", "asc");
		return $this->db->get();
	}

	function get_allowed_modules_accion($perfil_id, $module_id)
	{
		$this->db->select('p.id_perfil, p.nom_perfil, m.module_id, m.name_lang_key, ma.accion, ma.tipo, ma.sort, ma.alias '); 
		$this->db->from('tb_perfiles p');
		$this->db->join('tb_modules_accion ma','p.id_perfil = ma.id_perfil');
		$this->db->join('tb_modules m','ma.module_id = m.module_id');
		$this->db->where("p.id_perfil", $perfil_id);
		$this->db->where("ma.module_id", $module_id);
		$this->db->order_by("ma.sort", "asc");

		return $this->db->get();
		//Después del $this->db->get();
		//viene el $array_dato->result(); ($array_dato = Variable que recibe del $this->db->get())
	}

	function verEmpleado($person_id)
	{
		$this->db->where('person_id', $person_id);
		$query = $this->db->get('tb_datos');
		return $query->result();
	}
	

	// Verificar datos
	function exists($tabla, $campo, $valor)
	{
		$this->db->where($campo, $valor);
        $query = $this->db->get($tabla);
		if($query->result())
			return 1;
		else
			return 0;
	}
	// --

	function getUserCreator($id_owner)
	{
		$this->db->select("CONCAT(first_name, ' ', last_name) AS user_creador"); 
		$this->db->from('tb_datos');
		$this->db->where('person_id', $id_owner);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->user_creador;
	}

	function accessController($controller,$perfil_id)
	{
		$this->db->where("id_perfil", $perfil_id);
		$this->db->where("module_id", $controller);
        $query = $this->db->get('tb_modules_accion');
		if($query->num_rows()>0)
			return 1;
		else
			return 0;
	}

}