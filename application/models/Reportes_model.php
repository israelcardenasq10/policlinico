<?php
class Reportes_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	// Reportes Generales
    public function filtrar($fecha1, $fecha2, $cbo_1)
	{
        $sql = "SELECT * FROM EXCEL_REPORTE_1 a ";
		
		//if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
		//	$sql .= " WHERE a.id_serie = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		//if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
		//	$sql .= " WHERE a.id_serie = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha, a.ticket; ";
		
		$query = $this->db->query($sql);
		return $query->result();
	}

	public function exportarExcelReportes1($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_REPORTE_1');

		// data Body
		$sql = "SELECT * FROM EXCEL_REPORTE_1 a ";
		
		//if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
		//	$sql .= " WHERE a.id_serie = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		//if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
		//	$sql .= " WHERE a.id_serie = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha, a.ticket; ";
			
		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}


	// Query de Reporte de Inventario
	public function exportarExcelInventario($fecha1, $fecha2, $cbo_1) // FALTA 
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_INVENTARIO');

		// data Body
		$sql = "SELECT * FROM EXCEL_INVENTARIO a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_cat = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_cat = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";
			
		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelClientes($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_CLIENTES');

		// data Body
		$sql = "SELECT * FROM EXCEL_CLIENTES a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.tipo_doc = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.tipo_doc = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}


	// Query de Reporte
	public function exportarExcelAsistencias($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_ASISTENCIAS');

		// data Body
		$sql = "CALL SP_ASISTENCIAS ";
		
		//if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
		//	$sql .= " WHERE a.tipo_doc = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " ('$fecha1', '$fecha2', 0); ";
		
		else if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " ('$fecha1', '$fecha2', $cbo_1); ";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelProveedores($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_PROVEEDORES');

		// data Body
		$sql = "SELECT * FROM EXCEL_PROVEEDORES a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.tipo_prov = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.tipo_prov = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelEmpleados($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_EMPLEADOS');

		// data Body
		$sql = "SELECT * FROM EXCEL_EMPLEADOS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}


	// Query de Reporte
	public function exportarExcelCompras($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_COMPRAS');

		// data Body
		$sql = "SELECT * FROM EXCEL_COMPRAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.situacion = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.situacion = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelAlmacen($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_ALMACEN');

		// data Body
		$sql = "SELECT * FROM EXCEL_ALMACEN a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.unidad = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.unidad = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelProductos($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_PRODUCTOS');

		// data Body
		$sql = "SELECT * FROM EXCEL_PRODUCTOS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_categoria = '$cbo_1'";
					
			$sql .= " ORDER BY a.nombre ASC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}
	
	// Query de Reporte
	public function exportarExcelVentas($fecha1, $fecha2, $cbo_1, $anulado, $cbo_2)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_VENTAS');

		// data Body
		$sql = "SELECT * FROM EXCEL_VENTAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_serie = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_serie = '$cbo_1' AND (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";
				
		if($anulado == 'V')
			$sql .= "  AND a.estado = 'SI' ";
		
		if($cbo_2 > 0)
			$sql .= "  AND a.id_tp = '$cbo_2' ";
	
			$sql .= " ORDER BY a.fecha_registro DESC;";
			
		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}
	
	// Query de Reporte
	public function exportarExcelVentasRC($fecha1, $fecha2)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_VENTAS_RC');

		// data Body		
		$sql = "SELECT * FROM EXCEL_VENTAS_RC a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro";
			
		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelVentasRDP($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_VENTAS_RDP');//array('Plato','cantidad','venta_total');//
		// $fields = array('name'=>'Plato','name'=>'cantidad','name'=>'venta_total');
		// var_dump($fields);
		// data Body
		//$sql = "SELECT a.fecha_registro, a.producto, SUM(a.cantidad) AS venta FROM EXCEL_VENTAS_RDP a ";
		// $sql = "SELECT a.id_producto, a.fecha_registro, a.producto, SUM(a.cantidad) AS venta, SUM(a.total) as total FROM EXCEL_VENTAS_RDP a ";
		$sql = "SELECT a.producto, SUM(a.cantidad) AS venta, SUM(a.total) as total FROM EXCEL_VENTAS_RDP a ";

		if($cbo_1 == '0' && $fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') AND a.id_producto = '$cbo_1'";

			$sql .= " GROUP BY a.producto";
			$sql .= " ORDER BY venta DESC";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelVentasBar($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_VENTAS_BARISTA');

		// data Body
		$sql = "SELECT a.nro_doc, a.id, a.barista, a.username, a.fecha, SUM(a.total_venta) AS total_venta FROM EXCEL_VENTAS_BARISTA a ";
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";

			$sql .= " GROUP BY a.nro_doc, a.id, a.barista, a.username, a.fecha";
			$sql .= " ORDER BY a.fecha DESC";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelMesasBorradas($fecha1, $fecha2)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_MESAS_BORRADAS');

		// data Body		
		$sql = "SELECT * FROM EXCEL_MESAS_BORRADAS a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_registro >= '$fecha1' and a.fecha_registro <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro DESC";
			
		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}

	// Query de Reporte
	public function exportarExcelComandasBorradas($fecha1, $fecha2)
	{
		// data Header
		$fields = $this->db->field_data('excel_comandas_borradas');

		// data Body		
		$sql = "SELECT * FROM excel_comandas_borradas a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha_borrada >= '$fecha1' and a.fecha_borrada <= '$fecha2') ";

			$sql .= " ORDER BY a.id DESC";
			
		$query = $this->db->query($sql);
		
		return array("fields" => $fields, "query" => $query);
	}
	// Query de Reporte
	public function exportarExcelComandas($fecha1, $fecha2)
	{
		// data Header
		$fields = $this->db->field_data('excel_comandas');

		// data Body		
		$sql = "SELECT * FROM EXCEL_COMANDAS a ";

		if($fecha1 != '0' && $fecha2 != '0')
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";

			$sql .= " ORDER BY a.fecha_registro DESC";
		
		$query = $this->db->query($sql);
		
		return array("fields" => $fields, "query" => $query);
	}
	// Query de Reporte
	public function exportarExcelMermas($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_MERMAS');

		// data Body
		$sql = "SELECT * FROM EXCEL_MERMAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.id_serv_prov = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.id_serv_prov = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha_registro DESC;";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}
	// Query de Reporte
	public function exportarExcelVFDM($fecha1, $fecha2)
	{
		// data Header
		$fields = $this->db->field_data('excel_ventas_fin_de_mes');

		// data Body
		$sql = "SELECT * FROM excel_ventas_fin_de_mes a ";
		$sql .= " WHERE a.fecha_registro BETWEEN '".$fecha1."' AND '".$fecha2."'";
		$sql .= " ORDER BY a.fecha_registro,a.num_doc";

		$query = $this->db->query($sql);

		return array("fields" => $fields, "query" => $query);
	}
	
	// -- To File CSV
	public function exportarCSVCompras($fecha1, $fecha2, $cbo_1)
	{
		// data Header
		$fields = $this->db->field_data('EXCEL_COMPRAS');

		// data Body
		$sql = "SELECT * FROM EXCEL_COMPRAS a ";
		
		if($cbo_1 != '0' && ($fecha1 == '0' && $fecha2 == '0'))
			$sql .= " WHERE a.situacion = '$cbo_1'";

		if($cbo_1 == '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
		
		if($cbo_1 != '0' && ($fecha1 != '0' && $fecha2 != '0'))
			$sql .= " WHERE a.situacion = '$cbo_1' AND (a.fecha >= '$fecha1' and a.fecha <= '$fecha2') ";
						
			$sql .= " ORDER BY a.fecha DESC;";

		$query = $this->db->query($sql);

		return $query;
	}

}