<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class MLevel extends CI_Model {

	private $data_level = "ak_data_level";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_level);
		return $res->result();
	}

}