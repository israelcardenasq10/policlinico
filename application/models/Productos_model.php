<?php
class Productos_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	public function listar($categoria)
	{
		$this->db->select("p.*, pc.nombre AS categoria, b.username, p.producto_comanda_id");
		$this->db->from('tb_productos p');
		$this->db->join('tb_productos_cat pc', 'p.id_categoria = pc.id_categoria');
	    //$this->db->join('tb_almacen_productos ap', 'p.id_producto = ap.id_producto');
	    //$this->db->join('tb_almacen a', 'ap.id_almacen = a.id_almacen');
	    $this->db->join("tb_empleados b", "p.id_owner = b.person_id");
	    if(strlen($categoria)==4){
			$this->db->where("p.id_categoria", $categoria );
		}
		$this->db->order_by("p.nro_producto", "asc");
    	$query = $this->db->get();
    	//echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function listarProductos()
	{
		$this->db->select("p.*, pc.nombre AS categoria");
		$this->db->from('tb_productos p');
		$this->db->join('tb_productos_cat pc', 'p.id_categoria = pc.id_categoria');
		$this->db->order_by("p.nombre", "asc");
    	$query = $this->db->get();
		return $query->result();
	}

	// --
	public function listarCategoriasProd()
	{
		//$this->db->where('estado', 1);
    	$query = $this->db->get('tb_productos_cat');
		return $query->result();
	}

	public function verUnidad($id_unidad)
	{
		$this->db->where("id_unidad", $id_unidad);
        $query = $this->db->get('tb_unidades');
		return $query->result();
	}

	public function verInsumosXProd($id)
	{
		$this->db->select("a.id_almacen, a.id_serv_prov, s.nombres, a.id_unidad, u.valor, a.cantidad,
							a.unidad_medida, a.costo,
								CASE
									WHEN (SELECT ap.porcion_mlts AS porcion_mlts
													FROM tb_almacen_productos ap
													WHERE ap.id_producto = p.id_producto AND ap.id_almacen = a.id_almacen) > 0  THEN ap.porcion_mlts
									ELSE a.valor_porcion
								END AS valor_porcion,
							a.stock_porcion,
								CASE
									WHEN (SELECT ap.costo_mlts AS costo_mlts
													FROM tb_almacen_productos ap
													WHERE ap.id_producto = p.id_producto AND ap.id_almacen = a.id_almacen) > 0  THEN ap.costo_mlts
									ELSE a.costo_porcion
								END AS costo_porcion,
							p.id_producto, p.nombre");
		$this->db->from('tb_almacen a');
		$this->db->join('tb_almacen_productos ap', 'a.id_almacen = ap.id_almacen');
        $this->db->join('tb_productos p', 'ap.id_producto = p.id_producto');
        $this->db->join('tb_servicio s', 'a.id_serv_prov = s.id_serv_prov');
        $this->db->join('tb_unidades u', 'a.id_unidad = u.id_unidad');
		$this->db->where('p.id_producto', $id);
        $query = $this->db->get();
		return $query->result();
	}

	public function buscarProducto($id)
	{
		$this->db->where('id_producto', $id);
		$this->db->limit(1);
		$query = $this->db->get('tb_productos');
		return $query->result();
	}

	public function listarProductoEnvioComanda()
	{
		$this->db->where('estado', 1);
    	$query = $this->db->get('tb_productos_comanda');
		return $query->result();
	}
	public function listarUnidades()
	{
		$this->db->where('activo', 1);
		$this->db->order_by('id_unidad','DESC');
    	$query = $this->db->get('tb_unidades');
		return $query->result();
	}
	// --

	public function insertar($data)
	{
		$this->db->insert('tb_productos', $data);
		return $this->db->insert_id();
	}

	public function insertarDetalle($data)
	{
		$this->db->insert('tb_almacen_productos', $data);
	}

	public function actualizar($id, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_producto", $id);
		$this->db->update("tb_productos", $data);
		$this->db->trans_complete();
	}

	public function actualizarDetalle($id, $id2, $data)
	{
		$this->db->trans_start();
		$this->db->where("id_producto", $id);
		$this->db->where("id_almacen", $id2);
		$this->db->update("tb_almacen_productos", $data);
		$this->db->trans_complete();
	}

	public function ver($id)
	{
		$this->db->select("p.*, pc.nombre AS categoria, b.username");
		$this->db->from('tb_productos p');
		$this->db->join('tb_productos_cat pc', 'p.id_categoria = pc.id_categoria');
        $this->db->join("tb_empleados b", "p.id_owner = b.person_id");
		$this->db->where('p.id_producto', $id);
        $query = $this->db->get();
        //echo "<br/><br/><br/><br/>".$this->db->last_query();
		return $query->result();
	}

	public function eliminar($id)
	{
		$this->db->trans_start();
		$this->db->delete('tb_productos', array('id_producto' => $id));
		$this->db->trans_complete();
	}

	public function eliminarDetalle($id, $id2)
	{
		$this->db->delete('tb_almacen_productos',
								array(
										'id_producto' => $id,
										'id_almacen' => $id2
								)
						 );
	}

	public function generarCodMax()
	{
		//$this->db->select_max("id_producto");
		$this->db->select("MAX(ABS(id_producto)) as id_producto");
		$this->db->from('tb_productos');
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->id_producto;
	}

	public function verNroProductoXCategoria($id_categoria)
	{
		//$this->db->select_max("id_producto");
		$this->db->select("MAX(ABS(nro_producto)) as nro_producto");
		$this->db->from('tb_productos');
		$this->db->where('id_categoria', $id_categoria);
		$query = $this->db->get();
		$lis = $query->result();
		return $lis[0]->nro_producto;
	}


	// -- Dato duplicado
	public function validarCod($id)
    {
        $this->db->where('id_producto', $id);
        $this->db->from('tb_productos');
        $query = $this->db->get();
        if($query->result())
            return 1;
        else
            return 0;
    }

    // Subir Archivos
	public function updateImagen($id, $ruta_archivo)
    {
        $this->db->where('id_producto', $id);
        return $this->db->update('tb_productos', array('imagen' => $ruta_archivo));
    }

    // Reportes
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_PRODUCTOS a ";

		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_categoria = '$cbo_1'";

		//if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
		//	$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";

		//if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
		//	$sql .= " WHERE a.situacion = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";

			$sql .= "ORDER BY a.nombre ASC;";

		$query = $this->db->query($sql);
		return $query->result();
	}


}
