<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MSetup extends CI_Model{

	protected $data_instansi = 'ak_data_instansi';


	public function GetAll(){
		$res = $this->db->get($this->data_instansi);
		return $res->result();
	}

	public function GetSingle() {
		$id_instansi = $this->input->post('id_instansi');
		$res = $this->db->where('id_instansi',$id_instansi)->get($this->data_instansi);
		return $res->row();
	}

	public function SaveData() {
			$data = array(
							'nm_instansi' => $this->input->post('nm_instansi'),
							'alamat_instansi' => $this->input->post('alamat_instansi'),
							'kontak_instansi' => $this->input->post('kontak_instansi'),
							'tuslah_racik' => $this->input->post('tuslah_racik'),
							'emblase_racik' => $this->input->post('emblase_racik')
						);
			$this->db->where('id_instansi',$this->input->post('id_instansi'))
					 ->update($this->data_instansi,$data);
		return TRUE;
	}

}
?>