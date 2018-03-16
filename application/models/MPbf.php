<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPbf extends CI_Model {

	private $table = array(
													'ak_data_pbf'
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
		$a = $this->input->post('nm_pbf');
		$b = $this->input->post('alamat');
		$c = $this->input->post('kota_pbf');
		$d = $this->input->post('kontak_kantor_pbf');
		$e = $this->input->post('kontak_orang_pbf');
		$tot = $this->db->where('deleted',false)->get($this->table[0])->num_rows();
		$raw = $tot+1;
		$id = 'PBF'.$this->zerofill($raw);
		$data = array(
									'id_pbf' => $id,
									'nm_pbf' => $a,
									'alamat_pbf' => $b,
									'kota_pbf' => $c,
									'kontak_kantor_pbf' => $d,
									'kontak_orang_pbf' => $e
								 );
		$this->db->insert($this->table[0],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_pbf');
		$b = $this->input->post('alamat');
		$c = $this->input->post('kota_pbf');
		$d = $this->input->post('kontak_kantor_pbf');
		$e = $this->input->post('kontak_orang_pbf');
		$tot = $this->db->where('deleted',false)->get($this->table[0])->num_rows();
		$data = array(
									'nm_pbf' => $a,
									'alamat_pbf' => $b,
									'kota_pbf' => $c,
									'kontak_kantor_pbf' => $d,
									'kontak_orang_pbf' => $e
								 );
		$this->db->where('id_pbf',$id)
						 ->update($this->table[0],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_pbf',$id)
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