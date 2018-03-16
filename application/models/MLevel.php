<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLevel extends CI_Model {

	protected $table = array(
														'ak_data_level'
													);

	public function GetTotalLevel() {
		return $this->db->where('deleted',false)
										->get($this->table[0])->num_rows();
	}

	public function GetLevel($limit,$offset) {
		$res = $this->db->where('deleted',false)
										->limit($limit,$offset)
										->order_by('id_level','ASC')
										->get($this->table[0]);
		return $res->result();
	}

	public function SaveData() {
		$a = $this->input->post('nm_level');
		$data = array(
									'nm_level' => $a
								 );
		$this->db->insert($this->table[0],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_level');
		$data = array(
									'nm_level' => $a
								 );
		$this->db->where('id_level',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_level',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah dihapus!";
	}

}