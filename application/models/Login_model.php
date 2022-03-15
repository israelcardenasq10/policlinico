<?php
class Login_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function login($usr, $pass)
	{
		$this->db->where('username', $usr);
		$this->db->where('password', $pass);
		$this->db->where('deleted', 0);
		$query = $this->db->get('tb_empleados');
		if ($query->num_rows() == 0)
		  return 0;					 //Usuario no existe
		else
		  return $query->result();	//Usuario y contraseña correcta
	}

	public function insertarSession($data)
	{
		$this->db->insert('tb_sesiones_user', $data);
	}
}