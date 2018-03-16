<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MAdmin extends CI_Model {

	protected $table = array( 
								'ak_data_instansi',
								'ak_data_barang',
								'ak_data_sp_detail',
								'ak_data_surat_pesanan',
								'ak_data_pbf',
								'ak_data_barang_stok',
								'ak_data_barang_detail'
							);

	public function GetData() {
		$res = $this->db->join($this->table[4],$this->table[4].'.id_pbf='.$this->table[3].'.id_pbf')
						->get($this->table[3]);
		return $res->result();
	}

	public function GetSuratPesananDet($id) {
		$res = $this->db->join($this->table[4],$this->table[4].'.id_pbf='.$this->table[3].'.id_pbf')
										->where('id_sp',$id)
										->get($this->table[3]);
		return $res->row();
	}

	public function GetInstansi() {
		$res = $this->db->get($this->table[0]);
		return $res->row();
	}

	public function GetBarang() {
		$res = $this->db->where('deleted',false)
						->get($this->table[1]);
		return $res->result();
	}

	public function GetBarangDetail($id) {
		$res = $this->db->where('id_sp',$id)
										->join($this->table[1],$this->table[1].'.id_barang='.$this->table[2].'.id_barang')
										->get($this->table[2]);
		return $res->result();
	}

	public function GetTotal($id) {
		$res = $this->db->select_sum('subtotal_barang')
										->where('id_sp',$id)
										->get($this->table[2]);
		return $res->row('subtotal_barang');
	}

	public function GetTotalSP() {
		$res = $this->db->get($this->table[3]);
		return $res->num_rows();
	}

	public function GetPBF() {
		$res = $this->db->where('deleted',false)
										->get($this->table[4]);
		return $res->result();
	}

	public function SaveData() {
		$a = $this->input->post('tgl_pesan');
		$b = $this->input->post('no_surat');
		$c = $this->input->post('pbf');
		$d = $this->input->post('qty');
		$e = $this->input->post('diskon');
		$f = $this->input->post('barang');
		$res = $this->db->where('deleted',false)
						->where('id_barang',$f)
						->get($this->table[1])->row();
		$cek = $this->db->where('id_sp',$b)
						->get($this->table[3])->num_rows();
		if($cek==NULL) :
			$data = array(
							'id_sp' => $b,
							'id_pbf' => $c
						 );
			$this->db->insert($this->table[3],$data);
			$data1 = array(
							'id_sp' => $b,
							'id_barang' => $f,
							'qty' => $d,
							'diskon' => $e,
							'subtotal_barang' => $res->harga_dasar*$d-($res->harga_dasar*$e/100)
	  					  );
			$this->db->insert($this->table[2],$data1);
		else :
			$data = array(
							'id_sp' => $b,
							'id_pbf' => $c
						 );
			$this->db->where('id_sp',$b)
					 ->update($this->table[3],$data);
			$data1 = array(
										'id_sp' => $b,
										'id_barang' => $f,
										'qty' => $d,
										'diskon' => $e,
										'subtotal_barang' => $res->harga_dasar*$d-($res->harga_dasar*$e/100)
									 );
			$this->db->insert($this->table[2],$data1);
		endif;
			return $b;
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_sp',$id)
						 ->update($this->table[3],$data);
		return "success-Data telah dihapus!";
	}

	private function hash_pwd($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	private function zerofill ($num, $zerofill = 5) {
		return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
	}

}