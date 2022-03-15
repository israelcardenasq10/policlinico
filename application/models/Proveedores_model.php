<?php
class Proveedores_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
		$this->db->select("d.*, p.id_cate_serv, p.razon_social, p.nombre_corto, p.tipo_prov, p.linea, p.id_owner");
		$this->db->from('tb_datos d');
		$this->db->join('tb_proveedores p', 'd.person_id = p.person_id');
		$this->db->where('p.deleted', 0);
		$this->db->where('d.tipo_user', 'prov');
		$this->db->order_by("d.person_id", "desc");   
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	// --
	public function listarCategoriasServicios()
	{
		$query = $this->db->get('tb_categorias_serv');
		return $query->result();
	}

	// Funciones de SERVICIOS:
	public function listarServicios($id, $person_id)
	{
		$this->db->where('id_categoria', $id);
		$this->db->where('NOT EXISTS
									(
										SELECT id_serv_prov 
											FROM tb_servicio_prov 
											WHERE id_serv_prov = s.id_serv_prov 
														AND person_id = '.$person_id.' 
									)');
		$query = $this->db->get('tb_servicio s');
		return $query->result();
	}

	public function listarServiciosXProv($person_id)
	{
		$this->db->select("sp.person_id, sp.id_serv_prov, s.id_categoria, s.cuenta_conta, s.nombres");
		$this->db->from('tb_servicio_prov sp');
		$this->db->join('tb_proveedores p', 'sp.person_id = p.person_id');
		$this->db->join('tb_servicio s', 'sp.id_serv_prov = s.id_serv_prov');
		$this->db->where('sp.person_id', $person_id);
		//$this->db->order_by("d.nombres", "asc");   
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}
	public function insertarServicioProv($value)
	{
		$this->db->insert('tb_servicio_prov', $value);
	}
	public function eliminarServicioProv($id)
	{
		$this->db->delete('tb_servicio_prov', array('person_id' => $id));
	}

	public function verProveedor($ruc)
	{
		$this->db->select("d.nro_doc, d.tipo_doc, p.person_id, p.razon_social, p.nombre_corto, p.id_pref_1");
		$this->db->from('tb_datos d');
		$this->db->join('tb_proveedores p', 'd.person_id = p.person_id');
		$this->db->where('p.deleted', 0);
		$this->db->where('d.tipo_user', 'prov');
		$this->db->where('d.nro_doc', $ruc);
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
		$this->db->insert('tb_proveedores', $data);
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
		$this->db->where("person_id", $id);
		$this->db->update("tb_proveedores", $data);		
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->select("d.*, p.id_cate_serv, p.razon_social, p.nombre_corto, p.tipo_prov, p.linea, p.id_pref_1, p.id_owner");
		$this->db->from('tb_datos d');
		$this->db->join('tb_proveedores p', 'd.person_id = p.person_id');
		$this->db->where('p.deleted', 0);
		$this->db->where('d.tipo_user', 'prov');
		$this->db->where('d.person_id', $id);
        $query = $this->db->get();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->delete('tb_proveedores', array('person_id' => $id));
		$this->db->delete('tb_datos', array('person_id' => $id));
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
        $sql = "SELECT * FROM EXCEL_PROVEEDORES a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.tipo_prov = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.tipo_prov = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}
}