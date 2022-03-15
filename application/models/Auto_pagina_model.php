<?php
class Auto_pagina_model extends CI_Model {
    
	function __construct()
	{
		parent::__construct();
	}

	public function actualizarIPWifi($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_global", $id);
		$this->db->update("tb_globales", $data);
		$this->db->trans_complete();
	}  

}