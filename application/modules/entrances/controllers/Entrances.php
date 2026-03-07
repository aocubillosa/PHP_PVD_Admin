<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entrances extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("entrances_model");
        $this->load->model("general_model");
        $this->load->helper('form');
    }

    /**
	 * Buscar Visitantes
	 */
	public function searchVisitors()
	{
		$data["view"] = 'search_visitors';
		$this->load->view("layout_calendar", $data);
	}

	/**
	 * Verificar Visitante
	 */
	public function verifyVisitor()
	{
		header('Content-Type: application/json');
		$data = array();
		$arrParam['numeroDocumento'] = $this->input->post('documento');
		$infoVisitors = $this->general_model->get_visitors($arrParam);
		if (!empty($infoVisitors)) {
			$data["result"] = true;
			if ($infoVisitors[0]['state'] == 1) {
				$data['estado'] = true;
				$arrParam['idVisitante'] = $infoVisitors[0]['id_visitante'];
				$infoAccess = $this->entrances_model->get_access_by_visitor($arrParam);
				if (!empty($infoAccess)) {
					$data['ingreso'] = true;
				} else {
					$data['ingreso'] = false;
				}
			} else {
				$data['estado'] = false;
			}
		} else {
			$data["result"] = false;
		}
		echo json_encode($data);
	}

	/**
	 * Generar Acceso
	 */
	public function generateAccess($documento)
	{
		$data['infoVisitors'] = "";
		$arrParam['numeroDocumento'] = $documento;
		$data['infoVisitors'] = $this->general_model->get_visitors($arrParam);
		$data["view"] = 'generate_access';
		$this->load->view("layout_calendar", $data);
	}

	/**
	 * Guardar Acceso
	 */
	public function save_access()
	{
		header('Content-Type: application/json');
		$data = array();
		$documento = $this->input->post('numeroDocumento');
		$pass = $this->generaPass();
        $this->load->library('ciqrcode');
        $llave = $pass . $documento;
        $rutaImagen = "images/visitantes/QR/" . $llave . "_qr_code.png";
        $params['data'] = $documento;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $rutaImagen;
        $this->ciqrcode->generate($params);
        $data_qr = [
            'rutaImagen' => $rutaImagen,
            'llave' => $llave
        ];
		if ($idPermiso = $this->entrances_model->saveAccess($data_qr)) {
			$data["result"] = true;
			$data["permiso"] = $idPermiso;
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Generar Pass Codigo QR
	 */
	public function generaPass()
	{
		$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$longitudCadena = strlen($cadena);
		$pass = "";
		$longitudPass=20;
		for($i=1; $i<=$longitudPass ; $i++) {
			$pos = rand(0,$longitudCadena-1);
			$pass .= substr($cadena,$pos,1);
		}
		return $pass;
	}

	/**
	 * Mostrar Codigo QR
	 */
	public function show_qrcode($idPermiso)
	{
		$arrParam = array(
			"idPermiso" => $idPermiso
		);
		$data['infoPermiso'] = $this->entrances_model->get_access_by_permissions($arrParam);
		$data["view"] = 'show_qrcode';
		$this->load->view("layout_calendar", $data);
	}

	/**
	 * Listado Permisos Visitantes
	 */
	public function permissionsVisitantes()
	{
		$data['infoVisitantes'] = $this->entrances_model->get_infoVisitantes();
		$data["view"] = 'permissionsVisitantes';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Detalle Permisos
     */
    public function cargarModalPermisos()
	{
		$arrParam = array(
			"idPermiso" => $this->input->post("idPermiso")
		);
		$data['infoPermiso'] = $this->entrances_model->get_access_by_permissions($arrParam);
		$this->load->view("permisos_modal", $data);
    }

    /**
     * Eliminar Permisos
     */
    public function deletePermissions()
	{
		header('Content-Type: application/json');
		$msj = "Se eliminó el permiso!";
		if ($this->entrances_model->delete_permissions()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }

    /**
	 * Listado Ingresos Visitantes
	 */
	public function incomesVisitantes()
	{
		$fecha_actual = date('Y-m-d');
		$data['infoVisitantes'] = $this->entrances_model->get_infoVisitantes();
		if ($data['infoVisitantes']) {
			for ($i=0; $i<count($data['infoVisitantes']); $i++) {
				$data['infoVisitantes'][$i]['ingreso'] = false;
				$infoIngreso = $this->entrances_model->get_infoIngresos($data['infoVisitantes'][$i]['id_visitante']);
				if ($infoIngreso) {
					$fecha = $infoIngreso['fecha_ingreso'];
					$ingreso = explode(' ', $fecha);
					$fecha_ingreso = $ingreso[0];
					if ($fecha_ingreso == $fecha_actual) {
						$data['infoVisitantes'][$i]['ingreso'] = true;
					}
				}
			}
		}
		$data["view"] = 'incomesVisitantes';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Registrar Ingreso
     */
    public function checkIn()
	{
		header('Content-Type: application/json');
		$msj = "Se registró el ingreso!";
		if ($this->entrances_model->check_in()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }
}