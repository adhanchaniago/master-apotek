<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPesanan extends CI_Model {

	protected $table = array( 
								'ak_data_pesanan', //0
								'ak_data_pesanan_detail', //1
								'ak_data_pbf', //2
								'ak_data_instansi', //3
								'ak_data_barang', //4
								'ak_data_pembelian', //5
								'ak_data_barang_detail', //6
							);

	public function GetData() {
		$res = $this->db->join($this->table[2],$this->table[2].'.id_pbf='.$this->table[0].'.id_pbf')
						->get($this->table[0]);
		return $res->result();
	}

	public function GetDataInstansi() {
		$res = $this->db->get($this->table[3]);
		return $res->row();
	}

	public function GetBarang() {
		$res = $this->db->where('deleted',false)
						->get($this->table[4]);
		return $res->result();
	}

	public function GetTotalSP() {
		$res = $this->db->get($this->table[0]);
		return $res->num_rows();
	}

	public function GetPBF() {
		$res = $this->db->where('deleted',false)
						->get($this->table[2]);
		return $res->result();
	}

	public function GetBarangDetail($id) {
		$res = $this->db->where('id_pesanan',$id)
						->join($this->table[1],$this->table[1].'.id_barang='.$this->table[4].'.id_barang')
						->order_by('id_pesanan_detail','DESC')
						->get($this->table[4]);
		return $res->result();
	}

	public function GetDataPesanan($id) {
		$res = $this->db->where($this->table[5].'.id_pesanan',$id)
						->get($this->table[5]);
		return $res->row();
	}

	public function GetSubTotal($id) {
		$res = $this->db->select_sum('subtotal_barang')
						->where('id_pesanan',$id)
						->get($this->table[1]);
		return $res->row('subtotal_barang');
	}

	public function GetSuratPesananDet($id) {
		$res = $this->db->join($this->table[2],$this->table[2].'.id_pbf='.$this->table[0].'.id_pbf')
						->where('id_pesanan',$id)
						->get($this->table[0]);
		return $res->row();
	}

	public function GetTotalFaktur() {
		$res = $this->db->get($this->table[5]);
		return $res->num_rows();
	}

	public function SaveData() {
		$a = $this->input->post('tgl_pesan');
		$b = $this->input->post('no_surat');
		$c = $this->input->post('pbf');
		$f = $this->input->post('barang');
		$d = $this->input->post('qty');
		$e = $this->input->post('diskon');
		$res = $this->db->where('deleted',false)
						->where('id_barang',$f)
						->get($this->table[4])->row();
		$cek = $this->db->where('id_pesanan',$b)
						->get($this->table[1])->num_rows();
		$no_surat = 'FK'.date('ymd').str_pad($this->GetTotalFaktur()+1, 5, '0', STR_PAD_LEFT);
		$data = array(
						'id_pesanan' => $b,
						'id_pbf' => $c,
						'id_user' => $this->session->userdata('kode'),
						'tanggal_pembuatan' => $a
					 );
		$data2 = array(
						'id_pembelian' => $no_surat,
						'id_pesanan' => $b
					  );
		if($cek==NULL) :
			$this->db->insert($this->table[0],$data);
			$this->db->insert($this->table[5],$data2);
		else :
			$this->db->where('id_pesanan',$b)
					 ->update($this->table[0],$data);
		endif;
			return $b;
	}

	public function AddBrg($id) {
		$f = $this->input->post('barang');
		$d = $this->input->post('qty');
		$res = $this->db->where('deleted',false)
						->where('id_barang',$f)
						->get($this->table[4])->row();
		$data1 = array(
										'id_pesanan' => $id,
										'id_barang' => $f,
										'qty_raw' => $d,
										'diskon' => $e,
										'subtotal_barang' => $res->harga_dasar*$d-($res->harga_dasar*$e/100)
  					  		);
		$this->db->insert($this->table[1],$data1);
		$id_pesanan_detail = $this->db->insert_id();
		$data3 = array(
					'id_barang' => $f,
					'id_pesanan_detail' => $id_pesanan_detail,
					'batch' => NULL,
					'kadaluarsa' => NULL,
					'stok_masuk' => 0
				  );
		$this->db->insert($this->table[6],$data3);
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