<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entrances_model extends CI_Model {

	/**
	 * Consultar Accesos X Visitante
	 */
	public function get_access_by_visitor($arrData)
	{
		$this->db->select();
		$this->db->join('visitantes V', 'V.id_visitante = P.fk_id_visitante', 'INNER');
		$this->db->join('ingresos I', 'V.id_visitante = I.fk_id_visitante', 'LEFT');
		$this->db->where('P.fk_id_visitante', $arrData["idVisitante"]);
		$this->db->where('V.state', 1);
		$this->db->where('P.estado', 1);
		$query = $this->db->get("permisos P");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else{
			return false;
		}
	}

	/**
	 * Guardar Acceso
	 */
	public function saveAccess($data_qr)
	{
		$idVisitante = $this->input->post('hddId');
		$rutaImagen = $data_qr['rutaImagen'];
		$llave = $data_qr['llave'];
		$data = array(
			'fk_id_visitante' => $idVisitante,
			'qr_code_img_doc' => $rutaImagen,
			'qr_code_llave_doc' => $llave,
			'estado' => 1
		);
		$query = $this->db->insert('permisos', $data);
		$id_permiso = $this->db->insert_id();
		if ($query) {
			return $id_permiso;
		} else {
			return false;
		}
	}

	/**
	 * Consultar Accesos X Permisos
	 */
	public function get_access_by_permissions($arrData)
	{
		$this->db->select();
		$this->db->join('visitantes V', 'V.id_visitante = P.fk_id_visitante', 'INNER');
		$this->db->join('param_tipo_documento D', 'D.id_tipo_documento = V.fk_id_tipo_documento', 'INNER');
		$this->db->join('param_ocupacion O', 'O.id_ocupacion = V.fk_id_ocupacion', 'INNER');
		if (array_key_exists("idPermiso", $arrData)) {
			$this->db->where('P.id_permiso', $arrData["idPermiso"]);
		}
		if (array_key_exists("numeroDocumento", $arrData)) {
			$this->db->where('V.numero_documento', $arrData["numeroDocumento"]);
		}
		$query = $this->db->get("permisos P");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else{
			return false;
		}
	}

	/**
	 * Listado Permisos Visitantes
	 */
	public function get_infoVisitantes()
	{			
		$this->db->select();
		$this->db->join('visitantes V', 'V.id_visitante = P.fk_id_visitante', 'INNER');
		$this->db->join('param_tipo_documento D', 'D.id_tipo_documento = V.fk_id_tipo_documento', 'INNER');
		$this->db->join('param_ocupacion O', 'O.id_ocupacion = V.fk_id_ocupacion', 'INNER');
		$this->db->where('V.state', 1);
		$this->db->where('P.estado', 1);
		$query = $this->db->get("permisos P");
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else{
			return false;
		}
	}
	
	/**
	 * Eliminar Permiso
	 */
	public function delete_permissions()
	{
		$idPermiso = $this->input->post("identificador");
		$data = array(
			'estado' => 2
		);
		$this->db->where('id_permiso', $idPermiso);
		$query = $this->db->update('permisos', $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Listado Ingresos Visitantes
	 */
	public function get_infoIngresos($id_visitante)
	{
		$fecha = date('Y-m-d');
		$fecha_inicio = $fecha . ' 00:00:00';
		$fecha_final = $fecha . ' 23:59:59';
		$idUser = $this->session->userdata("id");
		$this->db->select();
		$this->db->join('visitantes V', 'V.id_visitante = I.fk_id_visitante', 'INNER');
		$this->db->where('V.id_visitante', $id_visitante);
		$this->db->where('V.state', 1);
		$this->db->where('I.fk_id_user', $idUser);
		$this->db->where("I.fecha_ingreso BETWEEN '$fecha_inicio' AND '$fecha_final'");
		$query = $this->db->get("ingresos I");
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}

	/**
	 * Registrar Ingreso
	 */
	public function check_in()
	{
		$fecha_ingreso = date('Y-m-d H:i:s');
		$idUser = $this->session->userdata("id");
		$idVisitante = $this->input->post('identificador');
		$data = array(
			'fk_id_user' => $idUser,
			'fk_id_visitante' => $idVisitante,
			'fecha_ingreso' => $fecha_ingreso
		);
		$query = $this->db->insert('ingresos', $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}