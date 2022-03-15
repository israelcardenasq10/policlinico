<?php
class Clientes_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("d.*, c.razon_social, c.id_owner");
		$this->db->from('tb_datos d');
		$this->db->join('tb_clientes c', 'd.person_id = c.person_id');
		$this->db->where('c.deleted', 0);
		$this->db->where('d.tipo_user', 'cli');
		$this->db->order_by("d.person_id", "desc");   
        $query = $this->db->get();
		return $query->result();
	}

	public function insertar($data,$data_d)
	{
		$this->db->trans_begin();
		$this->db->insert('tb_datos', $data);
		// return $this->db->insert_id();
		$person_id = $this->db->insert_id();
		$new_array=array_merge($data_d, array('person_id' => $person_id));
		$this->db->insert('tb_clientes', $new_array);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $person_id;
		}
		else
		{
			$this->db->trans_commit();
			return $person_id;

		}
	}

	public function insertarDetalle($data)
	{
		$this->db->insert('tb_clientes', $data);
	}

	public function actualizar($id, $data , $data_det)
	{
		$this->db->trans_start();
		$this->db->where("person_id", $id);
		$this->db->update("tb_datos", $data);	
		
		$this->db->where("person_id", $id);
		$this->db->update("tb_clientes", $data_det);

		$this->db->trans_complete();
	}
	
	public function ver($id)
	{
		$this->db->select("d.*, c.razon_social, c.id_owner");
		$this->db->from('tb_datos d');
		$this->db->join('tb_clientes c', 'd.person_id = c.person_id');
		$this->db->where('c.deleted', 0);
		$this->db->where('d.tipo_user', 'cli');
		$this->db->where('d.person_id', $id);
        $query = $this->db->get();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->delete('tb_clientes', array('person_id' => $id));
		$this->db->delete('tb_datos', array('person_id' => $id));
		//$this->db->where('person_id', $id);
		//$this->db->update('tb_clientes', array('deleted' => 1));
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
        $sql = "SELECT * FROM EXCEL_CLIENTES a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.tipo_doc = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.tipo_doc = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}


	// PROCESO BOLETA CLIENTE
	public function insertarClienteBoleta($data)
	{
		$this->db->insert('tb_pv_cliente_boleta', $data);
		return $this->db->insert_id();
	}
}