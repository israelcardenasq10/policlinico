<?php
class Inventario_model extends CI_Model {
    
    public $query;
    
	function __construct()
	{
		parent::__construct();
	}
	public function listar()
	{
        $this->db->select(" a.*,
                            e.nombre as nombre_area,
                            c.razon_social,
                            d.nombre");
		$this->db->from('tb_inventario a');
        $this->db->join("tb_proveedores c", "a.prov_id = c.person_id");
        $this->db->join("tb_inventario_cat d", "a.id_cat = d.id_cat");  
        $this->db->join("tb_inventario_area e", "a.hab_area = e.id_area");
        $this->db->where('a.deleted is Null');
        $this->db->order_by("id_inventario", "desc");
		$query = $this->db->get();
        //echo "<br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	// --
	public function listarCategorias()
	{
		$this->db->select("a.id_cat, a.nombre");
		$this->db->from('tb_inventario_cat a');
		$query = $this->db->get();
        //$this->db->last_query();
		return $query->result();
	}
	public function listarAreas()
	{
        $this->db->select("a.id_area, a.nombre");
        $this->query = $this->db->get("tb_inventario_area a");
		return $this->query->result();
	}  
	public function listarPerfiles()
	{
		$query = $this->db->get('tb_perfiles');
		return $query->result();
	}
    
	public function listarProveedor($tipo)
	{
		$this->db->where('tipo_prov', $tipo);
        $query = $this->db->get('tb_proveedores');
		return $query->result();
	}    
    
    public function regMax()
    {
        $this->db->select_max('id_inventario');
        $this->query = $this->db->get('tb_inventario');  
        return $this->query->result();
    }
    
    
	public function insertar($data)
	{
		$this->db->trans_start();
		$this->db->insert('tb_inventario', $data);
		$this->db->trans_complete();
	}
	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where('id_inventario', $id);
		$this->db->update('tb_inventario', $data);
        //echo "<br/><br/>".$this->db->last_query();
		$this->db->trans_complete();
	}

	public function ver($id)
	{
        $this->db->select(" a.*,
                            c.razon_social,
                            d.nombre");
		$this->db->from('tb_inventario a');
        $this->db->where('id_inventario', $id);
        $this->db->join("tb_proveedores c", "a.prov_id = c.person_id");
        $this->db->join("tb_inventario_cat d", "a.id_cat = d.id_cat"); 
        $query = $this->db->get();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		//$this->db->delete('tb_inventario', array('id_inventario' => $id));
		$this->db->where('id_inventario', $id);
		$this->db->update('tb_inventario', array('deleted' => 1));
		$this->db->trans_complete();
	}

	public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_INVENTARIO a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_cat = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_cat = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	

}