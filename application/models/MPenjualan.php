<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPenjualan extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_barang_stok_keluar = "ak_data_barang_stok_keluar";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_satuan = "ak_data_satuan";
	protected $data_jenis = "ak_data_jenis_obat";
	protected $data_penjualan = "ak_data_penjualan";
	protected $data_penjualan_bebas = "ak_data_penjualan_bebas";
	protected $data_penjualan_bebas_detail = "ak_data_penjualan_bebas_detail";
	protected $data_user = "ak_data_user";
	protected $data_pbf = "ak_data_pbf";
	protected $data_statistik = "ak_data_statistik";
	protected $data_piutang_bebas = "ak_data_piutang_bebas";
	protected $data_piutang_detail = "ak_data_piutang_bebas_detail";
	protected $data_retur_penjualan = "ak_data_retur_penjualan";
	// Model Retur Penjualan
	public function retur_transaksi($kode) {
		$hitung_data = $this->db->get($this->data_retur_penjualan)->num_rows();
		$id = "RET".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		$array_data_retur = array(
			'id_retur' => $id,
			'id_penjualan' => $kode,
			'id_user' => $this->session->userdata('kode'),
			'tanggal_retur' => date('Y-m-d'),
			'keterangan' => $this->input->post('keterangan')
		);
		$this->db->insert($this->data_retur_penjualan,$array_data_retur);
		$this->db->where('id_penjualan',$kode)->update($this->data_penjualan,array('returned' => true));
		$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_bebas,array('returned' => true));
		$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_bebas_detail,array('returned' => true));
		$list_barang = $this->db->where('id_penjualan',$kode)->where('deleted',false)->get($this->data_penjualan_bebas)->result();
		foreach($list_barang as $list) {
			$stok_baru = $this->db->where('id_barang',$list->id_barang)->where('fixed',TRUE)->where('deleted',TRUE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row();

			$this->db->where('id_stok_tersedia',$stok_baru->id_stok_tersedia)->update($this->data_barang_stok_tersedia,array('deleted' => 0));
			$this->db->where('id_barang',$list->id_barang)->update($this->data_barang_detail,array('stok_tersedia' => $stok_baru->stok_tersedia));
			$stok_lama = $this->db->where('id_barang',$list->id_barang)->where('id_penjualan',$kode)->where('id_pembelian',null)->where('id_opname',null)->where('fixed',TRUE)->where('deleted',FALSE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row('id_stok_tersedia');
			$this->db->where('id_stok_tersedia',$stok_lama)->delete($this->data_barang_stok_tersedia);
			$array_data_stok_keluar = array(
				'id_barang' => $list->id_barang,
				'id_retur' => $id,
				'stok_keluar' => $list->qty
			);
			$this->db->insert($this->data_barang_stok_keluar,$array_data_stok_keluar);
		};
		return "ok di cek";
	}
	
	public function get_detail_retur($kode) {
		$res = $this->db->where($this->data_penjualan_bebas_detail.'.deleted',FALSE)->where($this->data_penjualan_bebas_detail.'.id_penjualan',$kode)->join($this->data_barang,$this->data_barang.'.id_barang='.$this->data_penjualan_bebas_detail.'.id_barang')->join($this->data_satuan,$this->data_satuan.'.id_satuan='.$this->data_barang.'.id_satuan')->join($this->data_barang_detail,$this->data_barang_detail.'.id_detail_barang='.$this->data_penjualan_bebas_detail.'.id_barang_detail')->get($this->data_penjualan_bebas_detail);
		return $res->result();
	}

	public function get_all_retur() {
		$res = $this->db->where('returned',false)->where($this->data_penjualan.'.deleted',FALSE)->like($this->data_penjualan.'.id_penjualan','TRB','after')->join($this->data_user,$this->data_user.'.id_user='.$this->data_penjualan.'.id_user')->get($this->data_penjualan);
		return $res->result();
	}
	// Model Piutang
	public function bayar_piutang($kode) {
		$tersisa = $this->db->where('id_penjualan',$kode)->get($this->data_piutang_bebas)->row('sisa_hutang');
		$sisa = $tersisa-$this->input->post('bayar');
		$data_detail = array(
			'id_penjualan' => $kode,
			'id_user' => $this->session->userdata('kode'),
			'tanggal_bayar' => date('Y-m-d'),
			'bayar' => $this->input->post('bayar'),
			'sisa' => $sisa
		);
		$this->db->insert($this->data_piutang_detail,$data_detail);
		if($sisa==0) {
			$year = date('Y');
			$month = date('M');
			$cek_stat = $this->db->where('tahun',$year)->get($this->data_statistik);
			$grandtotal = $this->db->where('id_penjualan',$kode)->get($this->data_penjualan)->row('grandtotal');
			if($cek_stat->num_rows()==0) {
				$this->db->insert($this->data_statistik,array('tahun' => $year));
			}
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan,array('status' => 1));
			$this->db->where('id_penjualan',$kode)->update($this->data_piutang_bebas,array('deleted' => 1));
			$this->db->where('id_penjualan',$kode)->update($this->data_piutang_bebas,array('sisa_hutang' => $sisa));
			$statistik = array(
				$month => $cek_stat->row($month)+$grandtotal
			);
			$this->db->where('tahun',$year)
				 ->update($this->data_statistik,$statistik);
			return "Selamat, Hutang telah lunas!";
		} else {
			$this->db->where('id_penjualan',$kode)->update($this->data_piutang_bebas,array('sisa_hutang' => $sisa));
			return "Data piutang telah terupdate!";
		}
	}

	public function get_bayar_hutang($kode) {
		$res = $this->db->select_sum('bayar')->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_piutang_detail)->row('bayar');
		return "Rp. ".number_format($res,2,",",".");
	}

	public function get_sisa_hutang($kode) {
		$res = $this->db->select_sum('sisa_hutang')->where('id_penjualan',$kode)->get($this->data_piutang_bebas)->row('sisa_hutang');
		return "Rp. ".number_format($res,2,",",".");
	}

	public function get_detail_piutang($kode) {
		$res = $this->db->where($this->data_penjualan_bebas.'.deleted',FALSE)->where($this->data_penjualan_bebas.'.id_penjualan',$kode)->where($this->data_barang_stok_tersedia.'.fixed',TRUE)->where($this->data_barang_stok_tersedia.'.deleted',FALSE)->join($this->data_barang,$this->data_barang.'.id_barang='.$this->data_penjualan_bebas.'.id_barang')->join($this->data_satuan,$this->data_satuan.'.id_satuan='.$this->data_barang.'.id_satuan')->join($this->data_barang_stok_tersedia,$this->data_barang_stok_tersedia.'.id_barang='.$this->data_penjualan_bebas.'.id_barang')->get($this->data_penjualan_bebas);
		return $res->result();
	}

	public function get_piutang() {
		$res = $this->db->where($this->data_piutang_bebas.'.deleted',FALSE)->join($this->data_penjualan,$this->data_penjualan.'.id_penjualan='.$this->data_piutang_bebas.'.id_penjualan')->join($this->data_user,$this->data_user.'.id_user='.$this->data_piutang_bebas.'.id_user')->get($this->data_piutang_bebas);
		return $res->result();
	}
	// Model Transaksi
	public function pembayaran($kode) {
		if($kode==NULL) :
			$hitung_data = $this->db->like('id_penjualan',"TRB","after")->get($this->data_penjualan)->num_rows();
			$id = "TRB".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$grandtotal_kotor = $this->db->select_sum('subtotal')->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan_bebas)->row('subtotal');
			$grandtotal = $this->pembulatan($grandtotal_kotor);
			$pembulatan = abs($grandtotal_kotor-$grandtotal);
			$bayar = $this->input->post('bayar');
			$kembalian = abs($grandtotal-$bayar);
			$id_barang = $this->db->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan_bebas_detail)->result();
			$year = date('Y');
			$month = date('M');
			$cek_stat = $this->db->where('tahun',$year)->get($this->data_statistik);
			if($cek_stat->num_rows()==0) {
				$this->db->insert($this->data_statistik,array('tahun' => $year));
			}
			foreach($id_barang as $d) {
				$this->db->set('stok_tersedia','stok_tersedia-'.$d->qty.'',FALSE)->where('id_detail_barang',$d->id_barang_detail)->update($this->data_barang_detail);
			}
			if(!$this->input->post('kasbon')) :
				$array_data_penjualan = array(
					'id_penjualan' => $id,
					'id_special' => $this->input->post('id_nota'),
					'id_user' => $this->session->userdata('kode'),
					'tanggal_penjualan' => date('Y-m-d'),
					'waktu_penjualan' => date('H:i:s'),
					'nm_pasien' => $this->input->post('nm_pasien'),
					'tanggal_pelunasan' => date('Y-m-d H:i:s'),
					'tanggal_penjualan' => date('Y-m-d H:i:s'),
					'grandtotal' => $grandtotal,
					'pembulatan' => $pembulatan,
					'bayar' => $bayar,
					'kembalian' => $kembalian,
					'status' => 1
				);
				$this->db->insert($this->data_penjualan,$array_data_penjualan);
				$statistik = array(
					$month => $cek_stat->row($month)+$grandtotal
				);
				$this->db->where('tahun',$year)->update($this->data_statistik,$statistik);
			else :
				$array_data_penjualan = array(
					'id_penjualan' => $id,
					'id_special' => $this->input->post('id_nota'),
					'id_user' => $this->session->userdata('kode'),
					'tanggal_penjualan' => date('Y-m-d'),
					'waktu_penjualan' => date('H:i:s'),
					'nm_pasien' => $this->input->post('nm_pasien'),
					'tanggal_penjualan' => date('Y-m-d H:i:s'),
					'grandtotal' => $grandtotal,
					'pembulatan' => $pembulatan,
					'bayar' => 0,
					'kembalian' => 0,
					'status' => $this->input->post('kasbon')
				);
				$this->db->insert($this->data_penjualan,$array_data_penjualan);
				$array_data_piutang = array(
					'id_penjualan' => $id,
					'id_user' => $this->session->userdata('kode'),
					'tanggal_hutang' => date('Y-m-d'),
					'sisa_hutang' => $grandtotal
				);
				$this->db->insert($this->data_piutang_bebas,$array_data_piutang);
			endif;
			$array_data_kode_unik = array(
				'id_penjualan' => $id,
			);
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_bebas,$array_data_kode_unik);
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_bebas_detail,$array_data_kode_unik);
			$array_data_stok_keluar = array(
				'id_penjualan' => $id,
				'deleted' => 0
			);
			$this->db->where('id_penjualan',$kode)->where('id_opname',NULL)->update($this->data_barang_stok_keluar,$array_data_stok_keluar);
			$array_data_stok_tersedia = array(
				'id_penjualan' => $id,
				'fixed' => 1
			);
			$this->db->where('id_penjualan',$kode)->where('id_pembelian',NULL)->where('id_opname',NULL)->update($this->data_barang_stok_tersedia,$array_data_stok_tersedia);
			if(!$this->input->post('kasbon')) :
				return "Kembaliannya Rp. ".number_format($kembalian,2,",",".");
			else :
				return "Data bon telah tersimpan";
			endif;
		endif;
	}

	private function pembulatan($uang) {
		$ratusan = substr($uang, -2);
		if($ratusan<50) 
			$akhir = $uang - $ratusan;
		else 
			$akhir = $uang + (100-$ratusan);
		return $akhir;
	}

	public function delete_data($kode) {
		$array_data = array (
			'deleted' => TRUE
		);
		$this->db->where('id_penjualan',$kode)->where('id_barang',$this->input->post('id_barang'))->update($this->data_penjualan_bebas,$array_data);
		$this->db->where('id_penjualan',$kode)->where('id_barang',$this->input->post('id_barang'))->update($this->data_penjualan_bebas_detail,$array_data);
		if($kode!="") :
			$is_empty = $this->db->where('id_penjualan',$kode)->where('deleted',false)->get($this->data_penjualan_bebas_detail)->num_rows();
			if($is_empty==0) :
				$this->db->where('id_penjualan',$kode)->update($this->data_penjualan,$data_1);
			endif;
		endif;
		$stok_baru = $this->db->where('id_barang',$this->input->post('id_barang'))->where('fixed',TRUE)->where('deleted',TRUE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row('id_stok_tersedia');
		$this->db->where('id_stok_tersedia',$stok_baru)->update($this->data_barang_stok_tersedia,array('deleted' => 0));
		$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))->where('id_penjualan',$kode)->where('id_pembelian',null)->where('id_opname',null)->where('fixed',TRUE)->where('deleted',FALSE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row('id_stok_tersedia');
		$this->db->where('id_stok_tersedia',$stok_lama)->delete($this->data_barang_stok_tersedia);
		if($kode!="") {
			$grandtotal_kotor = $this->db->select_sum('subtotal')->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan_bebas)->row('subtotal');
			$grandtotal = $this->pembulatan($grandtotal_kotor);
			$pembulatan = abs($grandtotal_kotor-$grandtotal);
			$data_array_grand_total = array(
				'grandtotal' => $grandtotal,
				'pembulatan' => $pembulatan
			);
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan,$data_2);
			$harga_barang = $this->db->where('id_penjualan',$kode)->where('deleted',true)->where('id_barang',$this->input->post('id_barang'))->get($this->data_penjualan_bebas)->row('subtotal'); 
			$year = date('Y');
			$month = date('M');
			$cek_stat = $this->db->where('tahun',$year)->get($this->data_statistik);
			$statistik = array(
				$month => $cek_stat->row($month)-$harga_barang
			);
			$this->db->where('tahun',$year)->update($this->data_statistik,$statistik);
		}
	}

	public function get_data_penjualan($kode) {
		$res = $this->db->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan);
		return $res->row();
	}

	public function get_data_barang($kode) {
		$res = $this->db->where('id_penjualan',$kode)->where('deleted',FALSE)->where('id_barang',$this->input->post('id_barang'))->get($this->data_penjualan_bebas);
		return $res->row();
	}

	public function get_subtotal($kode) {
		$res = $this->db->select_sum('subtotal')->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan_bebas)->row('subtotal');
		return "Rp. ".number_format($res,2,",",".");
	}

	public function edit_data($kode) {
		$this->delete_data($kode);
		$this->save_data($kode);
	}

	public function save_data($kode) {
		// perhitungan data baru
		if($kode==NULL) {
			// hitung data untuk generate id yang unik
			$hitung_data = $this->db->get($this->data_penjualan_bebas)->num_rows();
			$id = "B".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			// pengambilan data untuk di jadikan variabel
			$id_barang_detail = $this->db->where('id_barang',$this->input->post('id_barang'))->where('stok_tersedia>=',0)->order_by('kadaluarsa','ASC')->get($this->data_barang_detail)->row('id_detail_barang');
			$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('harga_dasar'); // pengambilan harga dasar
			$margin = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('margin'); // pengambilan margin
			$decimal_marg = $margin/100; // pendesimalan margin
			// perhitungan harga dengan rumus
			$subtotal_kotor = $harga_dasar*$this->input->post('qty')*$decimal_marg+$harga_dasar*$this->input->post('qty');
			// pembulatan harga
			$subtotal = $this->pembulatan($subtotal_kotor);
			$pembulatan = abs($subtotal_kotor-$subtotal);
			// perhitungan stok
			$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))->where('fixed',TRUE)->where('deleted',FALSE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row();
			$stok_tersedia = $stok_lama->stok_tersedia-$this->input->post('qty');
			// memasukkan data ke database berdasarkan variable
			$array_data_penjualan_bebas = array(
				'id_penjualan_bebas' => $id,
				'id_nota' => $this->input->post('id_nota'),
				'id_penjualan' => NULL,
				'id_barang' => $this->input->post('id_barang'),
				'id_user' => $this->session->userdata('kode'),
				'qty' => $this->input->post('qty'),
				'diskon' => $this->input->post('diskon'),
				'pembulatan' => $pembulatan,
				'subtotal' => $subtotal
			);
			$this->db->insert($this->data_penjualan_bebas,$array_data_penjualan_bebas);
			$array_data_detail_penjualan_bebas = array(
				'id_penjualan' => NULL,
				'id_barang' => $this->input->post('id_barang'),
				'id_barang_detail' => $id_barang_detail,
				'qty' => $this->input->post('qty')
			);
			$this->db->insert($this->data_penjualan_bebas_detail,$array_data_detail_penjualan_bebas);
			$array_data_stok_keluar = array(
				'id_barang' => $this->input->post('id_barang'),
				'id_penjualan' => $kode,
				'tanggal_keluar' => date('Y-m-d H:i:s'),
				'stok_keluar' => $this->input->post('qty'),
				'deleted' => 1
			);
			$this->db->insert($this->data_barang_stok_keluar,$array_data_stok_keluar);
			$array_data_barang_detail = array(
				'stok_tersedia' => $stok_tersedia
			);
			$this->db->where('id_detail_barang',$id_barang_detail)->update($this->data_barang_detail,$array_data_barang_detail);
			$array_data_stok_tersedia = array(
				'id_barang' => $this->input->post('id_barang'),
				'stok_tersedia' => $stok_tersedia,
				'updated' => date('Y-m-d H:i:s'),
				'fixed' => true
			);
			$this->db->insert($this->data_barang_stok_tersedia,$array_data_stok_tersedia);
			// pembaharuan jumlah stok
			if($stok_lama!=NULL) :
				$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
			endif;
		// perhitungan data lama
		} else {
			// pengambilan data untuk dijadikan variable
			$id_barang_detail = $this->db->where('id_barang',$this->input->post('id_barang'))->where('stok_tersedia!=',0)->order_by('kadaluarsa','ASC')->get($this->data_barang_detail)->row('id_detail_barang');
			$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('harga_dasar'); // ambil harga dasar
			$margin = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('margin'); // ambil data margin
			$margin = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('margin'); // pengambilan margin
			$decimal_marg = $margin/100; // pendesimalan margin
			// perhitungan harga dengan rumus
			$subtotal_kotor = $harga_dasar*$this->input->post('qty')*$decimal_marg+$harga_dasar*$this->input->post('qty');
			// pembulatan harga
			$subtotal = $this->pembulatan($subtotal_kotor);
			$pembulatan = $subtotal_kotor-$subtotal;
			// perhitungan stok
			$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))->where('fixed',TRUE)->where('deleted',FALSE)->order_by('updated','DESC')->get($this->data_barang_stok_tersedia)->row();
			$stok_tersedia = $this->input->post('qty');
			// update data ke database berdasarkan variable
			$array_data_detail_penjualan_bebas = array(
				'id_user' => $this->session->userdata('kode'),
				'qty' => $this->input->post('qty'),
				'diskon' => $this->input->post('diskon'),
				'pembulatan' => $pembulatan,
				'subtotal' => $subtotal
			);
			$this->db->where('id_penjualan',$kode)->where('id_barang',$this->input->post('id_barang'))->where('deleted',FALSE)->update($this->data_penjualan_bebas,$array_data_detail_penjualan_bebas);
			$array_data_detail_penjualan_bebas = array(
				'qty' => $this->input->post('qty')
			);
			$this->db->where('id_penjualan',$kode)->where('id_barang',$this->input->post('id_barang'))->where('id_barang_detail',$id_barang_detail)->where('deleted',FALSE)->update($this->data_penjualan_bebas_detail,$array_data_detail_penjualan_bebas);
			$array_data_stok_keluar = array(
				'tanggal_keluar' => date('Y-m-d H:i:s'),
				'stok_keluar' => $this->input->post('qty'),
				'deleted' => false
			);
			$this->db->where('id_penjualan',$kode)->where('id_barang',$this->input->post('id_barang'))->where('id_opname',null)->update($this->data_barang_stok_keluar,$array_data_stok_keluar);
			$array_data_stok_tersedia = array(
				'stok_tersedia' => $stok_tersedia,
				'updated' => date('Y-m-d H:i:s')
			);
			$this->db->where('id_penjualan',$kode)->where('id_pembelian',null)->where('id_opname',null)->where('id_barang',$this->input->post('id_barang'))->where('deleted',false)->update($this->data_barang_stok_tersedia,$array_data_stok_tersedia);
		}
	}

	public function find_duplicate($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))->where('id_penjualan',$kode)->where('deleted',FALSE)->get($this->data_penjualan_bebas);
		return $res->num_rows();
	}

	public function cek_stok() {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))->where('deleted',FALSE)->get($this->data_barang_stok_tersedia);
		return $res->num_rows();
	}

	public function get_shopping_list($kode) {
		$res = $this->db->where($this->data_penjualan_bebas.'.deleted',FALSE)->where($this->data_penjualan_bebas.'.id_penjualan',$kode)->where($this->data_barang_stok_tersedia.'.deleted',FALSE)->join($this->data_barang,$this->data_barang.'.id_barang='.$this->data_penjualan_bebas.'.id_barang')->join($this->data_satuan,$this->data_satuan.'.id_satuan='.$this->data_barang.'.id_satuan')->join($this->data_barang_stok_tersedia,$this->data_barang_stok_tersedia.'.id_barang='.$this->data_barang.'.id_barang')->get($this->data_penjualan_bebas);
		return $res->result();
	}

	public function get_all() {
		$res = $this->db->where($this->data_penjualan.'.deleted',FALSE)->like($this->data_penjualan.'.id_penjualan','TRB','after')->join($this->data_user,$this->data_user.'.id_user='.$this->data_penjualan.'.id_user')->get($this->data_penjualan);
		return $res->result();
	}

	public function get_statistik() {
		$res = $this->db->where('tahun',date('Y'))
						->get($this->data_statistik);
		return $res->result();
	}

	public function total_penjualan() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->data_penjualan);
		return $res->num_rows();
	}

	public function total_piutang() {
		$res = $this->db->where('deleted',FALSE)
						->where('status',FALSE)
						->get($this->data_penjualan);
		return $res->num_rows();
	}

	public function total_pendapatan() {
		$res = $this->db->select_sum('grandtotal')
						->where('deleted',FALSE)
						->where('status',TRUE)
						->where('tanggal_penjualan>=',date('Y-01-01'))
						->where('tanggal_penjualan<=',date('Y-12-31'))
						->get($this->data_penjualan);
		return $res->row('grandtotal');
	}

	public function retur_data($kode) {
		$data_1 = array (
							'deleted' => TRUE
						);
		$retur = array (
			'deleted' => TRUE,
			'returned' => TRUE
		);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_bebas,$retur);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_bebas_detail,$retur);
		if($kode!="") :
			$is_empty = $this->db->where('id_penjualan',$kode)
								 ->where('deleted',false)
								 ->get($this->data_penjualan_bebas_detail)->num_rows();
			if($is_empty==0) :
				$this->db->where('id_penjualan',$kode)
						 ->update($this->data_penjualan,$data_1);
			endif;
		endif;
		$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
							  ->where('fixed',TRUE)
							  ->where('deleted',TRUE)
							  ->order_by('updated','DESC')
							  ->get($this->data_barang_stok_tersedia)
							  ->row('id_stok_tersedia');
		$this->db->where('id_stok_tersedia',$stok_lama)
				 ->update($this->data_barang_stok_tersedia,array('deleted' => 0));
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->where('id_opname',null)
				 ->where('id_pembelian',null)
				 ->update($this->data_barang_stok_tersedia,array('deleted' => 1));
		if($kode!="") {
			$grandtotal_kotor = $this->db->select_sum('subtotal')
										 ->where('id_penjualan',$kode)
										 ->where('deleted',FALSE)
										 ->get($this->data_penjualan_bebas)
										 ->row('subtotal');
			$grandtotal = $this->pembulatan($grandtotal_kotor);
			$pembulatan = abs($grandtotal_kotor-$grandtotal);
			$data_2 = array(
							// 'id_penjualan' => $id,
							// 'id_user' => $this->session->userdata('kode'),
							// 'tanggal_penjualan' => date('Y-m-d'),
							// 'waktu_penjualan' => date('H:i:s'),
							// 'nm_pasien' => $this->input->post('nm_pasien'),
							// 'tanggal_pelunasan' => date('Y-m-d H:i:s'),
							// 'tanggal_penjualan' => date('Y-m-d H:i:s'),
							'grandtotal' => $grandtotal,
							'pembulatan' => $pembulatan,
							// 'bayar' => $bayar,
							// 'kembalian' => $kembalian,
							// 'status' => 1
						);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan,$data_2);
			$harga_barang = $this->db->where('id_penjualan',$kode)
									 ->where('deleted',true)
									 ->where('id_barang',$this->input->post('id_barang'))
									 ->get($this->data_penjualan_bebas)->row('subtotal'); 
			$year = date('Y');
			$month = date('M');
			$cek_stat = $this->db->where('tahun',$year)->get($this->data_statistik);
			$statistik = array(
				$month => $cek_stat->row($month)-$harga_barang
			);
			$this->db->where('tahun',$year)
			 		 ->update($this->data_statistik,$statistik);
		}
	}

	public function get_data_stok(){
		$res = $this->db->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->where($this->data_barang_stok_tersedia.'.fixed',TRUE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_barang_stok_tersedia.'.id_barang'
							)
						->get($this->data_barang_stok_tersedia);
		return $res->result();
	}

	public function get_data_barang_expired(){
		$res = $this->db->where($this->data_barang_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_barang_detail.'.id_barang'
							)
						->join(
								$this->data_pembelian,
								$this->data_pembelian.'.id_pembelian='.
								$this->data_barang_detail.'.id_pembelian'
							)
						->get($this->data_barang_detail);
		return $res->result();
	}

	public function get_data_jatuh_tempo(){
		$res = $this->db->where($this->data_pembelian.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pembelian.'.id_pbf'
							)
						->get($this->data_pembelian);
		return $res->result();
	}

}