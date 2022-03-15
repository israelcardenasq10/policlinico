<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/TCClass/class_staff_library.php');

class TC_Automatico {

	private $library;

	function __construct()
	{
	    $this->library = new library();
	}

	public function index($work, $mes, $anho)
	{
		if(isset($work)){

			switch($work){
				case "get_image":	get_image($library); break;
				case "get_sbs":	get_sbs($library); break;
				case "get_bcr":	get_bcr($library); break;
				case "get_sunat":	$this->get_sunat($this->library, $mes, $anho); break; // Solo se esta usando esta funcion
			}

		}else{
			echo json_encode(array("success"=>false));
		}
	}
	
	
	public function get_sbs($library){
		//serverside.php?work=get_sbs&anho=2014&mes=02&dia=10
	
		if(isset($_GET['anho']) && isset($_GET['mes']) && isset($_GET['dia'])){
			$anho = str_pad($_GET['anho'], 4, "0", STR_PAD_LEFT);
			$mes = str_pad($_GET['mes'], 2, "0", STR_PAD_LEFT);
			$dia = str_pad($_GET['dia'], 2, "0", STR_PAD_LEFT);
		
			$url="http://www.sbs.gob.pe/app/stats/tc-cv.asp?FECHA_CONSULTA=$dia/$mes/$anho&button22=Consultar";
			
			$reemplazar = array(
				"cellspacing=\"0\" cellpadding=\"0\" width=\"100%\"  class=\"APLI_tabla\" >"=>'<table border="1">',
				'<td class="APLI_fila2">'=>'',
				'<br /> </td>'=>''
			);
			
			$content = $library->obtener_contenidos($url,'<table border="0" ',"</table>");
			
			if($content){
				$table = $library->filtrar( $content, $reemplazar );
				
				$head = "Dólar de N.A.";
				$foot = "</tr>";
				$table_final = $library->substract_inicio($table, $head);
				$table_final = $library->substract_final($table_final, $foot);
				
				$limpiar = array(" "=>'');
				
				if ( $table_final ) {
					$rows = preg_split('#</tr>#i', $table_final);
					$i = 0;
					
					foreach ( $rows as $row ) {
						$rows[$i] = array_filter( $library->filtrar( preg_split('#</td>#i', $row) ,$limpiar) );
						
						if($rows[$i]){
							$rows[$i]["fecha"] = $anho ."-". $mes ."-". $dia;
							$rows[$i]["compra"] = $rows[$i][0];unset($rows[$i][0]);
							$rows[$i]["venta"] = $rows[$i][1];unset($rows[$i][1]);
						}
						
						$i++;
					}
				}
				
				$data = array(
					'success' => true,
					'dia' => $dia,
					'mes' => $mes,
					'año' => $anho,
					'data' => $rows
				);
			}
		}else{
			$data = array(
				'success' => false
			);
		}
		
		header('Content-type: application/json');
		echo json_encode($data);	
	}
	public function get_bcr($library){
		//serverside.php?work=get_bcr&dia_desde=1&mes_desde=ene&dia_hasta=1&mes_hasta=feb&anho_desde=1998&anho_hasta=2000
	
		$mes_esp = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
	
		if(isset($_GET['dia_desde']) && isset($_GET['mes_desde']) && isset($_GET['anho_desde']) && isset($_GET['dia_hasta']) && isset($_GET['mes_hasta']) && isset($_GET['anho_hasta'])){
			$dia_desde = $_GET['dia_desde'];
			$mes_desde = $_GET['mes_desde'];
			$anho_desde = $_GET['anho_desde'];
			
			$dia_hasta = $_GET['dia_hasta'];
			$mes_hasta = $_GET['mes_hasta'];
			$anho_hasta = $_GET['anho_hasta'];
		
			$url="http://estadisticas.bcrp.gob.pe/consulta.asp?sIdioma=1&sTipo=1&sChkCount=12&sFrecuencia=D&Consulta=Consulta&chkDet1=1&chkDet1=5&chkDet1=6&chkDet1=7&chkDet1=8&chkDet1=9&txtanodesde=$anho_desde&txtanohasta=$anho_hasta&txtdiadesde=$dia_desde&txtdiahasta=$dia_hasta&txtmesdesde=$mes_desde&txtmeshasta=$mes_hasta";

			$reemplazar = array(
				'<table width="90%" border="1px" cellspacing="0" bordercolor="#CCCCCC">'=>'<table>',
				' scope="row" class="tiempo"'=>'',
				' scope="col" class="titulo"'=>'',
				'&nbsp;'=>'',
				'> <'=>'><',
				'> '=>'>',
				' <'=>'<',
				'th>'=>'td>',
				'TC Informal (S/. por US$) - Compra'=>'Compra',
				'TC Informal (S/. por US$) - Venta'=>'Venta',
				"Día/Mes/Año"=>'Fecha'
			);
		
			$content = $library->obtener_contenidos($url,"<table","</table>");
			
			if($content){
				$table = $library->filtrar( $content, $reemplazar);
				
				$head = "<td>Venta</td></tr>";
				$table_final = $library->substract_inicio($table, $head);

				$limpiar = array(
					"<tr><td>"=>'',
					"<td>"=>''
				);
				
				if ( $table_final ) {
					$rows = preg_split('#</tr>#i', $table_final);
					$i = 0;
					
					foreach ( $rows as $row ) {
						$rows[$i] = array_filter( $library->filtrar( preg_split('#</td>#i', $row) ,$limpiar) );
						
						if($rows[$i]){
							if($rows[$i][0]){
								$num = preg_split("/[a-zA-Z]+/",$rows[$i][0]);
								$str = array_filter(preg_split("/[0-9]+/",$rows[$i][0]));
								
								$dia = str_pad($num[0], 2, "0", STR_PAD_LEFT);
								$mes = str_pad(array_search($str[1], $mes_esp)+1, 2, "0", STR_PAD_LEFT);
								
								if($num[1] <= substr($anho_hasta, -2)){
									$anho = str_pad($num[1], 4, $anho_hasta, STR_PAD_LEFT);
								}else{
									$anho = str_pad($num[1], 4, $anho_desde, STR_PAD_LEFT);
								}
								
								$rows[$i]["fecha"] = $anho."-".$mes."-".$dia;
								
								unset($rows[$i][0]);
							}
							
							$rows[$i]["compra_interbancario"] = $rows[$i][1];unset($rows[$i][1]);
							$rows[$i]["venta_interbancario"] = $rows[$i][2];unset($rows[$i][2]);
							$rows[$i]["compra_sbs"] = $rows[$i][3];unset($rows[$i][3]);
							$rows[$i]["venta_sbs"] = $rows[$i][4];unset($rows[$i][4]);
							$rows[$i]["compra_paralelo"] = $rows[$i][5];unset($rows[$i][5]);
							$rows[$i]["venta_paralelo"] = $rows[$i][6];unset($rows[$i][6]);
						}
						
						$i++;
					}
				}
				$rows = array_filter($rows);
				
				$dia_desde = str_pad($dia_desde, 2, "0", STR_PAD_LEFT);
				$mes_desde = str_pad((array_search($mes_desde, $mes_esp)+1), 2, "0", STR_PAD_LEFT);
				$dia_hasta = str_pad($dia_hasta, 2, "0", STR_PAD_LEFT);
				$mes_hasta = str_pad((array_search($mes_hasta, $mes_esp)+1), 2, "0", STR_PAD_LEFT);
				
				$data = array(
					'success' => true,
					'dia_desde' => $dia_desde,
					'mes_desde' => $mes_desde,
					'anho_desde' => $anho_desde,
					'dia_hasta' => $dia_hasta,
					'mes_hasta' => $mes_hasta,
					'anho_hasta' => $anho_hasta,
					'data' => $rows
				);
			}
		}else{
			$data = array(
				"success" => false
			);
		}
		
		header('Content-type: application/json');
		echo json_encode($data);
	}
	public function get_sunat($library, $mes, $anho){ // Modificado por Rusbel
		//serverside.php?work=get_sunat&mes=01&anho=2014
		
		if(isset($mes) && isset($anho)){
			//$mes = $_GET['mes'];
			//$anho = $_GET['anho'];
		
			$url="http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?anho=$anho&mes=$mes";
			
			$reemplazar = array(
				"<table class=class=\"form-table\" border='1' cellpadding='0' cellspacing='0' width='81%' >"=>'<table border="1">',
				" class='beta' width='4%' align='center' class=\"ht\""=>'',
				" class='beta' width='10%' align='center' class=\"ht\""=>'',
				" width='8%' align='center' class=\"tne10\""=>'',
				" width='4%' align='center' class=\"H3\""=>'',
				'>  <'=>'><',
				'> '=>'>',
				' <'=>'<',
				"</tr><tr>"=>'',
				"<td><strong>"=>'</tr><tr><td><strong>'
			);
			
			$content = $library->obtener_contenidos($url,"<table class","</table>");
			
			if($content){
				$table = $library->filtrar( $content, $reemplazar );
				
				$head = "<td>Venta</td></tr>";
				$table_final = $library->substract_inicio($table, $head);
				
				$limpiar = array(
					"<tr><td>"=>'',
					"<td>"=>'',
					"<strong>"=>'',
					"</strong>"=>''
				);
				
				if ( $table_final ) {
					$rows = preg_split('#</tr>#i', $table_final);
					$i = 0;
					
					foreach ( $rows as $row ) {
						$rows[$i] = array_filter( $library->filtrar( preg_split('#</td>#i', $row) ,$limpiar) );
						
						if($rows[$i]){
							if($rows[$i][0]){
								$rows[$i]["fecha"] = $anho."-".str_pad($mes, 2, "0", STR_PAD_LEFT)."-".str_pad($rows[$i][0], 2, "0", STR_PAD_LEFT);
								
								unset($rows[$i][0]);
							}
							
							$rows[$i]["compra"] = $rows[$i][1];unset($rows[$i][1]);
							$rows[$i]["venta"] = $rows[$i][2];unset($rows[$i][2]);
						}
						
						$i++;
					}
				}
				
				$data = array(
					"success" => true,
					"año" => $anho,
					"mes" => $mes,
					"data" => $rows
				);
			}
		}else{
			$data = array(
				"success" => false
			);
		}
		
		header('Content-type: application/json');
		echo json_encode($data);
	}
	public function get_image($library){
		//serverside.php?work=get_image&tipo=interbancario
		//serverside.php?work=get_image&tipo=sbs
		//serverside.php?work=get_image&tipo=paralelo
		
		if(isset($_GET["tipo"])){
			$tipo = $_GET["tipo"];
			$fecha = date("Ymd");
			
			$img = "cache/".strtoupper(substr($tipo,0,4))."_".$fecha.".jpg";
			
			$img1 = "cache/PARA_".$fecha.".jpg";
			$img2 = "cache/INTE_".$fecha.".jpg";
			$img3 = "cache/SBS_".$fecha.".jpg";
			
			if(!file_exists($img1) || !file_exists($img2) || !file_exists($img3)){
				create_image($library);
			}
			
			$background = imagecreatefromjpeg($img);
			
			header('Content-type: image/jpg');
			imagejpeg($background, NULL, 100);
			imagedestroy($background);
		}
	}
	
