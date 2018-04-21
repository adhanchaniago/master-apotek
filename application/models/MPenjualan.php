<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPenjualan extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_barang_stok_keluar = "ak_data_barang_stok_keluar";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_satuan = "ak_data_satuan";
	protected $data_penjualan = "ak_data_penjualan";
	protected $data_penjualan_bebas = "ak_data_penjualan_bebas";
	protected $data_penjualan_bebas_detail = "ak_data_penjualan_bebas_detail";

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

	public function find_duplicate($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_bebas);
		return $res->num_rows();
	}

	public function cek_stok($kode) {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->where('id_penjualan',$kode)
						->get($this->data_penjualan_bebas);
		return $res->num_rows();
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
											 ->order_by('kadaluarsa','ASC')
											 ->get($this->data_barang_detail)
											 ->row('id_detail_barang');
				$data_2 = array(
								'id_penjualan_bebas' => NULL,
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

	public function SaveAll() {

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