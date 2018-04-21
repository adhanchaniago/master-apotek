<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPabrik extends CI_Model {

	private $data_pabrik = "ak_data_pabrik";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_pabrik);
		return $res->result();
	}

	public function GetSingle() {
		$id_pabrik = $this->input->post('id_pabrik');
		$res = $this->db->where('id_pabrik',$id_pabrik)->get($this->data_pabrik);
		return $res->row();
	}

	public function SaveData() {
		if($this->input->post('id_pabrik')==NULL) {
			$hitung_data = $this->db->get($this->data_pabrik)->num_rows();
			$id = "PAB".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_pabrik' => $id,
							'nm_pabrik' => $this->input->post('nm_pabrik')
						);
			$this->db->insert($this->data_pabrik,$data);
		} else {
			$data = array(
							'nm_pabrik' => $this->input->post('nm_pabrik')
						);
			$this->db->where('id_pabrik',$this->input->post('id_pabrik'))
					 ->update($this->data_pabrik,$data);
		}
		return TRUE;
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_pabrik);
		return $res->result();
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_pabrik',$this->input->post('id_pabrik'))
				 ->update($this->data_pabrik,$data);
		return TRUE;
	}

}