<?php
class Servicios_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
        $this->db->select('a.id_serv_prov, a.cuenta_conta, a.nombres, b.nombre');   
        $this->db->from('tb_servicio a');
        $this->db->join('tb_categorias_serv b','a.id_categoria = b.id_cate_serv');
        $query = $this->db->get();        
		return $query->result();
	}

	// --
    public function listaCategorias()
    {
        $query = $this->db->get('tb_categorias_serv');
		return $query->result();        
    }

    public function listarServiciosXCat()
    {
		$this->db->select('s.*');   
        $this->db->from('tb_servicio s');
        $this->db->join('tb_categorias_serv c','s.id_categoria = c.id_cate_serv');
        $this->db->where('c.tipo', 'PV');
        $this->db->where_not_in('s.descripcion', 'almacen');
        $this->db->or_where('s.descripcion IS NULL');
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
    }

    public function listarProveedoresXServ($id_serv_prov)
    {
        $this->db->select('DISTINCT(p.person_id) as person_id, p.razon_social, p.nombre_corto');   
        $this->db->from('tb_servicio_prov sp');
        $this->db->join('tb_proveedores p','sp.person_id = p.person_id');
        $this->db->where('sp.id_serv_prov', $id_serv_prov);
        $query = $this->db->get();
		return $query->result();
    }
    // --

	public function insertar($data)
	{
		$this->db->insert('tb_servicio', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->where('id_serv_prov', $id);
		$this->db->update('tb_servicio', $data);
	}

	public function ver($id)
	{
		$this->db->where('id_serv_prov', $id);
		$query = $this->db->get('tb_servicio');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->delete('tb_servicio', array('id_serv_prov' => $id));
	}
    
    /** MANTENIMIENTO CATEGORIAS **/
    
	public function verCat($id)
	{
		$this->db->where('id_cate_serv', $id);
		$query = $this->db->get('tb_categorias_serv');
		return $query->result();
	}    
    
	public function insertarCat($data)
	{
		$this->db->insert('tb_categorias_serv', $data);
	}    

	public function actualizarCat($id, $data)
	{
		$this->db->where('id_cate_serv', $id);
		$this->db->update('tb_categorias_serv', $data);
        $this->db->last_query();
        
	}    
	public function eliminarCat($id)
	{
		$this->db->delete('tb_categorias_serv', array('id_cate_serv' => $id));
	}    

}