<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	/**
	 * Consulta Ingresos Visitantes
	 */
	public function permisos_visitantes()
	{
		$this->db->join('visitantes V', 'V.id_visitante = P.fk_id_visitante AND P.estado = 1', 'INNER');
		$this->db->where('V.state', 1);
		$visitantes = $this->db->count_all_results('permisos P');
		return $visitantes;
	}
}