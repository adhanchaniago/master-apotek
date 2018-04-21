<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MSatuan extends CI_Model {

	private $data_satuan = "ak_data_satuan";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_satuan);
		return $res->result();
	}

	public function GetSingle() {
		$id_satuan = $this->input->post('id_satuan');
		$res = $this->db->where('id_satuan',$id_satuan)->get($this->data_satuan);
		return $res->row();
	}

	public function SaveData() {
		if($this->input->post('id_satuan')==NULL) {
			$hitung_data = $this->db->get($this->data_satuan)->num_rows();
			$id = "SAT".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_satuan' => $id,
							'nm_satuan' => $this->input->post('nm_satuan')
						);
			$this->db->insert($this->data_satuan,$data);
		} else {
			$data = array(
							'nm_satuan' => $this->input->post('nm_satuan')
						);
			$this->db->where('id_satuan',$this->input->post('id_satuan'))
					 ->update($this->data_satuan,$data);
		}
		return TRUE;
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_satuan);
		return $res->result();
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_satuan',$this->input->post('id_satuan'))
				 ->update($this->data_satuan,$data);
		return TRUE;
	}

}