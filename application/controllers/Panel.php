<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");

class Panel extends Secure_area {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('graficos_model');

		$this->load->library(array('session','form_validation', ''));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		// --

		$this->load->vars($data);
	}
	
	function index()
	{
		// Gráfico Pie:
		$lista_graf_1 = $this->graficos_model->graficarVentaProductoXDia();
		$data['lista_graf_1'] = json_encode($lista_graf_1);
		// --
		
		// Gráfico Line
		$str_inicio  = strtotime(date('Y') . 'W' . str_pad(date('W') , 2, '0', STR_PAD_LEFT)); // Muestra Lunes!
		// $str_fin = strtotime('next Saturday'); //Muestra Sábado!
		$str_fin = strtotime('Sunday'); // Muestra Domingo
		// echo $str_fin;
		$fecha_ini_sem = date('Y-m-d', $str_inicio);
		$fecha_fin_sem = date('Y-m-d', $str_fin);
		// echo $fecha_ini_sem.'-----'.$fecha_fin_sem;
		$lista_graf_2 = array();
		// echo $fecha_ini_sem;
		for($i = $fecha_ini_sem; $i <= $fecha_fin_sem; $i = date("Y-m-d", strtotime($i ."+ 1 days")))
		{
			// echo 'sdfsdfsdfsd<br>';
			@$lista = $this->graficos_model->graficarVentaXDiaSemana($i);
			
			$fecha = $i;
			if(@$lista):
				$moneda = $lista[0]->moneda;
				$costo = $lista[0]->costo;
				$venta = $lista[0]->total_venta;
			else:
				$moneda = (@$moneda) ? $moneda : $this->g_moneda;
				$costo = 0;
				$venta = 0;
			endif;

			$valor = array(
			            "fecha" => $fecha,
			            "moneda" => $moneda,
			            "costo" => $costo,
			            "total_venta" => $venta
			        );
			array_push($lista_graf_2, $valor);
		}
		$data['lista_graf_2'] = json_encode($lista_graf_2);
		// --

		// Gráfico Barra:
		$lista_graf_3 = $this->graficos_model->graficarCostoVentaXMesesAnio();
		$data['lista_graf_3'] = json_encode($lista_graf_3);
		// --

		$this->load->view("panel", $data);
	}


	//AJAX - JSON
	public function actualizarGraficoPie()
	{
		$lista_graf_1 = $this->graficos_model->graficarVentaProductoXDia();
		header('Content-type: application/json; charset=utf-8');
		print json_encode($lista_graf_1);
	}

	public function actualizarGraficoLine()
	{
		$str_inicio  = strtotime(date('Y') . 'W' . str_pad(date('W') , 2, '0', STR_PAD_LEFT));
		$str_fin = strtotime('Sunday'); // Muestra Domingo
		$fecha_ini_sem = date('Y-m-d', $str_inicio);
		$fecha_fin_sem = date('Y-m-d', $str_fin);

		$lista_graf_2 = array();
		for($i = $fecha_ini_sem; $i <= $fecha_fin_sem; $i = date("Y-m-d", strtotime($i ."+ 1 days")))
		{
			@$lista = $this->graficos_model->graficarVentaXDiaSemana($i);
			
			$fecha = $i;
			if(@$lista):
				$moneda = $lista[0]->moneda;
				$costo = $lista[0]->costo;
				$venta = $lista[0]->total_venta;
			else:
				$moneda = (@$moneda) ? $moneda : $this->g_moneda;
				$costo = 0;
				$venta = 0;
			endif;

			$valor = array(
			            "fecha" => $fecha,
			            "moneda" => $moneda,
			            "costo" => $costo,
			            "total_venta" => $venta
			        );
			array_push($lista_graf_2, $valor);
		}

		header('Content-type: application/json; charset=utf-8');
		print json_encode($lista_graf_2);
	}

	public function actualizarGraficoBar()
	{
		$lista_graf_3 = $this->graficos_model->graficarCostoVentaXMesesAnio();
		header('Content-type: application/json; charset=utf-8');
		print json_encode($lista_graf_3);
	}

	
	public function salir()
	{
		$this->session->sess_destroy();
		//$this->load->view('login');
		redirect('login');
	}

}
?>