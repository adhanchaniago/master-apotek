<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class MLevel extends CI_Model {

	private $data_level = "ak_data_level";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->where('id_level!=',1)
						->get($this->data_level);
		return $res->result();
	}

}