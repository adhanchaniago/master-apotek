<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLogin extends CI_Model {

	protected $data_user = 'ak_data_user';
	protected $data_level = 'ak_data_level';
	protected $data_instansi = 'ak_data_instansi';

	public function ceklog() {
		$u = $this->input->post('usr');
		$p = $this->input->post('pwd');
		$res = $this->db->where('login_user',$u)
										->where('deleted',false)
										->get($this->data_user)
										->row('pass_user');
		return $this->verifikasi($p,$res);
	}

	private function verifikasi($password,$hash) {
		return password_verify($password, $hash);
	}

	public function get_instansi() {
		return $this->db->get($this->data_instansi)->row();
	}

	public function datauser() {
		$u = $this->input->post('usr');
		$res = $this->db->join(
								$this->data_user,
								$this->data_level.'.id_level='.
								$this->data_user.'.level_user'
							)
		  				->where(array('login_user' => ''.$u.''))
		  				->get($this->data_level);
		return $res->row();
	}

	public function setLog() {
		$id_user = $this->session->userdata('kode');
		$data = array('login_terakhir' => date('Y-m-d'));
		$res = $this->db->where('id_user',$id_user)
						->update($this->data_user,$data);
	}

}