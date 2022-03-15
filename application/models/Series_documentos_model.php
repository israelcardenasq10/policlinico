<?php
class Series_documentos_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
        $query = $this->db->get('tb_series_documentos');
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_series_documentos', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->where('id_serie', $id);
		$this->db->update('tb_series_documentos', $data);
	}

	public function ver($id)
	{
		$this->db->where('id_serie', $id);
		$query = $this->db->get('tb_series_documentos');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->delete('tb_series_documentos', array('id_serie' => $id));
	}

	// -- Dato duplicado
	public function validarCod($id)
    {
        $this->db->where('tipo_doc', $id);                                 
        $this->db->from('tb_series_documentos');
        $query = $this->db->get();
        if($query->result())
            return 1;
        else
            return 0;  
    }

}