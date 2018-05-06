<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPenjualan extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_barang_stok_keluar = "ak_data_barang_stok_keluar";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_satuan = "ak_data_satuan";
	protected $data_penjualan = "ak_data_penjualan";
	protected $data_penjualan_bebas = "ak_data_penjualan_bebas";
	protected $data_penjualan_resep = "ak_data_penjualan_resep";
	protected $data_penjualan_bebas_detail = "ak_data_penjualan_bebas_detail";
	protected $data_penjualan_resep_detail = "ak_data_penjualan_resep_detail";
	protected $data_user = "ak_data_user";

	public function GetAll() {
		$res = $this->db->where($this->data_penjualan.'.deleted',FALSE)
						->where($this->data_penjualan.'.status',FALSE)
						->join(
								$this->data_user,
								$this->data_user.'.id_user='.
								$this->data_penjualan.'.id_user'
							  )
						->get($this->data_penjualan);
		return $res->result();
	}

	public function GetAllDetail($kode) {
		$res = $this->db->where($this->data_penjualan_bebas.'.deleted',FALSE)
						->where($this->data_penjualan_bebas.'.id_penjualan',$kode)
						->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_penjualan_bebas.'.id_barang'
							  )
						->join(
								$this->data_satuan,
								$this->data_satuan.'.id_satuan='.
								$this->data_barang.'.id_satuan'
							  )
						->join(
								$this->data_barang_stok_tersedia,
								$this->data_barang_stok_tersedia.'.id_barang='.
								$this->data_barang.'.id_barang'
							  )
						->get($this->data_penjualan_bebas);
		return $res->result();
	}

	public function GetAllDetailResep($kode) {
		$res = $this->db->where($this->data_penjualan_resep.'.deleted',FALSE)
						->where($this->data_penjualan_resep.'.id_penjualan',$kode)
						->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_penjualan_resep.'.id_barang'
							  )
						->join(
								$this->data_satuan,
								$this->data_satuan.'.id_satuan='.
								$this->data_barang.'.id_satuan'
							  )
						->join(
								$this->data_barang_stok_tersedia,
								$this->data_barang_stok_tersedia.'.id_barang='.
								$this->data_barang.'.id_barang'
							  )
						->get($this->data_penjualan_resep);
		return $res->result();
	}

	public function GetSingle($kode) {
		$res = $this->db->where('id_penjualan',$kode)
						->where('id_barang',$this->input->post('id_barang'))
						->get($this->data_penjualan_bebas);
		return $res->row();
	}

	public function find_duplicate($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_bebas);
		return $res->num_rows();
	}

	public function find_duplicate_resep($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_resep);
		return $res->num_rows();
	}

	public function cek_stok() {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('deleted',FALSE)
						->get($this->data_barang_stok_tersedia);
		return $res->num_rows();
	}

	public function get_nama_pasien_bebas($kode) {
		$res = $this->db->select('nm_pasien')
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_bebas);
		return $res->row();
	}

	public function get_nama_pasien_resep($kode) {
		$res = $this->db->where('id_penjualan',$kode)
						->get($this->data_penjualan_resep);
		return $res->row();
	}

	public function get_subtotal($kode) {
		return $this->db->select_sum('subtotal')
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_bebas)
						->row('subtotal');
	}

	public function get_subtotal_resep($kode) {
		return $this->db->select_sum('subtotal')
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_resep)
						->row('subtotal');
	}

	public function SaveData($kode) {
		if($kode==NULL) {
			if($this->input->post('id_penjualan_bebas')=="") {
				$hitung_data = $this->db->get($this->data_penjualan_bebas)->num_rows();
				$id = "B".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
				$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))
										 ->get($this->data_barang)->row('harga_dasar');
				$subtotal_kotor = $harga_dasar*$this->input->post('qty');
				$subtotal = $this->pembulatan($subtotal_kotor);
				$pembulatan = $subtotal_kotor-$subtotal;
				$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
								  ->where('fixed',TRUE)
								  ->where('deleted',FALSE)
								  ->order_by('updated','DESC')
								  ->get($this->data_barang_stok_tersedia)
								  ->row();
				$stok_tersedia = $stok_lama->stok_tersedia-$this->input->post('qty');
				$data_1 = array(
								'id_penjualan_bebas' => $id,
								'id_penjualan' => NULL,
								'id_barang' => $this->input->post('id_barang'),
								'id_user' => $this->session->userdata('kode'),
								'nm_pasien' => $this->input->post('nm_pasien'),
								'qty' => $this->input->post('qty'),
								'diskon' => $this->input->post('diskon'),
								'pembulatan' => $pembulatan,
								'subtotal' => $subtotal
							   );
				$this->db->insert($this->data_penjualan_bebas,$data_1);
				$id_barang_detail = $this->db->where('id_barang',$this->input->post('id_barang'))
											 ->where('stok_tersedia!=',0)
											 ->order_by('kadaluarsa','ASC')
											 ->get($this->data_barang_detail)
											 ->row('id_detail_barang');
				$data_2 = array(
								'id_penjualan' => NULL,
								'id_barang' => $this->input->post('id_barang'),
								'id_barang_detail' => $id_barang_detail,
								'qty' => $this->input->post('qty')
							   );
				$this->db->insert($this->data_penjualan_bebas_detail,$data_2);
				$data_3 = array(
								'id_barang' => $this->input->post('id_barang'),
								'id_penjualan' => $kode,
								'tanggal_keluar' => date('Y-m-d H:i:s'),
								'stok_keluar' => $this->input->post('qty'),
								'deleted' => 1
							);
				$this->db->insert($this->data_barang_stok_keluar,$data_3);
				$data_4 = array(
								'id_barang' => $this->input->post('id_barang'),
								'stok_tersedia' => $stok_tersedia,
								'updated' => date('Y-m-d H:i:s')
							);
				$this->db->insert($this->data_barang_stok_tersedia,$data_4);
				if($stok_lama!=NULL) :
					$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
					 	 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
				endif;
			}
		}
	}

	public function SaveDataResep($kode) {
		if($kode==NULL) {
			if($this->input->post('id_penjualan_resep')=="") {
				$hitung_data = $this->db->get($this->data_penjualan_resep)->num_rows();
				$id = "R".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
				$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))
										 ->get($this->data_barang)->row('harga_dasar');
				$subtotal_kotor = $harga_dasar*$this->input->post('qty');
				$subtotal = $this->pembulatan($subtotal_kotor);
				$pembulatan = abs($subtotal_kotor-$subtotal);
				$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
								  ->where('fixed',TRUE)
								  ->where('deleted',FALSE)
								  ->order_by('updated','DESC')
								  ->get($this->data_barang_stok_tersedia)
								  ->row();
				$stok_tersedia = $stok_lama->stok_tersedia-$this->input->post('qty');
				$id_barang_detail = $this->db->where('id_barang',$this->input->post('id_barang'))
											 ->where('stok_tersedia!=',0)
											 ->order_by('kadaluarsa','ASC')
											 ->get($this->data_barang_detail)
											 ->row('id_detail_barang');
				$data_1 = array(
									'id_penjualan_resep' => $id,
									'id_penjualan' => NULL,
									'id_barang' => $this->input->post('id_barang'),
									'id_user' => $this->session->userdata('kode'),
									'nm_pasien' => $this->input->post('nm_pasien'),
									'nm_dokter' => $this->input->post('nm_dokter'),
									'alamat_pasien' => $this->input->post('alamat_pasien'),
									'etiket' => $this->input->post('etiket'),
									'qty' => $this->input->post('qty'),
									'diskon' => $this->input->post('diskon'),
									'pembulatan' => $pembulatan,
									'subtotal' => $subtotal
							   );
				$this->db->insert($this->data_penjualan_resep,$data_1);
				$data_2 = array(
								'id_penjualan' => NULL,
								'id_barang' => $this->input->post('id_barang'),
								'id_barang_detail' => $id_barang_detail,
								'qty' => $this->input->post('qty')
							   );
				$this->db->insert($this->data_penjualan_resep_detail,$data_2);
				$data_3 = array(
								'id_barang' => $this->input->post('id_barang'),
								'id_penjualan' => $kode,
								'tanggal_keluar' => date('Y-m-d H:i:s'),
								'stok_keluar' => $this->input->post('qty'),
								'deleted' => 1
							);
				$this->db->insert($this->data_barang_stok_keluar,$data_3);
				$data_4 = array(
								'id_barang' => $this->input->post('id_barang'),
								'stok_tersedia' => $stok_tersedia,
								'updated' => date('Y-m-d H:i:s')
							);
				$this->db->insert($this->data_barang_stok_tersedia,$data_4);
				if($stok_lama!=NULL) :
					$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
					 	 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
				endif;
			}
		}
	}

	public function SaveAll($kode) {
		if($kode==NULL) :
			$hitung_data = $this->db->get($this->data_penjualan)->num_rows();
			$id = "TRB".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$grandtotal_kotor = $this->db->select_sum('subtotal')
										 ->where('id_penjualan',$kode)
										 ->get($this->data_penjualan_bebas)
										 ->row('subtotal');
			$grandtotal = $this->pembulatan($grandtotal_kotor);
			$pembulatan = abs($grandtotal_kotor-$grandtotal);
			$bayar = $this->input->post('bayar');
			$kembalian = abs($grandtotal-$bayar);
			$id_barang = $this->db->where('id_penjualan',$kode)
								  ->where('deleted',FALSE)
								  ->get($this->data_penjualan_bebas_detail)
								  ->result();
			foreach($id_barang as $d) {
				$this->db->set('stok_tersedia','stok_tersedia-'.$d->qty.'',FALSE)
						 ->where('id_detail_barang',$d->id_barang_detail)
						 ->update($this->data_barang_detail);
			}
			$data_1 = array(
								'id_penjualan' => $id,
								'id_user' => $this->session->userdata('kode'),
								'nm_pasien' => $this->input->post('nm_pasien'),
								'tanggal_penjualan' => date('Y-m-d H:i:s'),
								'grandtotal' => $grandtotal,
								'pembulatan' => $pembulatan,
								'bayar' => $bayar,
								'kembalian' => $kembalian,
								'status' => $this->input->post('kasbon')
						 	);
			$this->db->insert($this->data_penjualan,$data_1);
			$data_2 = array(
								'id_penjualan' => $id,
							);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_bebas,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_bebas_detail,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_barang_stok_tersedia,$data_2);
			$data_3 = array(
								'id_penjualan' => $id,
								'id_pembelian' => 0,
								'fixed' => 1
							);
			$this->db->where('id_penjualan',$id)
					 ->update($this->data_barang_stok_tersedia,$data_3);
			return $kembalian;
		endif;
	}

	public function SaveAllResep($kode) {
		if($kode==NULL) :
			$hitung_data = $this->db->get($this->data_penjualan)->num_rows();
			$id = "TRR".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$grandtotal_kotor = $this->db->select_sum('subtotal')
										 ->where('id_penjualan',$kode)
										 ->get($this->data_penjualan_resep)
										 ->row('subtotal');
			$grandtotal = $this->pembulatan($grandtotal_kotor);
			$pembulatan = abs($grandtotal_kotor-$grandtotal);
			$bayar = $this->input->post('bayar');
			$kembalian = abs($grandtotal-$bayar);
			$id_barang = $this->db->where('id_penjualan',$kode)
								  ->where('deleted',FALSE)
								  ->get($this->data_penjualan_resep_detail)
								  ->result();
			foreach($id_barang as $d) {
				$this->db->set('stok_tersedia','stok_tersedia-'.$d->qty.'',FALSE)
						 ->where('id_detail_barang',$d->id_barang_detail)
						 ->update($this->data_barang_detail);
			}
			$data_1 = array(
								'id_penjualan' => $id,
								'id_user' => $this->session->userdata('kode'),
								'nm_pasien' => $this->input->post('nm_pasien'),
								'tanggal_penjualan' => date('Y-m-d H:i:s'),
								'grandtotal' => $grandtotal,
								'pembulatan' => $pembulatan,
								'bayar' => $bayar,
								'kembalian' => $kembalian,
								'status' => $this->input->post('kasbon')
						 	);
			$this->db->insert($this->data_penjualan,$data_1);
			$data_2 = array(
								'id_penjualan' => $id,
							);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_resep,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_resep_detail,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_barang_stok_tersedia,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_barang_stok_keluar,$data_2);
			$data_3 = array(
								'id_penjualan' => $id,
								'id_pembelian' => 0,
								'fixed' => 1
							);
			$this->db->where('id_penjualan',$id)
					 ->update($this->data_barang_stok_tersedia,$data_3);
			return $kembalian;
		endif;
	}

	public function DelData($kode) {
		$data_1 = array (
							'deleted' => TRUE
						);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_bebas,$data_1);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_bebas_detail,$data_1);
	}

	private function pembulatan($uang) {
		$ratusan = substr($uang, -2);
		if($ratusan<50) 
			$akhir = $uang - $ratusan;
		else 
			$akhir = $uang + (100-$ratusan);
		return $akhir;
	}

}