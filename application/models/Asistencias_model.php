<?php
class Asistencias_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("a.*,CONCAT(c.first_name,' ',c.last_name) as nombres");
		$this->db->from('tb_asistencias a');
		$this->db->join('tb_empleados b', 'a.id_emple = b.id');
		$this->db->join('tb_datos c', 'b.person_id = c.person_id');
        $this->db->order_by('a.id_asistencia','DESC');
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();  
		return $query->result();
	}

	// --
	public function listarEmpleados()
	{
		$this->db->select("e.id, concat(d.last_name, ' ', d.first_name) AS nombres");
		$this->db->from('tb_empleados e');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->trans_start();
		$this->db->insert('tb_asistencias', $data);
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		$this->db->trans_complete();
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
        $this->db->where('id_asistencia', $id);  
		$this->db->update('tb_asistencias', $data);
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		$this->db->trans_complete();
	}

	public function ver($id)
	{
        $this->db->select("a.*, concat(c.last_name, ' ', c.first_name) AS nombres");
		$this->db->from('tb_asistencias a');
		$this->db->join('tb_empleados b', 'a.id_emple = b.id');
        $this->db->join('tb_datos c', 'b.person_id = c.person_id');
        $this->db->where('id_asistencia', $id);
        $query = $this->db->get();		
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}
	
    public function verCount($id,$fecha)
	{
		$this->db->where('id_emple', $id);
        $this->db->where('fecha_login', $fecha);
		$query = $this->db->get('tb_asistencias');
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->num_rows();
	}    
    

    public  function verReg1($id,$fecha,$reg){
        $this->db->where('consecutivo', $reg);
		$this->db->where('id_emple', $id);
        $this->db->where('fecha_login', $fecha);
		$query = $this->db->get('tb_asistencias');
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();        
    }  
    
    public  function verReg2($id,$fecha,$reg){
        $this->db->where('consecutivo', $reg);
		$this->db->where('id_emple', $id);
        $this->db->where('fecha_login', $fecha);
		$query = $this->db->get('tb_asistencias');
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();        
    }     

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->delete('tb_asistencias', array('id_asistencia' => $id));
		//$this->db->where('id', $id);
		//$this->db->update('tb_asistencias', array('deleted' => 1));
		$this->db->trans_complete();
	}


	// Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "CALL SP_ASISTENCIAS";
		
		//if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
		//	$sql .= " WHERE a.tipo_doc = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " ('$fecha1', '$fecha2', 0); ";
		
		else if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " ('$fecha1', '$fecha2', $cbo_1); ";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}