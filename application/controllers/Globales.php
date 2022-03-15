<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once ("Secure_area.php");

class Globales extends Secure_area {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('globales_model');

		$this->load->library(array('session','form_validation'));

		// Define las acciones por Modulo! 
		$session['module_id'] = $this->router->class;
		$this->session->set_userdata($session);	
		$data['id_perfil'] = $this->session->userdata('id_perfil');
		$data['module_id'] = $this->session->userdata('module_id');
		$this->getModulesAccion($data['id_perfil'], $data['module_id']);
		$data['modo'] = '';
		$this->load->vars($data);
	}

	public function index()
	{
		// Listado de datos para el datatables()
		$data['bus_dato'] = $this->globales_model->ver(1);
		$this->load->view("globales/main", $data);
	}

	public function actualizar()
	{
        if($this->input->post('chkimaprod') == 1)
            $prod_images = '1';
        else
            $prod_images = '0';

        $data = array(               
            'ruc'=>$this->input->post('ruc'),
            'razon_social'=>$this->input->post('razon_social'),
            'nombre_corto'=>$this->input->post('nombre_corto'),
            'direccion'=>$this->input->post('direccion'),
            'telefono'=>$this->input->post('telefono'),
            'email'=>$this->input->post('email'),
            'web'=>$this->input->post('web'),
            'igv_empre'=>$this->input->post('igv_empre'),
            'num_bol_ven'=>$this->input->post('num_bol_ven'),
            'tema'=>$this->input->post('tema'),
            'id_owner' => $this->session->userdata('person_id'),
            'pv_prod_images' => $prod_images,
            'firma_ticket'=>$this->input->post('firma_ticket'),

            'mail_envio'=>$this->input->post('mail_envio'),
            'mail_envio_alias'=>$this->input->post('mail_envio_alias'),
            'mail_copia'=>$this->input->post('mail_copia'),
            'mail_responde'=>$this->input->post('mail_responde'),
            'mail_responde_alias'=>$this->input->post('mail_responde_alias'),
            'estado' => 1,
            'date_updated' => mdate("%Y-%m-%d", time()).' '.mdate("%H:%i:%s", time()),
            'persona_id_updated' => $this->session->userdata('person_id')
        );

		$this->globales_model->actualizar($this->input->post('id_global'), $data);
	}
    
    public function cargarFile()
    {
        if($_FILES["archivo"]["name"])
        {
            $id = $this->input->post('id');

            $archivo = $_FILES["archivo"]["name"];
            // FCPATH -> Obtiene la ruta del directorio principal del proyecto.
            $ruta_archivo = FCPATH."public/images/".$archivo;
            $tmp_imagen = $_FILES["archivo"]["tmp_name"];
            copy($tmp_imagen, $ruta_archivo);

            $this->globales_model->updateAdjuntoOrden($id, $archivo);
            echo $id;
        }
        else 
        {
            echo 0;
        }
    }    

}