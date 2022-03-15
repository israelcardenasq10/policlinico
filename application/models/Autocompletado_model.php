<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Autocompletado_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function buscarClienteRuc($abuscar)
	{
		// $resultados = $this->db->select('person_id, nro_doc, tipo_doc, address_1, email, razon_social')
		// 						->from('v_clientes_ruc')
		// 						->where("nro_doc LIKE '$abuscar%' AND deleted='0' ")
       	// 						->get();
		$this->db->from('tb_clientes ');
		$this->db->where('deleted', 0);
		$this->db->where("nro_doc LIKE '$abuscar%'");
		$resultados = $this->db->get();

       	// Muestra mensaje de errores en la carpeta "logs"
       	// log_message('error', $this->db->last_query());

		if($resultados->num_rows() > 0)
		{
			return $resultados->result();
		}
		else
		{
			return FALSE;
		}
	}

	// PROCESO BOLETA CLIENTE
	public function buscarClienteBoleta($abuscar)
	{
		$resultados = $this->db->select('id,tipo_doc, documento, nombres, email, telefono, date_created, persona_id_created')
								->from('tb_pv_cliente_boleta')
       							->where("nombres LIKE '%$abuscar%'")
       							->get();

       	// Muestra mensaje de errores en la carpeta "logs"
       	// log_message('error', $this->db->last_query());

		if($resultados->num_rows() > 0)
		{
			return $resultados->result();
		}
		else
		{
			return FALSE;
		}
	}

	/*
	public function buscarClienteNatural($abuscar)
	{
		$resultados = $this->db->select('nro_doc, tipo_doc, first_name, last_name, address_1, email')
								->from('tb_datos')
       							->where("CONCAT(first_name, ' ',last_name) LIKE '%$abuscar%'")
       							->get();

		if($resultados->num_rows() > 0)
		{
			return $resultados->result();
		}
		else
		{
			return FALSE;
		}
	}
	*/

	public function autocompletarProducto($abuscar)
	{
		
		// $resultados = $this->db->select('id_producto AS id, CONCAT(codigo_ant,' - ',nombre) AS text,id_categoria')
		// 						->from('tb_productos')
		// 						->where("activo","SI")
       	// 						->where("CONCAT(codigo_ant,' - ',nombre) LIKE '%$abuscar%'")
       	// 						->get();
		// echo $resultados ;
		// return $resultados->result();
		$sql = "SELECT id_producto AS id,CONCAT(codigo_ant,' - S/',precio_venta, ' - ',nombre) AS TEXT,id_categoria
		FROM tb_productos
		WHERE activo='SI' AND CONCAT(codigo_ant,' - ',nombre) LIKE '%".$abuscar."%' ";
		
		$resultados = $this->db->query($sql);
		if($resultados->num_rows() > 0)
		{
			return $resultados->result();
		}
		else
		{
			return FALSE;
		}
	}
}