<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MResep extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_barang_stok_keluar = "ak_data_barang_stok_keluar";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_satuan = "ak_data_satuan";
	protected $data_penjualan = "ak_data_penjualan";
	protected $data_penjualan_resep = "ak_data_penjualan_resep";
	protected $data_penjualan_resep_detail = "ak_data_penjualan_resep_detail";
	protected $data_user = "ak_data_user";
	protected $data_pbf = "ak_data_pbf";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_statistik = "ak_data_statistik";
	protected $data_penjualan_hutang = "ak_data_piutang_resep";
	protected $data_piutang_detail = "ak_data_piutang_resep_detail";

	public function bayar_piutang($kode) {
		$tersisa = $this->db->where('id_penjualan',$kode)->get($this->data_penjualan_hutang)->row('sisa_hutang');
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
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_hutang,array('deleted' => 1));
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_hutang,array('sisa_hutang' => $sisa));
			$statistik = array(
				$month => $cek_stat->row($month)+$grandtotal
			);
			$this->db->where('tahun',$year)
				 ->update($this->data_statistik,$statistik);
			return "Selamat, Hutang telah lunas!";
		} else {
			$this->db->where('id_penjualan',$kode)->update($this->data_penjualan_hutang,array('sisa_hutang' => $sisa));
			return "Data piutang telah terupdate!";
		}
	}

	public function get_piutang() {
		$res = $this->db->where($this->data_penjualan_hutang.'.deleted',FALSE)
						->join(
								$this->data_penjualan,
								$this->data_penjualan.'.id_penjualan='.
								$this->data_penjualan_hutang.'.id_penjualan'
							  )
						->join(
								$this->data_user,
								$this->data_user.'.id_user='.
								$this->data_penjualan_hutang.'.id_user'
							  )
						->get($this->data_penjualan_hutang);
		return $res->result();
	}

	public function get_nama_pasien_bebas($kode) {
		$res = $this->db->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->get($this->data_penjualan_resep);
		return $res->row();
	}

	public function GetAll() {
		$res = $this->db->where($this->data_penjualan.'.deleted',FALSE)
						//->where($this->data_penjualan.'.status',FALSE)
						->join(
								$this->data_user,
								$this->data_user.'.id_user='.
								$this->data_penjualan.'.id_user'
							  )
						->get($this->data_penjualan);
		return $res->result();
	}

	public function GetAllDetail($kode) {
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

	public function get_nama_pasien_resep($kode) {
		$res = $this->db->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->get($this->data_penjualan_resep);
		return $res->row();
	}

	public function GetSingle($kode) {
		$res = $this->db->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->where('id_barang',$this->input->post('id_barang'))
						->get($this->data_penjualan_resep);
		return $res->row();
	}

	public function find_duplicate($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->get($this->data_penjualan_resep);
		return $res->num_rows();
	}

	public function cek_stok() {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('deleted',FALSE)
						->get($this->data_barang_stok_tersedia);
		return $res->num_rows();
	}

	public function get_subtotal($kode) {
		$res = $this->db->select_sum('subtotal')
						->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->get($this->data_penjualan_resep)
						->row('subtotal');
		return "Rp. ".number_format($res,2,",",".");
	}

	public function SaveData($kode) {
		if($kode==NULL) {
			if($this->input->post('id_penjualan_resep')=="") {
				//Penomoran resep
				$hitung_data = $this->db->get($this->data_penjualan_resep)->num_rows();
				$id = "R".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
				$hitung_data_resep = $this->db->get($this->data_penjualan_resep)->num_rows();
				$no_resep = "NR".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
				//penghitungan harga satuan
				$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))
										 ->get($this->data_barang)->row('harga_dasar');
				$margin = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('margin');
				$decimal_marg = $margin/100;
				//Pentotalan harga
				$subtotal_kotor = $this->input->post('jasa_dokter')+$harga_dasar*$this->input->post('qty')*$decimal_marg+$harga_dasar*$this->input->post('qty');
				$subtotal = $this->pembulatan($subtotal_kotor);
				$pembulatan = $subtotal_kotor-$subtotal;
				//Update stok
				$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
								  ->where('fixed',TRUE)
								  ->where('deleted',FALSE)
								  ->order_by('updated','DESC')
								  ->get($this->data_barang_stok_tersedia)
								  ->row();
				$stok_tersedia = $stok_lama->stok_tersedia-$this->input->post('qty');
				//insert data penjualan
				$data_1 = array(
								'id_penjualan_resep' => $id,
								'id_penjualan' => NULL,
								'id_kwitansi' => $this->input->post('id_kwitansi'),
								'id_barang' => $this->input->post('id_barang'),
								'id_user' => $this->session->userdata('kode'),
								'nomor_resep' => $no_resep,
								'nm_pasien' => $this->input->post('nm_pasien'),
								'nm_dokter' => $this->input->post('nm_dokter'),
								'etiket' => $this->input->post('etiket'),
								'alamat_pasien' => $this->input->post('alamat_pasien'),
								'qty' => $this->input->post('qty'),
								'diskon' => $this->input->post('diskon'),
								'pembulatan' => $pembulatan,
								'jasa_dokter' => $this->input->post('jasa_dokter'),
								'subtotal' => $subtotal
							   );
				$this->db->insert($this->data_penjualan_resep,$data_1);
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
		} else {
			$harga_dasar = $this->db->where('id_barang',$this->input->post('id_barang'))
									 ->get($this->data_barang)->row('harga_dasar');
			$margin = $this->db->where('id_barang',$this->input->post('id_barang'))
								   ->get($this->data_barang)->row('margin');
			$arr_marg = explode(",",$margin);
			$subtotal_kotor = $harga_dasar*$this->input->post('qty')*array_sum($arr_marg)/100+$harga_dasar;
			$subtotal = $this->pembulatan($subtotal_kotor);
			$pembulatan = $subtotal_kotor-$subtotal;
			$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
							  ->where('fixed',TRUE)
							  ->where('deleted',FALSE)
							  ->order_by('updated','DESC')
							  ->get($this->data_barang_stok_tersedia)
							  ->row();
			$stok_tersedia = $this->input->post('qty');
			$data_1 = array(
							//'id_penjualan_resep' => $id,
							//'id_penjualan' => NULL,
							'id_kwitansi' => $this->input->post('id_kwitansi'),
							//'id_barang' => $this->input->post('id_barang'),
							'id_user' => $this->session->userdata('kode'),
							'nomor_resep' => $no_resep,
							'nm_pasien' => $this->input->post('nm_pasien'),
							'nm_dokter' => $this->input->post('nm_dokter'),
							'etiket' => $this->input->post('etiket'),
							'alamat_pasien' => $this->input->post('alamat_pasien'),
							'qty' => $this->input->post('qty'),
							'diskon' => $this->input->post('diskon'),
							'pembulatan' => $pembulatan,
							'subtotal' => $subtotal
						   );
			$this->db->where('id_penjualan',$kode)
					 ->where('id_barang',$this->input->post('id_barang'))
					 ->where('deleted',FALSE)
					 ->update($this->data_penjualan_resep,$data_1);
			$id_barang_detail = $this->db->where('id_barang',$this->input->post('id_barang'))
										 ->where('stok_tersedia!=',0)
										 ->order_by('kadaluarsa','ASC')
										 ->get($this->data_barang_detail)
										 ->row('id_detail_barang');
			$data_2 = array(
								//'id_penjualan' => NULL,
								//'id_barang' => $this->input->post('id_barang'),
								//'id_barang_detail' => $id_barang_detail,
								'qty' => $this->input->post('qty')
						   );
			$this->db->where('id_penjualan',$kode)
					 ->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_barang_detail',$id_barang_detail)
					 ->where('deleted',FALSE)
					 ->update($this->data_penjualan_resep_detail,$data_2);
			$data_3 = array(
							//'id_barang' => $this->input->post('id_barang'),
							//'id_penjualan' => $kode,
							'tanggal_keluar' => date('Y-m-d H:i:s'),
							'stok_keluar' => $this->input->post('qty'),
							'deleted' => 1
						);
			$this->db->where('id_penjualan',$kode)
					 ->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_opname',null)
					 ->update($this->data_barang_stok_keluar,$data_3);
			$data_4 = array(
								//'id_barang' => $this->input->post('id_barang'),
								'stok_tersedia' => $stok_tersedia,
								'updated' => date('Y-m-d H:i:s')
						   );
			$this->db->where('id_penjualan',$kode)
					 ->where('id_pembelian',null)
					 ->where('id_opname',null)
					 ->where('id_barang',$this->input->post('id_barang'))
					 ->update($this->data_barang_stok_tersedia,$data_4);
			// if($stok_lama!=NULL) :
			// 	$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
			// 	 	 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
			// endif;
		}
	}

	public function SaveAll($kode) {
		if($kode==NULL) :
			$hitung_data = $this->db->like('id_penjualan',"TRR","after")->get($this->data_penjualan)->num_rows();
			$id = "TRR".date('ymd').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$grandtotal_kotor = $this->db->select_sum('subtotal')
										 ->where('id_penjualan',$kode)
										 ->where('deleted',FALSE)
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
			$year = date('Y');
			$month = date('M');
			$cek_stat = $this->db->where('tahun',$year)->get($this->data_statistik);
			if($cek_stat->num_rows()==0) {
				$this->db->insert($this->data_statistik,array('tahun' => $year));
			}
			foreach($id_barang as $d) {
				$this->db->set('stok_tersedia','stok_tersedia-'.$d->qty.'',FALSE)
						 ->where('id_detail_barang',$d->id_barang_detail)
						 ->update($this->data_barang_detail);
			}
			if($this->input->post('kasbon')) :
				$data_1 = array(
									'id_penjualan' => $id,
									'id_special' => $this->input->post('id_kwitansi'),
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
				$statistik = array(
									$month => $cek_stat->row($month)+$grandtotal
								  );
				$this->db->where('tahun',$year)
						 ->update($this->data_statistik,$statistik);
			else : 
				$data_1 = array(
									'id_penjualan' => $id,
									'id_special' => $this->input->post('id_kwitansi'),
									'id_user' => $this->session->userdata('kode'),
									'tanggal_penjualan' => date('Y-m-d'),
									'waktu_penjualan' => date('H:i:s'),
									'nm_pasien' => $this->input->post('nm_pasien'),
									//'tanggal_pelunasan' => date('Y-m-d H:i:s'),
									'tanggal_penjualan' => date('Y-m-d H:i:s'),
									'grandtotal' => $grandtotal,
									'pembulatan' => $pembulatan,
									'bayar' => 0,
									'kembalian' => 0,
									'status' => $this->input->post('kasbon')
								 );
				$data_piutang = array(
					'id_penjualan' => $id,
					'id_user' => $this->session->userdata('kode'),
					'tanggal_hutang' => date('Y-m-d'),
					'sisa_hutang' => $grandtotal
				);
				$this->db->insert($this->data_penjualan_hutang,$data_piutang);
			endif;
			$this->db->insert($this->data_penjualan,$data_1);
			$data_2 = array(
								'id_penjualan' => $id,
							);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_resep,$data_2);
			$this->db->where('id_penjualan',$kode)
					 ->update($this->data_penjualan_resep_detail,$data_2);
			$data_3 = array(
							//'id_barang' => $this->input->post('id_barang'),
							'id_penjualan' => $id,
							//'tanggal_masuk' => date('Y-m-d'),
							//'stok_masuk' => $this->input->post('qty')
							'deleted' => 0
						);
			$this->db->where('id_penjualan',$kode)
					 ->where('id_opname',NULL)
					 ->update($this->data_barang_stok_keluar,$data_3);
			$data_4 = array(
								'id_penjualan' => $id,
								'fixed' => 1
							);
			$this->db->where('id_penjualan',$kode)
					 ->where('id_pembelian',NULL)
					 ->where('id_opname',NULL)
					 ->update($this->data_barang_stok_tersedia,$data_4);
			if($this->input->post('kasbon')) :
				return "Rp. ".number_format($kembalian,2,",",".");
			else :
				return "Data bon telah tersimpan";
			endif;
		endif;
	}

	public function DelData($kode) {
		$data_1 = array (
							'deleted' => TRUE
						);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_resep,$data_1);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_penjualan_resep_detail,$data_1);
		$this->db->where('id_penjualan',$kode)
				 ->where('id_barang',$this->input->post('id_barang'))
				 ->where('id_opname',null)
				 ->where('id_pembelian',null)
				 ->delete($this->data_barang_stok_tersedia);
		$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
							  ->where('fixed',TRUE)
							  ->where('deleted',TRUE)
							  ->order_by('updated','DESC')
							  ->get($this->data_barang_stok_tersedia)
							  ->row('id_stok_tersedia');
		$this->db->where('id_stok_tersedia',$stok_lama)
				 ->update($this->data_barang_stok_tersedia,array('deleted' => 0));
	}

	private function pembulatan($uang) {
		$ratusan = substr($uang, -2);
		if($ratusan<50) 
			$akhir = $uang - $ratusan;
		else 
			$akhir = $uang + (100-$ratusan);
		return $akhir;
	}

	public function get_bayar_hutang($kode) {
		$res = $this->db->select_sum('bayar')
						->where('id_penjualan',$kode)
						->where('deleted',FALSE)
						->get($this->data_piutang_detail)
						->row('bayar');
		return "Rp. ".number_format($res,2,",",".");
	}

	public function get_sisa_hutang($kode) {
		$res = $this->db->select_sum('sisa_hutang')
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_hutang)
						->row('sisa_hutang');
		return "Rp. ".number_format($res,2,",",".");
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