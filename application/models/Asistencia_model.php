<?php
class Asistencia_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listarHorarios()
	{
		$this->db->select("a.*,CONCAT(c.first_name,' ',c.last_name) as nombres");
		$this->db->from('tb_persona_horarios a');
		$this->db->join('tb_empleados b', 'a.id_empleado = b.id');
		$this->db->join('tb_datos c', 'b.person_id = c.person_id');
        $this->db->order_by('a.personahorario','DESC');
		 
		$query = $this->db->get();
		return $query->result();
	}
	public function listarAsistencia()
	{
		$this->db->select("a.*,CONCAT(c.first_name,' ',c.last_name) as nombres");
		$this->db->from('tb_marcaciones_asistencia a');
		$this->db->join('tb_empleados b', 'a.id_empleado = b.id');
		$this->db->join('tb_datos c', 'b.person_id = c.person_id');
        $this->db->order_by('a.fecha','DESC');
		$query = $this->db->get();

		return $query->result();
	}
	public function listarMarcas()
	{
		$query = $this->db->query("select top 1000 a.id_marcaciones,a.id_empleado, a.fecha_hora,
		CONCAT(c.first_name,' ',c.last_name) as nombres
		from tb_marcaciones a
		inner join tb_empleados b ON a.id_empleado = b.id
		inner join tb_datos c on b.person_id = c.person_id
		ORDER BY a.id_marcaciones DESC");
		//$query = $this->db->get();
		  
		return $query->result();
	}
	public function listarTabla($tabla){
		$this->db->select("*");
		$this->db->from($tabla);
		$query = $this->db->get();
		  
		return $query->result();
	}

	public function listarEmpleados()
	{
		$this->db->select("e.id, concat(d.last_name, ' ', d.first_name) AS nombres");
		$this->db->from('tb_empleados e');
		$this->db->join('tb_datos d', 'e.person_id = d.person_id');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function insertar($tabla,$data)
	{
		$this->db->trans_start();
		$this->db->insert($tabla, $data);
		$this->db->trans_complete();
	}

    public function mdlRegistroDatos($tabla, $datos){
        
        $this->db->insert($tabla, $datos);
        if ($this->db->affected_rows() == '1'){
            return TRUE;
        }else{
            FALSE;
        }
        $this->db->close();
	}

	public function updatemarcas(){
		$consulta=$this->db->query("EXEC sp_ic_actualizar_dni_empleado");
       	if($consulta==true){
           return true;
       	}else{
           return false;
       	}
	}
    
	public function actualizar($pk, $id , $table, $data)
	{
		$this->db->trans_start();
        $this->db->where($pk, $id);  
		$this->db->update($table, $data);
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		$this->db->trans_complete();
	}

	public function ver($id)
	{
        $this->db->select("a.*,CONCAT(c.first_name,' ',c.last_name) as nombres");
		$this->db->from('tb_persona_horarios a');
		$this->db->join('tb_empleados b', 'a.id_empleado = b.id');
		$this->db->join('tb_datos c', 'b.person_id = c.person_id');
        $this->db->where('a.personahorario', $id);
        $query = $this->db->get();		
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
		if($fecha1 != '0' && $fecha2 != '0')
		{
		$sql = "select a.*,
		CONCAT(c.first_name,' ',c.last_name) as nombres,
		c.nro_doc
		from tb_marcaciones_asistencia a
		inner join tb_empleados b ON a.id_empleado = b.id
		inner join tb_datos c on b.person_id = c.person_id
		WHERE fecha BETWEEN '$fecha1' AND '$fecha2' AND (a.id_empleado=$cbo_1 OR 0=$cbo_1)
		ORDER BY a.fecha DESC";
		$query = $this->db->query($sql);
		
		return $query->result();
		}
	}
	public function procAsist($desde,$hasta){
		$_proc = "EXEC sp_ic_marcaciones_asistencia_resumen( '$desde', '$hasta') ";
       	if($consulta==true ){
           return true;
       	}else{
           return false;
       	}
	}
}