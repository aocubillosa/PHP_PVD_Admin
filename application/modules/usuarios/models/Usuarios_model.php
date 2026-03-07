<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

	/**
	 * Listado Usuarios
	 */
	public function get_user($arrData) 
	{			
		$this->db->select();
		$this->db->join('param_role R', 'R.id_role = U.fk_id_user_role', 'INNER');
		$this->db->join('param_tipo_documento D', 'D.id_tipo_documento = U.fk_id_tipo_documento', 'INNER');
		$this->db->join('param_sedes S', 'S.id_sede = U.fk_id_sede', 'INNER');
		if (array_key_exists("idUser", $arrData)) {
			$this->db->where('U.id_user', $arrData["idUser"]);
		}
		$this->db->order_by("first_name, last_name", "ASC");
		$query = $this->db->get("usuarios U");
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else{
			return false;
		}
	}

	/**
     * Actualizar contraseña usuario
     */
    public function updatePassword()
	{
			$idUser = $this->input->post("hddId");
			$newPassword = $this->input->post("inputPassword");
			$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
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
}