<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reportes_model extends CI_Model {

	/**
	 * Consulta Turnos
	 */
	public function get_ingresos($arrData)
	{
		$this->db->select();
		$this->db->join('ingresos I', 'I.fk_id_visitante = V.id_visitante', 'INNER');
		$this->db->join('param_tipo_documento D', 'D.id_tipo_documento = V.fk_id_tipo_documento', 'INNER');
		$this->db->join('param_ocupacion O', 'O.id_ocupacion = V.fk_id_ocupacion', 'INNER');
		$this->db->where('V.state', 1);
		$this->db->where('I.fecha_ingreso BETWEEN "'. $arrData["from"] .'" AND "'. $arrData["to"] .'"');
		$this->db->order_by('I.id_ingreso ASC');
		$query = $this->db->get('visitantes V');
		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
}