<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MMargin extends CI_Model {

	private $data_margin = "ak_data_margin";

	public function GetAll() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_margin);
		return $res->result();
	}

	public function GetSingle() {
		$id_margin = $this->input->post('id_margin');
		$res = $this->db->where('id_margin',$id_margin)->get($this->data_margin);
		return $res->row();
	}

	public function SaveData() {
		if($this->input->post('id_margin')==NULL) {
			$hitung_data = $this->db->get($this->data_margin)->num_rows();
			$id = "MAR".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_margin' => $id,
							'nm_margin' => $this->input->post('nm_margin'),
							'persentase_margin' => $this->input->post('persentase_margin')
						);
			$this->db->insert($this->data_margin,$data);
		} else {
			$data = array(
							'nm_margin' => $this->input->post('nm_margin'),
							'persentase_margin' => $this->input->post('persentase_margin')
						);
			$this->db->where('id_margin',$this->input->post('id_margin'))
					 ->update($this->data_margin,$data);
		}
		return TRUE;
	}

	public function GetOption() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_margin);
		return $res->result();
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_margin',$this->input->post('id_margin'))
				 ->update($this->data_margin,$data);
		return TRUE;
	}

}