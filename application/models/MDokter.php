<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MDokter extends CI_Model {

	private $data_dokter = "ak_data_dokter";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_dokter);
		return $res->result();
	}

	public function GetSingle() {
		$id_dokter = $this->input->post('id_dokter');
		$res = $this->db->where('id_dokter',$id_dokter)->get($this->data_dokter);
		return $res->row();
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_dokter);
		return $res->result();
	}

	public function SaveData() {
		if($this->input->post('id_dokter')==NULL) {
			$hitung_data = $this->db->get($this->data_dokter)->num_rows();
			$id = "DKR".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_dokter' => $id,
							'nm_dokter' => $this->input->post('nm_dokter')
						);
			$this->db->insert($this->data_dokter,$data);
		} else {
			$data = array(
							'nm_dokter' => $this->input->post('nm_dokter')
						);
			$this->db->where('id_dokter',$this->input->post('id_dokter'))
					 ->update($this->data_dokter,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_dokter',$this->input->post('id_dokter'))
				 ->update($this->data_dokter,$data);
		return TRUE;
	}

}