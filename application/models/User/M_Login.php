<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Login extends CI_Model {

	protected $user = 'ak_data_user';
	protected $level = 'ak_data_level';
	protected $instansi = 'ak_data_instansi';

	public function ceklog() {
		$u = $this->input->post('usr');
		$p = $this->input->post('pwd');

		$res = $this->db->where(
			'login_user',$u
		)->where(
			'deleted',false
		)->get($this->user)->row('pass_user');

		return $this->verifikasi($p,$res);
	}

	private function verifikasi($password,$hash) {
		return password_verify($password, $hash);
	}

	public function get_instansi() {
		return $this->db->get($this->instansi)->row();
	}

	public function datauser() {
		$u = $this->input->post('usr');
		
		$res = $this->db->join(
			$this->user,
			$this->level.'.id_level='.
			$this->user.'.id_level'
		)->where(
			'login_user',$u
		)->get($this->level);
		
		return $res->row();
	}

	public function setLog() {
		$id_user = $this->session->userdata('kode');
		
		$data = array(
			'login_terakhir' => date('Y-m-d')
		);
		
		$res = $this->db->where(
			'id_user',$id_user
		)->update(
			$this->user,$data
		);
	}

}