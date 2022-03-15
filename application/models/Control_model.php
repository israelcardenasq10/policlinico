<?php
class Control_model extends CI_Model {
    
    public $query;
    
	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
	} 

	public function ver($pass)
	{
        $this->db->select("e.id, d.person_id, d.nro_doc, concat(d.last_name, ' ', d.first_name) AS nombres, d.email, e.username, d.imagen");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->where('e.deleted', 0);
        $this->db->where('e.password', $pass);
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->trans_start();
		$this->db->insert('tb_asistencias', $data);
		$this->db->trans_complete();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();   
	}

	public function verIPGlobal()
	{
		$this->db->select("ip_wifi");
		$this->db->from('tb_globales');
		$this->db->where('id_global', 1);
        $query = $this->db->get();
        $lis = $query->result();
        return $lis[0]->ip_wifi;
	}

	public function verCalendarioFeriado($fecha_cal)
	{
		$lis = NULL;
		$this->db->select("*");
		$this->db->from('tb_calendario');
		$this->db->where('fecha_cal', $fecha_cal);
		$this->db->limit(1);
        $query = $this->db->get();
        $lis = $query->result();

        if($lis === NULL)
       		return false;
       	else
        	return $lis[0]->fecha_cal;
	}

}