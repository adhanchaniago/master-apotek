<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MMargin extends CI_Model {

	private $table = array(
													'ak_data_margin'
												);

	public function GetTotalData() {
		$res = $this->db->where('deleted',false)
										->get($this->table[0]);
		return $res->num_rows();
	}

	public function GetData($limit,$offset) {
		$res = $this->db->where('deleted',false)
										->limit($limit,$offset)
										->get($this->table[0]);
		return $res->result();
	}

	public function SaveData() {
		$a = $this->input->post('nm_margin');
		$b = $this->input->post('ketentuan');
		$tot = $this->db->get($this->table[0])->num_rows();
		$raw = $tot+1;
		$id = 'MAR'.$this->zerofill($raw);
		$data = array(
									'id_margin' => $id,
									'nm_margin' => $a,
									'harga_margin' => $b
								 );
		$this->db->insert($this->table[0],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_margin');
		$b = $this->input->post('ketentuan');
		$data = array(
									'nm_margin' => $a,
									'harga_margin' => $b
								 );
		$this->db->where('id_margin',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_margin',$id)
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