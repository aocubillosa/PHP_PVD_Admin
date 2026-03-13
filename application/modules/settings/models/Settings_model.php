<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

	/**
	 * Verificar Usuario
	 */
	public function verifyUser($arrData)
	{
		$this->db->select();
		if (array_key_exists("idUser", $arrData)) {
			$this->db->where('id_user !=', $arrData["idUser"]);
		}
		$this->db->where($arrData["column"], $arrData["value"]);
		$query = $this->db->get("usuarios");
		if ($query->num_rows() > 0) {
			return true;
		} else { 
			return false; 
		}
	}

	/**
	 * Add/Edit Usuario
	 */
	public function saveUser()
	{
		$idUser = $this->input->post('hddId');
		$passwd = 'V1v3D1g1t4l';
		$passwd = md5($passwd);
		$data = array(
			'fk_id_user_role' => $this->input->post('idRole'),
			'fk_id_sede' => $this->input->post('idSede'),
			'fk_id_tipo_documento' => $this->input->post('tipoDocumento'),
			'numero_documento' => $this->input->post('numeroDocumento'),
			'first_name' => $this->input->post('firstName'),
			'last_name' => $this->input->post('lastName'),
			'log_user' => $this->input->post('user'),
			'email' => $this->input->post('email'),
			'movil' => $this->input->post('movilNumber')
		);
		if ($idUser == '') {
			$data['state'] = 1;
			$data['password'] = $passwd;
			$query = $this->db->insert('usuarios', $data);
		} else {
			$data['state'] = $this->input->post('state');
			$this->db->where('id_user', $idUser);
			$query = $this->db->update('usuarios', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Usuario con LDAP
	 */
	/*public function saveUser()
	{
		$idUser = $this->input->post('hddId');
		$data = array(
			'fk_id_user_role' => $this->input->post('idRole'),
			'fk_id_sede' => $this->input->post('idSede'),
			'fk_id_tipo_documento' => $this->input->post('tipoDocumento'),
			'numero_documento' => $this->input->post('numeroDocumento'),
			'first_name' => $this->input->post('firstName'),
			'last_name' => $this->input->post('lastName'),
			'log_user' => $this->input->post('user'),
			'email' => $this->input->post('email'),
			'movil' => $this->input->post('movilNumber')
		);
		if ($idUser == '') {
			$data['state'] = 1;
			$query = $this->db->insert('usuarios', $data);
		} else {
			$data['state'] = $this->input->post('state');
			$this->db->where('id_user', $idUser);
			$query = $this->db->update('usuarios', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}*/

	/**
	 * Restablecer Contraseña
	 */
	public function resetUserPassword($idUser)
	{
		$passwd = 'V1v3D1g1t4l';
		$passwd = md5($passwd);
		$data = array(
			'password' => $passwd,
		);
		$this->db->where('id_user', $idUser);
		$query = $this->db->update('usuarios', $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
    }

	/**
	 * Verificar Visitante
	 */
	public function verifyVisitor($arrData)
	{
		$this->db->select();
		if (array_key_exists("idVisitor", $arrData)) {
			$this->db->where('id_visitante !=', $arrData["idVisitor"]);
		}
		$this->db->where($arrData["column"], $arrData["value"]);
		$query = $this->db->get("visitantes");
		if ($query->num_rows() > 0) {
			return true;
		} else { 
			return false; 
		}
	}

	/**
	 * Add/Edit Visitante
	 */
	public function saveVisitor()
	{
		$idVisitor = $this->input->post('hddId');
		$data = array(
			'fk_id_tipo_documento' => $this->input->post('tipoDocumento'),
			'fk_id_ocupacion' => $this->input->post('ocupacion'),
			'numero_documento' => $this->input->post('numeroDocumento'),
			'nombres' => $this->input->post('firstName'),
			'apellidos' => $this->input->post('lastName'),
			'fecha_nacimiento' => $this->input->post('fecha_nacimiento'),
			'telefono' => $this->input->post('movilNumber')
		);
		if ($idVisitor == '') {
			$data['state'] = 1;
			$query = $this->db->insert('visitantes', $data);
		} else {
			$data['state'] = $this->input->post('state');
			$this->db->where('id_visitante', $idVisitor);
			$query = $this->db->update('visitantes', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Verificar Inventario
	 */
	public function verifyInventory($arrData)
	{
		$this->db->select();
		if (array_key_exists("idInventario", $arrData)) {
			$this->db->where('id_inventario !=', $arrData["idInventario"]);
		}
		$this->db->where($arrData["column"], $arrData["value"]);
		$query = $this->db->get("inventario");
		if ($query->num_rows() > 0) {
			return true;
		} else { 
			return false; 
		}
	}

	/**
	 * Add/Edit Inventario
	 */
	public function saveInventory()
	{
		$idUser = $this->session->userdata("id");
		$idInventario = $this->input->post('hddId');
		if (!empty($this->input->post('fecha_servicio'))) {
			$fecha_servicio = $this->input->post('fecha_servicio');
		} else {
			$fecha_servicio = NULL;
		}
		$data = array(
			'fk_id_elemento' => $this->input->post('elemento'),
			'descripcion' => $this->input->post('descripcion'),
			'fk_id_user' => $idUser,
			'fk_id_marca' => $this->input->post('marca'),
			'placa' => $this->input->post('placa'),
			'fecha_ingreso' => $this->input->post('fecha_ingreso'),
			'fecha_servicio' => $fecha_servicio,
			'valor' => $this->input->post('valor'),
			'fk_id_estado' => $this->input->post('estado')
		);
		if ($idInventario == '') {
			$query = $this->db->insert('inventario', $data);
		} else {
			$this->db->where('id_inventario', $idInventario);
			$query = $this->db->update('inventario', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Elemento
	 */
	public function saveElement()
	{
		$idElemento = $this->input->post('hddId');
		$data = array(
			'elemento' => $this->input->post('elemento')
		);
		if ($idElemento == '') {
			$query = $this->db->insert('param_elementos', $data);
		} else {
			$this->db->where('id_elemento', $idElemento);
			$query = $this->db->update('param_elementos', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Marca
	 */
	public function saveMark()
	{
		$idMarca = $this->input->post('hddId');
		$data = array(
			'marca' => $this->input->post('marca')
		);
		if ($idMarca == '') {
			$query = $this->db->insert('param_marcas', $data);
		} else {
			$this->db->where('id_marca', $idMarca);
			$query = $this->db->update('param_marcas', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Ocupacion
	 */
	public function saveOccupation()
	{
		$idOcupacion = $this->input->post('hddId');
		$data = array(
			'ocupacion' => $this->input->post('ocupacion')
		);
		if ($idOcupacion == '') {
			$query = $this->db->insert('param_ocupacion', $data);
		} else {
			$this->db->where('id_ocupacion', $idOcupacion);
			$query = $this->db->update('param_ocupacion', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Sede
	 */
	public function saveSede()
	{
		$idSede = $this->input->post('hddId');
		$data = array(
			'nombre_sede' => $this->input->post('sede')
		);
		if ($idSede == '') {
			$query = $this->db->insert('param_sedes', $data);
		} else {
			$this->db->where('id_sede', $idSede);
			$query = $this->db->update('param_sedes', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Add/Edit Tipo Documento
	 */
	public function saveDocumentType()
	{
		$idTipo = $this->input->post('hddId');
		$data = array(
			'tipo_documento' => $this->input->post('tipoDoc')
		);
		if ($idTipo == '') {
			$query = $this->db->insert('param_tipo_documento', $data);
		} else {
			$this->db->where('id_tipo_documento', $idTipo);
			$query = $this->db->update('param_tipo_documento', $data);
		}
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}