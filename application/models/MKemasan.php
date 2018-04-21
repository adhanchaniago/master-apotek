<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MKemasan extends CI_Model {

	private $data_kemasan = "ak_data_kemasan";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_kemasan);
		return $res->result();
	}

	public function GetSingle() {
		$id_kemasan = $this->input->post('id_kemasan');
		$res = $this->db->where('id_kemasan',$id_kemasan)->get($this->data_kemasan);
		return $res->row();
	}

	public function SaveData() {
		if($this->input->post('id_kemasan')==NULL) {
			$hitung_data = $this->db->get($this->data_kemasan)->num_rows();
			$id = "KEM".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_kemasan' => $id,
							'nm_kemasan' => $this->input->post('nm_kemasan')
						);
			$this->db->insert($this->data_kemasan,$data);
		} else {
			$data = array(
							'nm_kemasan' => $this->input->post('nm_kemasan')
						);
			$this->db->where('id_kemasan',$this->input->post('id_kemasan'))
					 ->update($this->data_kemasan,$data);
		}
		return TRUE;
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_kemasan);
		return $res->result();
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_kemasan',$this->input->post('id_kemasan'))
				 ->update($this->data_kemasan,$data);
		return TRUE;
	}

}