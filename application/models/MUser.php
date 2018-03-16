<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MUser extends CI_Model {

	private $table = array(
													'ak_data_level',
													'ak_data_user'
												);

	public function GetLevel() {
		return $this->db->where('deleted',false)
										->get($this->table[0])
										->result();
	}

	public function GetTotalData() {
		$res = $this->db->get($this->table[1]);
		return $res->num_rows();
	}

	public function GetData($limit,$offset) {
		$res = $this->db->where($this->table[1].'.deleted',false)
										->join(
														$this->table[0],
														$this->table[0].'.id_level='.
														$this->table[1].'.level_user'
													)
										->limit($limit,$offset)
										->get($this->table[1]);
		return $res->result();
	}

	public function SaveData() {
		$a = $this->input->post('nm_user');
		$b = $this->input->post('username');
		$c = $this->input->post('jenis_kelamin');
		$d = $this->input->post('tempat_lahir');
		$e = $this->input->post('tgl_lahir');
		$f = $this->input->post('kontak');
		$g = $this->input->post('alamat');
		$h = $this->input->post('level');
		$tot = $this->db->get($this->table[1])->num_rows();
		$raw = $tot+1;
		$id = 'USR'.$this->zerofill($raw);	
		$data = array(
									'id_user' => $id,
									'nm_user' => $a,
									'username' => $b,
									'password' => $this->hash_pwd($b),
									'jenis_kelamin' => $c,
									'tempat_lahir' => $d,
									'tanggal_lahir' => $e,
									'kontak_user' => $f,
									'alamat_user' => $g,
									'level_user' => $h
								 );
		$this->db->insert($this->table[1],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_user');
		$b = $this->input->post('username');
		$c = $this->input->post('jenis_kelamin');
		$d = $this->input->post('tempat_lahir');
		$e = $this->input->post('tgl_lahir');
		$f = $this->input->post('kontak');
		$g = $this->input->post('alamat');
		$h = $this->input->post('level');
		$data = array(
									'nm_user' => $a,
									'username' => $b,
									'jenis_kelamin' => $c,
									'tempat_lahir' => $d,
									'tanggal_lahir' => $e,
									'kontak_user' => $f,
									'alamat_user' => $g,
									'level_user' => $h
								 );
		$this->db->where('id_user',$id)
						 ->update($this->table[1],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_user',$id)
						 ->update($this->table[1],$data);
		return "success-Data telah dihapus!";
	}

	private function hash_pwd($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	private function zerofill ($num, $zerofill = 5) {
		return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
	}

}