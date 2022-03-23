<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Facturador extends Secure_area {
	
	function __construct()
	{
    	parent::__construct();
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$this->load->model('ventas_model');
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index(){
		$this->load->view("facturador/main");		
	}

	public function lista()
	{	
		try 
		{
			/*** connect to SQLite database ***/
			$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
			$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$resultado = $baseDeDatos->query("SELECT NUM_RUC
			,TIP_DOCU
			,NUM_DOCU
			--,FEC_CARG
			,substr(FEC_CARG,7,4) || '-' || substr(FEC_CARG,4,2)||'-'||substr(FEC_CARG,0,3) AS FEC_CARG
			,substr(FEC_GENE,7,4) || '-' || substr(FEC_GENE,4,2)||'-'||substr(FEC_GENE,0,3)||' '|| substr(FEC_GENE,12,8) AS FEC_GENE
			,FEC_ENVI 
			,DES_OBSE
			,NOM_ARCH
			,IND_SITU
			,TIP_ARCH			
			FROM DOCUMENTO;");
			$data = $resultado->fetchAll(PDO::FETCH_OBJ);
			header('Content-Type: application/json');
			echo json_encode($data);			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			echo "<br><br>Database -- NOT -- loaded successfully .. ";
			die( "<br><br>Query Closed !!! $error");
		}
	}

	public function volverEnviarSunat()
	{	
		try 
		{
			$resumen = $this->input->post('resumen');
			$ticket = $this->input->post('ticket');
			$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
			$update="SELECT IND_SITU FROM DOCUMENTO WHERE NOM_ARCH='$resumen';";
			$resultado = $baseDeDatos->query($update);
			$dato = $resultado->fetchAll(PDO::FETCH_OBJ);
			$verif = $dato[0]->IND_SITU;

			if($verif<>'03'){
				/*** connect to SQLite database ***/
				$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
				$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$update="UPDATE DOCUMENTO SET DES_OBSE='$ticket' , IND_SITU='08' WHERE NOM_ARCH='$resumen';";
				$resultado = $baseDeDatos->exec($update);
				$data = array('ok' => true);				
			}else{
				$data = array('ok' => false);	
			}
			header('Content-Type: application/json');
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			echo "<br><br>Database -- NOT -- loaded successfully .. ";
			die( "<br><br>Query Closed !!! $error");
		}
	}

	public function obtticket()
	{	
		try 
		{	
			$id = $this->input->post('id_resumen');
			$resumen = $this->input->post('resumen');
			/*** connect to SQLite database ***/
			$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
			$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$update="SELECT DES_OBSE FROM DOCUMENTO WHERE NOM_ARCH='$resumen';";
			$resultado = $baseDeDatos->query($update);
			$dato = $resultado->fetchAll(PDO::FETCH_OBJ);
			$verif = substr($dato[0]->DES_OBSE,0,11 );
			if($verif=='Nro. Ticket'){
				$data = array('ntickect' => $dato[0]->DES_OBSE);			
				$this->ventas_model->actresumen($id,$data);
				$res = array('ntickect' => $dato[0]->DES_OBSE , 'ok'=> true);
			}else{
				$res = array('ntickect' => $dato[0]->DES_OBSE , 'ok'=> false);
			}
			header('Content-Type: application/json');
			echo json_encode($res);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			echo "<br><br>Database -- NOT -- loaded successfully .. ";
			die( "<br><br>Query Closed !!! $error");
		}
	}

	public function validarResumen(){
		$resumenes = $this->ventas_model->resumencabecera();

		foreach($resumenes  as $val){
			if($val->ntickect==''){

				try 
				{	
					$id = $val->id_resumen;
					$resumen = $val->NOM_ARCH;
					/*** connect to SQLite database ***/
					$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
					$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$update="SELECT DES_OBSE FROM DOCUMENTO WHERE NOM_ARCH='$resumen';";
					$resultado = $baseDeDatos->query($update);
					$dato = $resultado->fetchAll(PDO::FETCH_OBJ);
					$verif = substr($dato[0]->DES_OBSE,0,11 );
					if($verif=='Nro. Ticket'){
						$data = array('ntickect' => $dato[0]->DES_OBSE);			
						$this->ventas_model->actresumen($id,$data);
					}
				}
				catch(PDOException $e)
				{
					echo $e->getMessage();
					echo "<br><br>Database -- NOT -- loaded successfully .. ";
					die( "<br><br>Query Closed !!! $error");
				}
			}			
			
		}

		$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
		$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$resultado = $baseDeDatos->query("SELECT * FROM DOCUMENTO");
		$lista_sqlite = $resultado->fetchAll(PDO::FETCH_OBJ);
		
		foreach($lista_sqlite as $rs){
			if($rs->IND_SITU='05' && substr($rs->NUM_DOCU,0,2)=='RC'){
				foreach($resumenes  as $val){
				if($val->NOM_ARCH == $rs->NOM_ARCH){
					$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
					$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$update="UPDATE DOCUMENTO SET DES_OBSE='$val->ntickect' , IND_SITU='08' WHERE NOM_ARCH='$rs->NOM_ARCH';";
					$resultado = $baseDeDatos->exec($update);
				}
				}
			}/*else if($rs->IND_SITU='05' && substr($rs->NUM_DOCU,0,1)=='F'){
					$baseDeDatos = new PDO("sqlite:C:\SUNAT\bd\BDFacturador.db");
					$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$update="UPDATE DOCUMENTO SET IND_SITU='02' WHERE NOM_ARCH='$rs->NOM_ARCH';";
					$resultado = $baseDeDatos->exec($update);
			}*/
		}

	}


}