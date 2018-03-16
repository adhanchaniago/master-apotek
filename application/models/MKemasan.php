<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Mkemasan extends CI_Model {

	private $table = array(
													'ak_data_kemasan'
												);

	public function GetTotalData() {
		$res = $this->db->where('deleted',false)
										->get($this->table[0]);
		return $res->num_rows();
	}

	public function GetData($limit,$offset) {
		$res = $this->db->where($this->table[0].'.deleted',false)
										->limit($limit,$offset)
										->get($this->table[0]);
		return $res->result();
	}

	public function SaveData() {
		$a = $this->input->post('nm_kemasan');
		$tot = $this->db->get($this->table[0])->num_rows();
		$raw = $tot+1;
		$id = 'KEM'.$this->zerofill($raw);	
		$data = array(
									'id_kemasan' => $id,
									'nm_kemasan' => $a
								 );
		$this->db->insert($this->table[0],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_kemasan');
		$data = array(
									'nm_kemasan' => $a
								 );
		$this->db->where('id_kemasan',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_kemasan',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah dihapus!";
	}

	private function hash_pwd($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	private function zerofill ($num, $zerofill = 5) {
		return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
	}

}