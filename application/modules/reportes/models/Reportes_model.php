<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reportes_model extends CI_Model {

	/**
	 * Consulta Ingresos
	 */
	public function get_ingresos($arrData)
	{
		$this->db->select();
		$this->db->join('visitantes V', 'V.id_visitante = I.fk_id_visitante', 'INNER');
		$this->db->join('param_tipo_documento D', 'D.id_tipo_documento = V.fk_id_tipo_documento', 'INNER');
		$this->db->join('param_ocupacion O', 'O.id_ocupacion = V.fk_id_ocupacion', 'INNER');
		$this->db->where('V.state', 1);
		$this->db->where('I.fk_id_user', $arrData["idUser"]);
		$this->db->where('I.fecha_ingreso BETWEEN "'. $arrData["from"] .'" AND "'. $arrData["to"] .'"');
		$this->db->order_by('I.id_ingreso ASC');
		$query = $this->db->get('ingresos I');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	/**
	 * Consulta Inventario
	 */
	public function get_inventario($arrData)
	{
		$this->db->select();
		$this->db->join('usuarios U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_elementos E', 'E.id_elemento = I.fk_id_elemento', 'INNER');
		$this->db->join('param_marcas M', 'M.id_marca = I.fk_id_marca', 'INNER');
		$this->db->join('param_estados S', 'S.id_estado = I.fk_id_estado', 'INNER');
		if (array_key_exists("idInventario", $arrData)) {
			$this->db->where('I.id_inventario', $arrData["idInventario"]);
		}
		if (array_key_exists("idUser", $arrData)) {
			$this->db->where('I.fk_id_user', $arrData["idUser"]);
		}
		$this->db->order_by("E.elemento, I.descripcion, I.placa", "ASC");
		$query = $this->db->get("inventario I");
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else{
			return false;
		}
	}
}