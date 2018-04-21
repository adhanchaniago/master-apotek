<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPBF extends CI_Model {

	private $data_pbf = "ak_data_pbf";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_pbf);
		return $res->result();
	}

	public function GetSingle() {
		$id_pbf = $this->input->post('id_pbf');
		$res = $this->db->where('id_pbf',$id_pbf)->get($this->data_pbf);
		return $res->row();
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_pbf);
		return $res->result();
	}

	public function SaveData() {
		if($this->input->post('id_pbf')==NULL) {
			$hitung_data = $this->db->get($this->data_pbf)->num_rows();
			$id = "PBF".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_pbf' => $id,
							'nm_pbf' => $this->input->post('nm_pbf'),
							'alamat_pbf' => $this->input->post('alamat_pbf'),
							'kota_pbf' => $this->input->post('kota_pbf'),
							'kontak_kantor_pbf' => $this->input->post('kontak_kantor_pbf'),
							'kontak_person_pbf' => $this->input->post('kontak_person_pbf')
						);
			$this->db->insert($this->data_pbf,$data);
		} else {
			$data = array(
							'nm_pbf' => $this->input->post('nm_pbf'),
							'alamat_pbf' => $this->input->post('alamat_pbf'),
							'kota_pbf' => $this->input->post('kota_pbf'),
							'kontak_kantor_pbf' => $this->input->post('kontak_kantor_pbf'),
							'kontak_person_pbf' => $this->input->post('kontak_person_pbf')
						);
			$this->db->where('id_pbf',$this->input->post('id_pbf'))
					 ->update($this->data_pbf,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_pbf',$this->input->post('id_pbf'))
				 ->update($this->data_pbf,$data);
		return TRUE;
	}

}