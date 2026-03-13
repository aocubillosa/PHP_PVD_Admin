<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	/**
	 * Consulta Ingresos Visitantes
	 */
	public function permisos_visitantes()
	{
		$idUser = $this->session->userdata("id");
		$this->db->join('visitantes V', 'V.id_visitante = P.fk_id_visitante AND P.estado = 1', 'INNER');
		$this->db->where('V.state', 1);
		$visitantes = $this->db->count_all_results('permisos P');
		return $visitantes;
	}

	/**
	 * Consulta Elementos Inventario
	 */
	public function elementos_inventario()
	{
		$idUser = $this->session->userdata("id");
		$this->db->where('I.fk_id_user', $idUser);
		$inventario = $this->db->count_all_results('inventario I');
		return $inventario;
	}

	/**
	 * Consulta Ingresos
	 */
	public function get_ingresos()
	{
		$from = date('Y-m-d 00:00:00');
		$to = date('Y-m-d 23:59:59');
		$idUser = $this->session->userdata("id");
		$this->db->join('Visitantes V', 'V.id_visitante = I.fk_id_visitante', 'INNER');
		$this->db->where('V.state', 1);
		$this->db->where('I.fk_id_user', $idUser);
		$this->db->where('I.fecha_ingreso BETWEEN "'. $from .'" AND "'. $to .'"');
		$ingresos = $this->db->count_all_results('ingresos I');
		return $ingresos;
	}
}