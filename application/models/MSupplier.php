<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MSupplier extends CI_Model {

	private $data_supplier = "ak_data_supplier";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_supplier);
		return $res->result();
	}

	public function GetSingle() {
		$id_supplier = $this->input->post('id');
		$res = $this->db->where('id_supplier',$id_supplier)->get($this->data_supplier);
		return $res->row();
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_supplier);
		return $res->result();
	}

	public function SaveData() {
		if($this->input->post('id_supplier')==NULL) {
			$hitung_data = $this->db->get($this->data_supplier)->num_rows();
			$id = "PBF".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_supplier' => $id,
							'nm_supplier' => $this->input->post('nm_supplier'),
							'alamat_supplier' => $this->input->post('alamat_supplier'),
							'kota_supplier' => $this->input->post('kota_supplier'),
							'kontak_kantor_supplier' => $this->input->post('kontak_kantor_supplier'),
							'kontak_person_supplier' => $this->input->post('kontak_person_supplier')
						);
			$this->db->insert($this->data_supplier,$data);
		} else {
			$data = array(
							'nm_supplier' => $this->input->post('nm_supplier'),
							'alamat_supplier' => $this->input->post('alamat_supplier'),
							'kota_supplier' => $this->input->post('kota_supplier'),
							'kontak_kantor_supplier' => $this->input->post('kontak_kantor_supplier'),
							'kontak_person_supplier' => $this->input->post('kontak_person_supplier')
						);
			$this->db->where('id_supplier',$this->input->post('id_supplier'))
					 ->update($this->data_supplier,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_supplier',$this->input->post('id_supplier'))
				 ->update($this->data_supplier,$data);
		return TRUE;
	}

}