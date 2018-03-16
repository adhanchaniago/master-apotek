<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MFarmasi extends CI_Model {

	protected $table = array( 'ak_data_jenis_obat',
														'ak_data_satuan',
														'ak_data_resep'
													);

	public function GetGolObat() {
		$res = $this->db->where('deleted',FALSE)
										->get($this->table[0]);
		return $res->result();
	}

	public function GetSatuan() {
		$res = $this->db->where('deleted',FALSE)
										->get($this->table[1]);
		return $res->result();
	}

}