	/*No es un metodo, son funciones especificas para get_image*/
	public function create_image($library){
		/*Utiliza libreria GD y NECESITA: Codificacion UTF-8 sin BOM*/
		
		$arr = return_array_bcr_range($library);
		$mes_esp = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
		
		$interlineado = 20;
		$tab = "             ";
		$tab2 = "          ";
		$tipos = array("interbancario", "sbs", "paralelo");
		$template = "template/fondo.jpg";
		
		if(file_exists($template)){		
			foreach ($tipos as $tipo){
				$background = imagecreatefromjpeg($template);
				
				$i = 3;
				$inicio = 0;
				$orden = 0;
				$fecha = array();
				$compra = array();
				$venta = array();
					
				$fila1 = "TIPO DE CAMBIO DOLAR - ". strtoupper($tipo);
				
				for($i; $i >= $inicio; $i--){
					$orden++;
					$num = preg_split("/-/", $arr[$i]["fecha"]);
					$fecha[$orden] = $num[2]. " " . $mes_esp[$num[1]-1];
					
					switch($tipo){
						case "interbancario":
							$x = 122;
							$compra[$orden] = $arr[$i]["compra_interbancario"];
							$venta[$orden] = $arr[$i]["venta_interbancario"];
							break;
						case "sbs":
							$x = 150;
							$compra[$orden] = $arr[$i]["compra_sbs"];
							$venta[$orden] = $arr[$i]["venta_sbs"];
							break;
						case "paralelo":
							$x = 135;
							$compra[$orden] = $arr[$i]["compra_paralelo"];
							$venta[$orden] = $arr[$i]["venta_paralelo"];
							break;
					}
				}
					
				$library->insertar_label( $background, $fila1, $x, 95,  "#333333", 9 );
				
				$fila2 = implode( "$tab2 ", $fecha );
				$library->insertar_label( $background, $fila2, 141, 105+$interlineado, "#993300", 8 );
				
				$fila3 = implode( "$tab ", $compra );
				$library->insertar_label( $background, $fila3, 141, 119+$interlineado, "#333333", 9 );
				
				$fila4 = implode( "$tab ", $venta );
				$library->insertar_label( $background, $fila4, 141, 134+$interlineado, "#333333", 9 );

				$fila5 = "COMPRA:";
				$library->insertar_label( $background, $fila5, 68, 120+$interlineado, "#333333", 8 );

				$fila6 = "VENTA:";
				$library->insertar_label( $background, $fila6, 68, 135+$interlineado, "#333333", 8 );
				
				$fecha = date("Ymd");
				
				header('Content-type: image/jpg');
				imagejpeg($background, "cache/".strtoupper(substr($tipo,0,4))."_".$fecha.".jpg", 100);
				imagedestroy($background);
			}
		}else{
			$error = "¡NO EXISTE EL TEMPLATE BASE!";
			
			$library->mostrar_error_jpg($error);
		}
	}
	public function return_array_bcr_range($library){
		//serverside.php?work=get_bcr_range
	
		$mes_esp = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");
	
		$dia_hasta = date("d");
		$mes_fin = date("m");
		$mes_hasta = $mes_esp[$mes_fin-1];
		$anho_hasta = date("Y");
		
		$desde = mktime(0, 0, 0, date("m"), date("d")-15, date("Y"));
		
		$dia_desde = date("d", $desde);
		$mes_ini = date("m", $desde);
		$mes_desde = $mes_esp[$mes_ini-1];
		$anho_desde = date("Y", $desde);
		
		$url="http://estadisticas.bcrp.gob.pe/consulta.asp?sIdioma=1&sTipo=1&sChkCount=12&sFrecuencia=D&Consulta=Consulta&chkDet1=1&chkDet1=5&chkDet1=6&chkDet1=7&chkDet1=8&chkDet1=9&txtanodesde=$anho_desde&txtanohasta=$anho_hasta&txtdiadesde=$dia_desde&txtdiahasta=$dia_hasta&txtmesdesde=$mes_desde&txtmeshasta=$mes_hasta";
		
		$reemplazar = array(
			'<table width="90%" border="1px" cellspacing="0" bordercolor="#CCCCCC">'=>'<table>',
			' scope="row" class="tiempo"'=>'',
			' scope="col" class="titulo"'=>'',
			'&nbsp;'=>'',
			'> <'=>'><',
			'> '=>'>',
			' <'=>'<',
			'th>'=>'td>',
			'TC Informal (S/. por US$) - Compra'=>'Compra',
			'TC Informal (S/. por US$) - Venta'=>'Venta',
			"Día/Mes/Año"=>'Fecha'
		);
	
		$content = $library->obtener_contenidos($url,"<table","</table>");
		
		if($content){
			$table = $library->filtrar( $content, $reemplazar);
			
			$head = "<td>Venta</td></tr>";
			$table_final = $library->substract_inicio($table, $head);

			$limpiar = array(
				"<tr><td>"=>'',
				"<td>"=>''
			);
			
			if ( $table_final ) {
				$rows = preg_split('#</tr>#i', $table_final);
				$i = 0;
				
				foreach ( $rows as $row ) {
					$rows[$i] = array_filter( $library->filtrar( preg_split('#</td>#i', $row) ,$limpiar) );
					
					if($rows[$i]){
						if($rows[$i][0]){
							$num = preg_split("/[a-zA-Z]+/",$rows[$i][0]);
							$str = array_filter(preg_split("/[0-9]+/",$rows[$i][0]));
							
							$dia = str_pad($num[0], 2, "0", STR_PAD_LEFT);
							$mes = str_pad(array_search( strtoupper( $str[1] ), $mes_esp )+1, 2, "0", STR_PAD_LEFT);
							if($num[1] <= substr($anho_hasta, -2)){
								$anho = str_pad($num[1], 4, $anho_hasta, STR_PAD_LEFT);
							}else{
								$anho = str_pad($num[1], 4, $anho_desde, STR_PAD_LEFT);
							}
							
							$rows[$i]["fecha"] = $anho."-".$mes."-".$dia;
							
							unset($rows[$i][0]);
						}
						if($rows[$i][1] != "n.d." && $rows[$i][2] != "n.d." && $rows[$i][3] != "n.d." && $rows[$i][4] != "n.d." && $rows[$i][5] != "n.d." && $rows[$i][6] != "n.d."){
							$rows[$i]["compra_interbancario"] = $rows[$i][1];unset($rows[$i][1]);
							$rows[$i]["venta_interbancario"] = $rows[$i][2];unset($rows[$i][2]);
							$rows[$i]["compra_sbs"] = $rows[$i][3];unset($rows[$i][3]);
							$rows[$i]["venta_sbs"] = $rows[$i][4];unset($rows[$i][4]);
							$rows[$i]["compra_paralelo"] = $rows[$i][5];unset($rows[$i][5]);
							$rows[$i]["venta_paralelo"] = $rows[$i][6];unset($rows[$i][6]);
						}else{
							unset($rows[$i]);
						}
					}
					
					$i++;
				}
			}
			$rows = array_filter($rows);
			$rows = array_reverse($rows);
		}
		
		return($rows);
	}
	

} //Cierra Class