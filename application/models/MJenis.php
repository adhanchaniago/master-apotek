<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MJenis extends CI_Model {

	private $data_jenis_obat = "ak_data_jenis_obat";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_jenis_obat);
		return $res->result();
	}

	public function GetSingle() {
		$id_jenis = $this->input->post('id_jenis');
		$res = $this->db->where('id_jenis',$id_jenis)->get($this->data_jenis_obat);
		return $res->row();
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_jenis_obat);
		return $res->result();
	}

	public function SaveData() {
		if($this->input->post('id_jenis')==NULL) {
			$hitung_data = $this->db->get($this->data_jenis_obat)->num_rows();
			$id = "JEN".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_jenis' => $id,
							'nm_jenis' => $this->input->post('nm_jenis')
						);
			$this->db->insert($this->data_jenis_obat,$data);
		} else {
			$data = array(
							'nm_jenis' => $this->input->post('nm_jenis')
						);
			$this->db->where('id_jenis',$this->input->post('id_jenis'))
					 ->update($this->data_jenis_obat,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_jenis',$this->input->post('id_jenis'))
				 ->update($this->data_jenis_obat,$data);
		return TRUE;
	}

}