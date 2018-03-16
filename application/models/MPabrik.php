<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPabrik extends CI_Model {

	private $table = array(
							'ak_data_pabrik'
						  );

	public function GetTotalData() {
		$res = $this->db->where('deleted',false)
						->get($this->table[0]);
		return $res->num_rows();
	}

	public function GetData() {
		$res = $this->db->where($this->table[0].'.deleted',false)
						->order_by('id_pabrik','ASC')
						->get($this->table[0]);
		return $res->result();
	}

	public function GetSingleData($id) {
		$res = $this->db->where($this->table[0].'.id_pabrik',$id)
						->get($this->table[0]);
		return $res->row();
	}

	public function SaveData() {
		$a = $this->input->post('nm_pabrik');
		$tot = $this->db->get($this->table[0])->num_rows();
		$raw = $tot+1;
		$id = 'PAB'.$this->zerofill($raw);	
		$data = array(
									'id_pabrik' => $id,
									'nm_pabrik' => $a
								 );
		$this->db->insert($this->table[0],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_pabrik');
		$data = array(
									'nm_pabrik' => $a
								 );
		$this->db->where('id_pabrik',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_pabrik',$id)
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