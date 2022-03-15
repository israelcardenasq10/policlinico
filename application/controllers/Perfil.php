<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Perfil extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('perfil_model');
		$this->load->library(array('session','form_validation'));
		$session['module_id'] = $this->router->class; // Obtiene el nombre de la clase, es igual al module_id de la BD;
		$this->session->set_userdata($session);
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		// $this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$this->load->vars($data);
	}

	public function index()
	{	
		$this->load->view("partial/header");
		$this->load->view("perfil/main");
		$this->load->view("partial/footer");
	}

	public function listar()
	{
		header('Content-type: application/json; charset=utf-8');
		$id_perfil =  $this->input->post('id_perfil');
		$data = $this->perfil_model->listar($id_perfil);
		echo json_encode($data);
	}

	public function getByID()
	{
		header('Content-type: application/json; charset=utf-8');
		$id_ma =  $this->input->post('id_ma');
		$data = $this->perfil_model->getById($id_ma);
		echo json_encode($data);
	}

	public function save()
	{		
		$id_ma= $this->input->post('id_ma');
		$data = array(
				'id_perfil' => $this->input->post('id_perfil'),
				'module_id' => $this->input->post('module_id'),
				'accion' => $this->input->post('accion'),
				'alias' => $this->input->post('alias'),
				'tipo' => $this->input->post('tipo'),
				'sort' => $this->input->post('sort')
			);
		if($id_ma==0){
			$this->perfil_model->insert($data);

		}else{
			$where = array('id_ma'=> $id_ma);
			$this->perfil_model->update($where , $data);
		}
		
	}

	public function eliminar()
	{
		$this->perfil_model->eliminar($this->input->post('id'));
		header('Content-type: application/json; charset=utf-8');
		$data = array('OK'=>true);
		echo json_encode($data);
	}

	// Subir Archivos
	public function cargarFile()
    {
        if($_FILES["archivo"]["name"])
        {
            $id = $this->input->post('id');

            $archivo = $_FILES["archivo"]["name"];
            // FCPATH -> Obtiene la ruta del directorio principal del proyecto.
            $ruta_archivo = FCPATH."public/images/users/perfil/".$archivo;
            $tmp_imagen = $_FILES["archivo"]["tmp_name"];
            copy($tmp_imagen, $ruta_archivo);

            $this->perfil_model->updateImagen($id, $archivo);
            echo $id;
        }
        else 
        {
            echo 0;
        }
    }

}