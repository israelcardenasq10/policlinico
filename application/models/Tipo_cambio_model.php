<?php
class Tipo_cambio_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar()
	{
        $this->db->select(" a.*,
                            b.username
                            ");
        $this->db->from('tb_tipo_cambio a');
        $this->db->join("tb_empleados b", "a.id_owner = b.person_id");
        $this->db->order_by('a.fecha_registro', 'DESC');
        $query = $this->db->get();
        //echo "<br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function insertar($data)
	{
		$this->db->insert('tb_tipo_cambio', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->where('id_tc', $id);
		$this->db->update('tb_tipo_cambio', $data);
	}

	public function ver($id)
	{
		$this->db->where('id_tc', $id);
		$query = $this->db->get('tb_tipo_cambio');
		return $query->result();
	}

    public function fechadupli()
    {
        $this->db->where('fecha_registro', mdate("%Y-%m-%d", time()));
        $this->db->from('tb_tipo_cambio');
        $query = $this->db->get();
        //echo "<br/><br/><br/>".$this->db->last_query();
        if($query->result())
            return 1;
        else
            return 0;
    }

		public function fechaduplicaTC($fechatc)
		{
				$this->db->where('fecha_registro', $fechatc);
				$this->db->from('tb_tipo_cambio');
				$query = $this->db->get();
				if($query->result())
						return 1;
				else
						return 0;
		}

    public function verTCxFecha($fecha_compra)
	{
		$this->db->where('fecha_registro', $fecha_compra);
		$query = $this->db->get('tb_tipo_cambio');
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->delete('tb_tipo_cambio', array('id_tc' => $id));
	}

}
