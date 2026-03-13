<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("settings_model");
        $this->load->model("entrances/entrances_model");
        $this->load->model("general_model");
		$this->load->helper('form');
    }
	
	/**
	 * Listado Usuarios
	 */
	public function users($state=1)
	{
		$data['state'] = $state;
		if($state == 1){
			$arrParam = array("filtroState" => TRUE);
		} else {
			$arrParam = array("state" => $state);
		}
		$data['info'] = $this->general_model->get_users($arrParam);
		$data["view"] = 'users';
		$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Formulario Usuario
     */
    public function cargarModalUsers() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = FALSE;
		$data["idUser"] = $this->input->post("idUser");
		$arrParam = array("filtro" => TRUE);
		$data['roles'] = $this->general_model->get_roles($arrParam);
		$arrParam = array(
			"table" => "param_tipo_documento",
			"order" => "tipo_documento",
			"id" => "x"
		);
		$data['tipo_documento'] = $this->general_model->get_basic_search($arrParam);
		$arrParam = array(
			"table" => "param_sedes",
			"order" => "nombre_sede",
			"id" => "x"
		);
		$data['sedes'] = $this->general_model->get_basic_search($arrParam);
		if ($data["idUser"] != 'x') {
			$arrParam = array(
				"table" => "usuarios",
				"order" => "id_user",
				"column" => "id_user",
				"id" => $data["idUser"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("users_modal", $data);
    }

    /**
	 * Guardar Usuario
	 */
	public function save_user()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idUser = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo usuario!";
		if ($idUser != '') {
			$msj = "Se actualizó el usuario!";
		}
		$num_doc = $this->input->post('numeroDocumento');
		$log_user = $this->input->post('user');
		$email_user = $this->input->post('email');
		$result_user = false;
		$result_email = false;
		$result_ldap = false;
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "numero_documento",
			"value" => $num_doc
		);
		$result_documento = $this->settings_model->verifyUser($arrParam);
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "log_user",
			"value" => $log_user
		);
		$result_user = $this->settings_model->verifyUser($arrParam);
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "email",
			"value" => $email_user
		);
		$result_email = $this->settings_model->verifyUser($arrParam);
		$data["state"] = $this->input->post('state');
		if ($idUser == '') {
			$data["state"] = 1;
		}
		if ($result_documento || $result_user || $result_email)
		{
			$data["result"] = "error";
			if($result_documento)
			{
				$data["mensaje"] = " Error. El número de documento ya existe.";
			}
			if($result_user)
			{
				$data["mensaje"] = " Error. El usuario ya existe.";
			}
			if($result_email)
			{
				$data["mensaje"] = " Error. El correo ya existe.";
			}
			if($result_user && $result_email)
			{
				$data["mensaje"] = " Error. El usuario y el correo ya existen.";
			}
		} else {
			if ($this->settings_model->saveUser()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		}
		echo json_encode($data);
    }
	
	/**
	 * Guardar Usuario con LDAP
	 */
	/*public function save_user()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idUser = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo usuario!";
		if ($idUser != '') {
			$msj = "Se actualizó el usuario!";
		}
		$num_doc = $this->input->post('numeroDocumento');
		$log_user = $this->input->post('user');
		$email_user = $this->input->post('email');
		$result_user = false;
		$result_email = false;
		$result_ldap = false;
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "numero_documento",
			"value" => $num_doc
		);
		$result_documento = $this->settings_model->verifyUser($arrParam);
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "log_user",
			"value" => $log_user
		);
		$result_user = $this->settings_model->verifyUser($arrParam);
		$arrParam = array(
			"idUser" => $idUser,
			"column" => "email",
			"value" => $email_user
		);
		$result_email = $this->settings_model->verifyUser($arrParam);
		$data["state"] = $this->input->post('state');
		if ($idUser == '') {
			$data["state"] = 1;
			$arrParam = array(
				"table" => "parametros",
				"order" => "id_parametro",
				"id" => "x"
			);
			$parametric = $this->general_model->get_basic_search($arrParam);
			$ldap_host = $parametric[6]["parametro_valor"];
			$ldap_port = $parametric[7]["parametro_valor"];
			$ldap_domain = $parametric[8]["parametro_valor"];
			$ldap_binddn = $parametric[9]["parametro_valor"];
			$ds = ldap_connect("$ldap_host", "$ldap_port") or die("No es posible conectar con el directorio activo.");
	        if (!$ds) {
	            echo "<br /><h4>Servidor LDAP no disponible</h4>";
	            @ldap_close($ds);
	        } else {
	        	$ldapuser = $this->session->userdata('logUser');
				$ldappass = ldap_escape($this->session->userdata('password'), ".,_,-,+,*,#,$,%,&,@", LDAP_ESCAPE_FILTER);
	            $ldapdominio = "$ldap_domain";
	            $ldapusercn = $ldapdominio . "\\" . $ldapuser;
	            $binddn = "$ldap_binddn";
	            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        		ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
	            $r = @ldap_bind($ds, $ldapusercn, $ldappass);
	            if (!$r) {
	                @ldap_close($ds);
	                $data["msj"] = "Error de autenticación. Por favor revisar usuario y contraseña de red.";
	                $this->session->sess_destroy();
					$this->load->view('login', $data);
	            } else {
	            	$filter = "(&(sAMAccountName=" . $log_user . ")(mail=" . $email_user . "))";
	            	$attributes = array('sAMAccountName', 'mail');
	            	$result = @ldap_search($ds, $binddn, $filter, $attributes);
	            	if (@ldap_count_entries($ds, $result) == 1) {
	            		$result_ldap = false;
	            	} else {
	            		$result_ldap = true;
	            	}
	            }
	        }
		}
		if ($result_documento || $result_user || $result_email || $result_ldap)
		{
			$data["result"] = "error";
			if($result_documento)
			{
				$data["mensaje"] = " Error. El número de documento ya existe.";
			}
			if($result_user)
			{
				$data["mensaje"] = " Error. El usuario ya existe.";
			}
			if($result_email)
			{
				$data["mensaje"] = " Error. El correo ya existe.";
			}
			if($result_user && $result_email)
			{
				$data["mensaje"] = " Error. El usuario y el correo ya existen.";
			}
			if ($result_ldap) {
				$data["mensaje"] = " Error. El usuario no existe en el directorio activo.";
			}
		} else {
			if ($this->settings_model->saveUser()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		}
		echo json_encode($data);
    }*/

    /**
	 * Restablecer Contraseña
	 */
    public function resetPassword($idUser)
	{
		header('Content-Type: application/json');
		$msj = "Se restablecio la contraseña!";
		if ($this->settings_model->resetUserPassword($idUser)) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Listado Visitantes
	 */
	public function visitors($state=1)
	{
		$data['state'] = $state;
		if($state == 1){
			$arrParam = array("filtroState" => TRUE);
		} else {
			$arrParam = array("state" => $state);
		}
		$data['info'] = $this->general_model->get_visitors($arrParam);
		if ($data['info']) {
			$i = 0;
			foreach ($data['info'] as $lista):
				$arrParam = array(
					"numeroDocumento" => $lista['numero_documento']
				);
				$infoPermiso = $this->entrances_model->get_access_by_permissions($arrParam);
				if ($infoPermiso) {
					$data['info'][$i]['permiso'] = $infoPermiso['id_permiso'];
				} else {
					$data['info'][$i]['permiso'] = "";
				}
				$i++;
			endforeach;
		}
		$data["view"] = 'visitors';
		$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Formulario Visitante
     */
    public function cargarModalVisitors() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = FALSE;
		$data["idVisitor"] = $this->input->post("idVisitor");
		$arrParam = array(
			"table" => "param_tipo_documento",
			"order" => "tipo_documento",
			"id" => "x"
		);
		$data['tipo_documento'] = $this->general_model->get_basic_search($arrParam);
		$arrParam = array(
			"table" => "param_ocupacion",
			"order" => "ocupacion",
			"id" => "x"
		);
		$data['ocupacion'] = $this->general_model->get_basic_search($arrParam);
		if ($data["idVisitor"] != 'x') {
			$arrParam = array(
				"table" => "visitantes",
				"order" => "id_visitante",
				"column" => "id_visitante",
				"id" => $data["idVisitor"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("visitors_modal", $data);
    }

    /**
	 * Guardar Visitante
	 */
	public function save_visitor()
	{
		header('Content-Type: application/json');
		$data = array();
		$idVisitor = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo visitante!";
		if ($idVisitor != '') {
			$msj = "Se actualizó el visitante!";
		}
		$num_doc = $this->input->post('numeroDocumento');
		$arrParam = array(
			"idVisitor" => $idVisitor,
			"column" => "numero_documento",
			"value" => $num_doc
		);
		$result_documento = $this->settings_model->verifyVisitor($arrParam);
		$data["state"] = $this->input->post('state');
		if ($idVisitor == '') {
			$data["state"] = 1;
		}
		if($result_documento) {
			$data["result"] = "error";
			$data["mensaje"] = " Error. El número de documento ya existe.";
		} else {
			if ($this->settings_model->saveVisitor()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		}
		echo json_encode($data);
	}

	/**
	 * Listado Inventario
	 */
	public function inventory()
	{
		$idUser = $this->session->userdata("id");
		$arrParam = array(
			"idUser" => $idUser
		);
		$data['info'] = $this->general_model->get_inventory($arrParam);
		$data["view"] = 'inventory';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Inventario
     */
    public function cargarModalInventory() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = FALSE;
		$data["idInventario"] = $this->input->post("idInventario");
		$arrParam = array(
			"table" => "param_elementos",
			"order" => "elemento",
			"id" => "x"
		);
		$data['elementos'] = $this->general_model->get_basic_search($arrParam);
		$arrParam = array(
			"table" => "param_marcas",
			"order" => "marca",
			"id" => "x"
		);
		$data['marcas'] = $this->general_model->get_basic_search($arrParam);
		$arrParam = array(
			"table" => "param_estados",
			"order" => "estado",
			"id" => "x"
		);
		$data['estados'] = $this->general_model->get_basic_search($arrParam);
		if ($data["idInventario"] != 'x') {
			$arrParam = array(
				"table" => "inventario",
				"order" => "id_inventario",
				"column" => "id_inventario",
				"id" => $data["idInventario"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("inventory_modal", $data);
    }

    /**
	 * Guardar Inventario
	 */
	public function save_inventory()
	{
		header('Content-Type: application/json');
		$data = array();
		$idInventario = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo elemento al inventario!";
		if ($idInventario != '') {
			$msj = "Se actualizó el elemento del inventario!";
		}
		$placa = $this->input->post('placa');
		$arrParam = array(
			"idInventario" => $idInventario,
			"column" => "placa",
			"value" => $placa
		);
		$result_placa = $this->settings_model->verifyInventory($arrParam);
		if($result_placa) {
			$data["result"] = "error";
			$data["mensaje"] = " Error. El número de placa ya existe.";
		} else {
			if ($this->settings_model->saveInventory()) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
		}
		echo json_encode($data);
	}

	/**
	 * Listado Elementos
	 */
	public function elements()
	{
		$arrParam = array(
			"table" => "param_elementos",
			"order" => "elemento",
			"id" => "x"
		);
		$data['elemento'] = $this->general_model->get_basic_search($arrParam);
		$data["view"] = 'elements';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Elemento
     */
    public function cargarModalElements() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = "";
		$data["idElemento"] = $this->input->post("idElemento");
		if ($data["idElemento"] != 'x') {
			$arrParam = array(
				"table" => "param_elementos",
				"order" => "id_elemento",
				"column" => "id_elemento",
				"id" => $data["idElemento"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("elements_modal", $data);
    }

    /**
	 * Guardar Elemento
	 */
	public function save_element()
	{
		header('Content-Type: application/json');
		$data = array();
		$idElemento = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo elemento!";
		if ($idElemento != '') {
			$msj = "Se actualizó el elemento!";
		}
		if ($this->settings_model->saveElement()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Eliminar Elemento
	 */
	public function delete_element()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idElemento = $this->input->post('identificador');
		$arrParam = array(
			"table" => "param_elementos",
			"primaryKey" => "id_elemento",
			"id" => $idElemento
		);
		if ($this->general_model->deleteRecord($arrParam)) 
		{
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Has eliminado el elemento.');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }

    /**
	 * Listado Marcas
	 */
	public function marks()
	{
		$arrParam = array(
			"table" => "param_marcas",
			"order" => "marca",
			"id" => "x"
		);
		$data['marcas'] = $this->general_model->get_basic_search($arrParam);
		$data["view"] = 'marks';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Marca
     */
    public function cargarModalMarks() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = "";
		$data["idMarca"] = $this->input->post("idMarca");
		if ($data["idMarca"] != 'x') {
			$arrParam = array(
				"table" => "param_marcas",
				"order" => "id_marca",
				"column" => "id_marca",
				"id" => $data["idMarca"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("marks_modal", $data);
    }

    /**
	 * Guardar Marca
	 */
	public function save_mark()
	{
		header('Content-Type: application/json');
		$data = array();
		$idMarca = $this->input->post('hddId');
		$msj = "Se adicionó una nueva marca!";
		if ($idMarca != '') {
			$msj = "Se actualizó la marca!";
		}
		if ($this->settings_model->saveMark()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Eliminar Marca
	 */
	public function delete_mark()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idMarca = $this->input->post('identificador');
		$arrParam = array(
			"table" => "param_marcas",
			"primaryKey" => "id_marca",
			"id" => $idMarca
		);
		if ($this->general_model->deleteRecord($arrParam))
		{
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Has eliminado la marca.');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }

	/**
	 * Listado Ocupaciones
	 */
	public function occupations()
	{
		$arrParam = array(
			"table" => "param_ocupacion",
			"order" => "ocupacion",
			"id" => "x"
		);
		$data['ocupacion'] = $this->general_model->get_basic_search($arrParam);
		$data["view"] = 'occupations';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Ocupacion
     */
    public function cargarModalOccupations() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = "";
		$data["idOcupacion"] = $this->input->post("idOcupacion");
		if ($data["idOcupacion"] != 'x') {
			$arrParam = array(
				"table" => "param_ocupacion",
				"order" => "id_ocupacion",
				"column" => "id_ocupacion",
				"id" => $data["idOcupacion"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("occupations_modal", $data);
    }

    /**
	 * Guardar Ocupacion
	 */
	public function save_occupation()
	{
		header('Content-Type: application/json');
		$data = array();
		$idOcupacion = $this->input->post('hddId');
		$msj = "Se adicionó una nueva ocupación!";
		if ($idOcupacion != '') {
			$msj = "Se actualizó la ocupación!";
		}
		if ($this->settings_model->saveOccupation()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Eliminar Ocupacion
	 */
	public function delete_occupation()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idOcupacion = $this->input->post('identificador');
		$arrParam = array(
			"table" => "param_ocupacion",
			"primaryKey" => "id_ocupacion",
			"id" => $idOcupacion
		);
		if ($this->general_model->deleteRecord($arrParam)) 
		{
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Has eliminado la ocupación.');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }

    /**
	 * Listado Sedes
	 */
	public function sedes()
	{
		$arrParam = array(
			"table" => "param_sedes",
			"order" => "nombre_sede",
			"id" => "x"
		);
		$data['sedes'] = $this->general_model->get_basic_search($arrParam);
		$data["view"] = 'sedes';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Sedes
     */
    public function cargarModalSedes() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = "";
		$data["idSede"] = $this->input->post("idSede");
		if ($data["idSede"] != 'x') {
			$arrParam = array(
				"table" => "param_sedes",
				"order" => "id_sede",
				"column" => "id_sede",
				"id" => $data["idSede"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("sedes_modal", $data);
    }

    /**
	 * Guardar Sede
	 */
	public function save_sede()
	{
		header('Content-Type: application/json');
		$data = array();
		$idSede = $this->input->post('hddId');
		$msj = "Se adicionó una nueva sede!";
		if ($idSede != '') {
			$msj = "Se actualizó la sede!";
		}
		if ($this->settings_model->saveSede()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Eliminar Sede
	 */
	public function delete_sede()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idSede = $this->input->post('identificador');
		$arrParam = array(
			"table" => "param_sedes",
			"primaryKey" => "id_sede",
			"id" => $idSede
		);
		if ($this->general_model->deleteRecord($arrParam)) 
		{
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Has eliminado la sede.');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }

    /**
	 * Listado Tipo Documento
	 */
	public function documentType()
	{
		$arrParam = array(
			"table" => "param_tipo_documento",
			"order" => "tipo_documento",
			"id" => "x"
		);
		$data['tipoDoc'] = $this->general_model->get_basic_search($arrParam);
		$data["view"] = 'document_type';
		$this->load->view("layout_calendar", $data);
	}

	/**
     * Formulario Tipo Documento
     */
    public function cargarModalDocumentType() 
	{
		header("Content-Type: text/plain; charset=utf-8");
		$data['information'] = "";
		$data["idTipo"] = $this->input->post("idTipo");
		if ($data["idTipo"] != 'x') {
			$arrParam = array(
				"table" => "param_tipo_documento",
				"order" => "id_tipo_documento",
				"column" => "id_tipo_documento",
				"id" => $data["idTipo"]
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		}
		$this->load->view("document_type_modal", $data);
    }

    /**
	 * Guardar Tipo Documento
	 */
	public function save_document_type()
	{
		header('Content-Type: application/json');
		$data = array();
		$idTipo = $this->input->post('hddId');
		$msj = "Se adicionó un nuevo tipo de documento!";
		if ($idTipo != '') {
			$msj = "Se actualizó el tipo de documento!";
		}
		if ($this->settings_model->saveDocumentType()) {
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
		} else {
			$data["result"] = "error";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
	}

	/**
	 * Eliminar Tipo Documento
	 */
	public function delete_document_type()
	{			
		header('Content-Type: application/json');
		$data = array();
		$idTipo = $this->input->post('identificador');
		$arrParam = array(
			"table" => "param_tipo_documento",
			"primaryKey" => "id_tipo_documento",
			"id" => $idTipo
		);
		if ($this->general_model->deleteRecord($arrParam)) 
		{
			$data["result"] = true;
			$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> Has eliminado el tipo de documento.');
		} else {
			$data["result"] = "error";
			$data["mensaje"] = "Error!!! Ask for help.";
			$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
		}
		echo json_encode($data);
    }
}