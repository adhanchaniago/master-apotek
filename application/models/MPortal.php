<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPortal extends CI_Model {

	protected $table = 'ak_data_user';

	public function ceklog($u,$p) {
		$x = $this->db->select('username,password')
				 	  ->from($this->table)
				 	  ->where(array('username' => ''.$u.''))
				 	  ->get()->row('password');
		return $this->verifikasi($p,$x);
	}

	public function datauser($u) {
		$x = $this->db->select('*')
					  ->from('ak_data_level')
					  ->join($this->table,'ak_data_level.id_level='.$this->table.'.level_user')
					  ->where(array('username' => ''.$u.''))
					  ->get();
		return $x;
	}

	public function setLog($k) {
		$data = array('login_terakhir' => date('Y-m-d'));
		$res = $this->db->where('id_user',$k)
										->update($this->table,$data);
	}

	private function verifikasi($password,$hash) {
		return password_verify($password, $hash);
	}

}