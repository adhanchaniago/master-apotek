<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Statistik extends CI_Model {

	protected $statistik = "ak_data_statistik";
	
	public function get_statistik() {
		$res = $this->db->where('tahun',date('Y'))
						->get($this->statistik);
		return $res->result();
	}

}