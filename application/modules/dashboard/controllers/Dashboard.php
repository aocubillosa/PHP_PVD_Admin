<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("dashboard_model");
		$this->load->model("general_model");
		$this->load->helper('form');
    }
	
	/**
	 * Dashboard
	 * @since 14/07/2024
     * @author AOCUBILLOSA
	 */
	public function admin()
	{
		$data = array();
		$arrParam = array(
			"table" => "param_sedes",
			"order" => "id_sede",
			"column" => "id_sede",
			"id" => $this->session->userdata("sede")
		);
		$data['sedes'] = $this->general_model->get_basic_search($arrParam);
		$visitantes = $this->dashboard_model->permisos_visitantes();
		$data['visitantes'] = $visitantes;
		$data["view"] = "dashboard";
		$this->load->view("layout_calendar", $data);
	}
}