<?php
class Empleados_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("d.*, e.id, p.*");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->join('tb_planillas p', 'e.id = p.id_emple', 'left');
		$this->db->where('e.deleted', 0);
		$this->db->where('d.tipo_user', 'emp');
		$this->db->order_by("d.person_id", "desc");   
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
	// --

	public function insertar($data)
	{
		//$this->db->trans_start();
		$this->db->insert('tb_datos', $data);
		//$this->db->trans_complete();
		return $this->db->insert_id();
	}
	public function insertarDetalle($data)
	{
		$this->db->insert('tb_empleados', $data);
		return $this->db->insert_id();
	}
	public function insertarDetalle_2($data)
	{
		$this->db->insert('tb_planillas', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("person_id", $id);
		$this->db->update("tb_datos", $data);		
		$this->db->trans_complete();
	}
	public function actualizarDetalle($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_emple", $id);
		$this->db->update("tb_planillas", $data);		
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->select("d.*, e.id, e.id_owner, p.*");
		$this->db->from('tb_datos d');
		$this->db->join('tb_empleados e', 'd.person_id = e.person_id');
		$this->db->join('tb_planillas p', 'e.id = p.id_emple', 'left');
		$this->db->where('d.tipo_user', 'emp');
		$this->db->where('d.person_id', $id);
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->where('person_id', $id);
		$this->db->update('tb_empleados', array('deleted' => 1));
		$this->db->trans_complete();
	}


	// Subir Archivos
	public function updateImagen($id, $ruta_archivo)
    {
        $this->db->where('person_id', $id);
        return $this->db->update('tb_datos', array('imagen' => $ruta_archivo)); 
    }

    // Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_EMPLEADOS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}