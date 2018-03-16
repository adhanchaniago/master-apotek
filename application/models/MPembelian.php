<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPembelian extends CI_Model {

	protected $table = array(
								'ak_data_instansi', //0
								'ak_data_barang', //1
								'ak_data_pembelian', //2
								'ak_data_pbf', //3
								'ak_data_pesanan_detail', //4
								'ak_data_pesanan', //5
								'ak_data_barang_detail', //6
								'ak_data_barang_stok', //7
								'ak_data_pembelian', //8
								'ak_data_pembelian_detail' //9
							);

	public function SaveItems($id) {
		$a = $this->input->post('id_barang');
		$b = $this->input->post('qty');
		$c = $this->input->post('batch');
		$d = $this->input->post('jatuh_tempo');
		$e = $this->input->post('kadaluwarsa');
		$f = $this->input->post('hrg_satuan');
		$g = $this->input->post('diskon');
		$stok = $this->db->select('id_barang')
										 ->select_sum('stok_masuk')
										 ->where('id_barang',$a)
										 ->group_by('id_barang')
										 ->get($this->table[7])->row('stok_masuk');
		$data = array(
						'id_pembelian' => $id,
						'id_barang' => $a,
						'qty_fix' => $b,
						'diskon' => $b,
						'subtotal_barang' => $f*$b,
						'checked' => TRUE
					 );
		$this->db->insert($this->table[9],$data);
		$id_pem = $this->db->insert_id();
		$sata = array(
						'id_barang' => $a,
						'id_pembelian_detail' => $id_pem,
						'batch' => $c,
						'kadaluarsa' => $e,
						'stok_masuk' => $b
					 );
		$this->db->insert($this->table[6],$sata);
		$pata = array(
						'id_barang' => $a,
						'stok_masuk' => $b
					 );
		$this->db->insert($this->table[7],$pata);
		$mata = array(
						'stok_tersedia' => $stok+$b
					 );
		$this->db->where('id_barang',$a)
						 ->update($this->table[1],$mata);
		return $id;
	}

	public function GetFaktur($id) {
		$res = $this->db->where('id_pembelian',$id)
										->get($this->table[8]);
		return $res->row();
	}

	public function GenFaktur() {
		$a = $this->input->post('tgl_beli');
		$b = $this->input->post('tgl_jth_tempo');
		$c = $this->input->post('no_faktur');
		$d = $this->input->post('pbf');
		$data = array(
									'id_pembelian' => $c,
									'tanggal_pembelian' => $a,
									'tanggal_jatuh_tempo' => $b,
									'id_pbf' => $d
								 );
		$this->db->insert($this->table[8],$data);
		return $c;
	}

	public function GetDetailFaktur($id) {
		$res = $this->db->where('id_pembelian',$id)
										->join($this->table[6],$this->table[6].'.id_pembelian_detail='.$this->table[9].'.id_pembelian_detail')
										->join($this->table[1],$this->table[1].'.id_barang='.$this->table[9].'.id_barang')
										->get($this->table[9]);
		return $res;
	}

	public function GetData() {
		$res = $this->db->get($this->table[8]);
		return $res->result();
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

	public function GetTotalFaktur() {
		$res = $this->db->get($this->table[2]);
		return $res->num_rows();
	}

	public function GetPBF() {
		$res = $this->db->where('deleted',false)
						->get($this->table[3]);
		return $res->result();
	}

	public function GetBarangDetail($id) {
		$res = $this->db->where($this->table[4].'.id_pesanan',$id)
						->join($this->table[1],$this->table[1].'.id_barang='.$this->table[4].'.id_barang')
						->join($this->table[6],$this->table[6].'.id_pesanan_detail='.$this->table[4].'.id_pesanan_detail')
						->join($this->table[2],$this->table[2].'.id_pesanan='.$this->table[4].'.id_pesanan')
						->get($this->table[4]);
		return $res->result();
	}

	public function GetSubTotal($id) {
		$res = $this->db->select_sum('subtotal_barang')
						->where('id_pesanan',$id)
						->get($this->table[4]);
		return $res->row('subtotal_barang');
	}

	public function GetSuratPesanan() {
		$res = $this->db->join(
							$this->table[4],
							$this->table[4].'.id_pbf='.$this->table[3].'.id_pbf')
						->get($this->table[3]);
		return $res->result();
	}

	public function GetFakturDet($id) {
		$res = $this->db->join($this->table[3],$this->table[3].'.id_pbf='.$this->table[5].'.id_pbf')
						->join($this->table[2],$this->table[2].'.id_pesanan='.$this->table[5].'.id_pesanan')
						->where($this->table[5].'.id_pesanan',$id)
						->get($this->table[5]);
		return $res->row();
	}

	public function SaveData() {
		$a = $this->input->post('id_barang');
		$b = $this->input->post('qty');
		$c = $this->input->post('batch');
		$d = $this->input->post('jatuh_tempo');
		$e = $this->input->post('kadaluwarsa');
		$f = $this->input->post('hrg_satuan');
		$g = $this->input->post('diskon');
		$h = $this->input->post('id_sp');
		$data = array(
						'id_pesanan' => $h,
						'tanggal_pembelian' => date('Y-m-d'),
						'tanggal_jatuh_tempo' => $d,
						'qty_datang' => $b,
						'subtotal' => $f*$b
					 );
		$this->insert($this->table[6],$data);
	}

	public function SimpanData() {
		$a = $this->input->post('id_barang');
		$b = $this->input->post('qty');
		$c = $this->input->post('batch');
		$d = $this->input->post('jatuh_tempo');
		$e = $this->input->post('kadaluwarsa');
		$f = $this->input->post('hrg_satuan');
		$g = $this->input->post('diskon');
		$h = $this->input->post('id_sp');
		$barang = $this->db->where('id_barang',$a)->get($this->table[1])->row();
		$detail = $this->db->where('id_pesanan',$h)
						   ->where('id_barang',$a)
						   ->get($this->table[4])
						   ->row();
		$stok = $barang->isi_satuan*$b;
		$req = $this->db->where('id_barang',$a)->get($this->table[7])->row('stok_tersedia');
		$no_surat = 'FK'.date('ymd').str_pad($this->GetTotalFaktur()+1, 5, '0', STR_PAD_LEFT);
		$cek = $this->db->where('id_pesanan',$h)
						->get($this->table[5])->num_rows();
		$data = array(
						'id_pesanan' => $h,
						'tanggal_pembelian' => date('Y-m-d'),
						'tanggal_jatuh_tempo' => $d,
						'qty_datang' => $b,
						'subtotal' => $f*$b
					 );
		$data2 = array(
						'id_barang' => $a,
						'id_pesanan_detail' => $detail->id_pesanan_detail,
						'batch' => $c,
						'kadaluarsa' => $e,
						'stok_masuk' => $stok
					  );
		$data3 = array(
						'stok_tersedia' => $req+$stok
					  );
		$data4 = array(
						'qty_fix' => $b,
						'diskon' => $g,
						'checked' => TRUE
					  );
		if($cek==NULL) :
			$this->db->insert($this->table[2],$data);
			$this->db->where('id_pesanan_detail',$detail->id_pesanan_detail)
					 ->where('id_barang',$a)
					 ->update('ak_data_barang_detail',$data2);
			$this->db->where('id_barang',$a)
					 ->update('ak_data_barang',$data3);
			$this->db->where('id_pesanan_detail',$detail->id_pesanan_detail)
					 ->update('ak_data_pesanan_detail',$data4);
		else :
			$this->db->where('id_pesanan',$h)
					 ->update('ak_data_pembelian',$data);
			$this->db->where('id_pesanan_detail',$detail->id_pesanan_detail)
					 ->where('id_barang',$a)
					 ->update('ak_data_barang_detail',$data2);
			$this->db->where('id_barang',$a)
					 ->update('ak_data_barang',$data3);
			$this->db->where('id_pesanan_detail',$detail->id_pesanan_detail)
					 ->update('ak_data_pesanan_detail',$data4);
		endif;
		return $h;
	}

	public function EditData($id) {
		$a = $this->input->post('nm_pbf');
		$b = $this->input->post('alamat');
		$c = $this->input->post('kota_pbf');
		$d = $this->input->post('kontak_kantor');
		$e = $this->input->post('kontak_orang');
		$data = array(
									'id_pbf' => $id,
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