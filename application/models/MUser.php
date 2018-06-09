<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class MUser extends CI_Model {

	private $data_user = "ak_data_user";
	private $data_level = "ak_data_level";

	public function GetAll() {
		if($this->session->userdata('level')=="Master" OR $this->session->userdata('level')=="Pemilik") :
			$res = $this->db->where($this->data_user.'.deleted',FALSE)
							->where($this->data_level.'.id_level!=',1)
							->join($this->data_level,
									$this->data_user.'.level_user='.
									$this->data_level.'.id_level'
								)
							->get($this->data_user);
		else :
			$res = $this->db->where($this->data_user.'.deleted',FALSE)
							->where($this->data_level.'.id_level!=',1)
							->where($this->data_user.'.id_user',$this->session->userdata('kode'))
							->join($this->data_level,
									$this->data_user.'.level_user='.
									$this->data_level.'.id_level'
								)
							->get($this->data_user);
		endif;
		return $res->result();
	}

	public function get_single() {
		$id_user = $this->input->post('id_user');
		$res = $this->db->where('id_user',$id_user)
						->get($this->data_user);
		return $res->row();
	}

	public function GetUser($kode) {
		$res = $this->db->where('id_user',$kode)
						->join($this->data_level,
								$this->data_user.'.level_user='.
								$this->data_level.'.id_level'
							)
						->get($this->data_user);
		return $res->result();
	}

	public function SaveData() {
		$hitung_data = $this->db->get($this->data_user)->num_rows();
		$id = "USR".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		if($this->input->post('id_user')=="") {
			$data = array(
							'id_user' => $id,
							'nm_user' => $this->input->post('nm_user'),
							'login_user' => $this->input->post('login_user'),
							'pass_user' => $this->hash_pwd($this->input->post('pass_user')),
							'level_user' => $this->input->post('level_user')
						);
			$this->db->insert($this->data_user,$data);
			return TRUE;
		} else {
			$this->EditData();
		}
	}

	public function EditData() {
		$id_user = $this->input->post('id_user');
		if($this->input->post('pass_user')=="") {
			$data = array(
							'nm_user' => $this->input->post('nm_user'),
							//'level_user' => $this->input->post('level_user')
						);
			$this->db->where('id_user',$id_user)
					 ->update($this->data_user,$data);
		} else {
			$data = array(
							'nm_user' => $this->input->post('nm_user'),
							'login_user' => $this->input->post('login_user'),
							'pass_user' => $this->hash_pwd($this->input->post('pass_user')),
							//'level_user' => $this->input->post('level_user')
						);
			$this->db->where('id_user',$id_user)
					 ->update($this->data_user,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$id_user = $this->input->post('id_user');
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_user',$id_user)
				 ->update($this->data_user,$data);
		return TRUE;
	}

	public function verif_user() {
		$u = $this->session->userdata('kode');
		$p = $this->input->post('verif_pass_user');
		$res = $this->db->where('id_user',$u)
						->where('deleted',false)
						->get($this->data_user)
						->row('pass_user');
		return $this->verifikasi($p,$res);
	}

	public function unlock() {
		$p = $this->input->post('verif_pass_user');
		$res = $this->db->where('id_user','USR00001')
						->where('deleted',false)
						->get($this->data_user)
						->row('pass_user');
		return $this->verifikasi($p,$res);
	}

	private function verifikasi($password,$hash) {
		return password_verify($password, $hash);
	}

	private function hash_pwd($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

}