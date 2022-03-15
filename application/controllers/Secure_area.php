<?php
class Secure_area extends CI_Controller 
{
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	protected $g_id_global;
	protected $g_ruc;
	protected $g_razon_social;
	protected $g_nombre_corto;
	protected $g_direccion;
	protected $g_telefono;
	protected $g_email;
	protected $g_distrito;
	protected $g_ciudad;

	protected $g_pv_prod_images;
	protected $g_tc;
	protected $de_fecha_nace;
	protected $fecha_actual;
	protected $g_igv;
	protected $g_moneda;
	protected $g_logotipo;
	protected $g_firma_ticket;
	protected $g_mail_envio;
	protected $g_mail_envio_alias;
	protected $g_mail_copia;
	protected $g_mail_responde;
	protected $g_mail_responde_alias;

	function __construct()
	{
		parent::__construct();
		$this->load->model('privilegios_model');
		$this->load->model('globales_model');

		$this->load->library(array('session'));
		
		date_default_timezone_set('America/Lima');

		$data['id_user'] = $this->session->userdata('id_user');
        $data['person_id'] = $this->session->userdata('person_id');
        $data['username'] = $this->session->userdata('username');
        $data['id_perfil'] = $this->session->userdata('id_perfil');

        if($this->session->userdata('username') == false)
		redirect('login');

		if($this->privilegios_model->accessController($this->router->class, $data['id_perfil']) == 0 ) 		
		redirect('NoTieneAcceso.-__-');

		//load up global data
		$data['allowed_modules'] = $this->privilegios_model->get_allowed_modules($data['person_id'], $data['id_perfil']);
		$data['datos_emp'] = $this->privilegios_model->verEmpleado($data['person_id']);



		$globales = $this->globales_model->listar();
        
        $data['g_id_global'] = $globales[0]->id_global;
		$data['g_ruc'] = $globales[0]->ruc;
		$data['g_razon_social'] = $globales[0]->razon_social;
		$data['g_direccion'] = $globales[0]->direccion; // -- declarar en el view: $g_direccion
		$data['g_telefono'] = $globales[0]->telefono;
		$data['g_email'] = $globales[0]->email;        
        $data['g_tema'] = $globales[0]->tema;
        $data['g_logotipo'] = $globales[0]->logotipo;
        $data['g_tc'] = $globales[0]->tc;
        $data['g_igv'] = $globales[0]->igv_empre;
        $data['g_moneda'] = $globales[0]->simbolo_mn_empre;
        $data['g_pv_prod_images'] = $globales[0]->pv_prod_images;

       	$data['fecha_actual'] = mdate("%Y-%m-%d", time());
       	$data['de_foto'] = $data['datos_emp'][0]->imagen;
       	//$data['de_fecha_nace'] = $data['datos_emp'][0]->fecha_nace;

       	// Variables para los Controllers
       	$this->g_id_global = $globales[0]->id_global;
       	$this->g_ruc = $globales[0]->ruc;
       	$this->g_razon_social = $globales[0]->razon_social;
       	$this->g_nombre_corto = $globales[0]->nombre_corto;
       	$this->g_direccion = $globales[0]->direccion;
       	$this->g_telefono = $globales[0]->telefono;
       	$this->g_email = $globales[0]->email;
       	$this->g_web = $globales[0]->web;
       	$this->g_distrito = $globales[0]->distrito;
       	$this->g_ciudad = $globales[0]->ciudad;

       	$this->g_pv_prod_images = $globales[0]->pv_prod_images;
       	$this->g_tc = $globales[0]->tc;
       	$this->de_fecha_nace = $data['datos_emp'][0]->fecha_nace;
       	$this->fecha_actual = mdate("%Y-%m-%d", time());
       	$this->g_igv = $globales[0]->igv_empre;
       	$this->g_moneda = $globales[0]->simbolo_mn_empre;
       	$this->g_logotipo = $globales[0]->logotipo;
       	$this->g_firma_ticket = $globales[0]->firma_ticket;

       	$this->g_mail_envio = $globales[0]->mail_envio;
		$this->g_mail_envio_alias = $globales[0]->mail_envio_alias;
		$this->g_mail_copia = $globales[0]->mail_copia;
		$this->g_mail_responde = $globales[0]->mail_responde;
		$this->g_mail_responde_alias = $globales[0]->mail_responde_alias;
		$this->g_mail_responde_alias = $globales[0]->web;
       	// --

		$this->load->vars($data); //Envia valores al VIEW por default
	}
	
	public function getModulesAccion($id_perfil, $module_id){
		$data['allowed_modules_accion'] = $this->privilegios_model->get_allowed_modules_accion($id_perfil, $module_id);
		return $this->load->vars($data);
	}
	
	public function actualizarGlobales($data)
	{
		$this->globales_model->actualizarGlobales($data);
	}
}
?>