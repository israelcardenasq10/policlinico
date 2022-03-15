<?php
class Globales_model extends CI_Model {
    
    public $query;
    
	function __construct()
	{
		parent::__construct();
	}
	public function listar()
	{
        $query = $this->db->get("tb_globales");   		     
		//echo "<br/><br/><br/>".$this->db->last_query();
        return $query->result();
        
	} 
    
	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where('id_global', $id);
		$this->db->update('tb_globales', $data);
        //echo "<br/><br/><br/>".$this->db->last_query();
		$this->db->trans_complete();
	}

	public function ver($id)
	{
        $this->db->select(" g.* ");
		$this->db->from('tb_globales g');
        $query = $this->db->get();
		return $query->result();
	}
    
    public function updateAdjuntoOrden($id, $ruta_archivo)
    {
        $this->db->where('id_global', $id);
        return $this->db->update('tb_globales', array('logotipo' => $ruta_archivo)); 
    }

    //Actualizar Globales
	function actualizarGlobales($data)
	{
		$this->db->where("id_global", 1);
		$this->db->update("tb_globales", $data);
	}
    
}