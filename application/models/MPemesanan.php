<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPemesanan extends CI_Model {

	protected $data_pbf = "ak_data_pbf";
	protected $data_user = 'ak_data_user';
	protected $data_barang = "ak_data_barang";
	protected $data_kemasan = "ak_data_kemasan";
	protected $data_pesanan = "ak_data_pesanan";
	protected $data_pesanan_detail = "ak_data_pesanan_detail";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_pembelian_detail = "ak_data_pembelian_detail";
	protected $data_pabrik = "ak_data_pabrik";
	protected $data_stok_masuk = "ak_data_barang_stok_masuk";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_barang_detail = "ak_data_barang_detail";

	public function GetAll() {
		$res = $this->db->where($this->data_pesanan.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pesanan.'.id_pbf'
							)
						->join(
								$this->data_user,
								$this->data_user.'.id_user='.
								$this->data_pesanan.'.id_user'
							)
						->get($this->data_pesanan);
		return $res->result();
	}

	public function GetAllDetail($kode) {
		$res = $this->db->where('id_pesanan',$kode)
						->where($this->data_pesanan_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pesanan_detail.'.id_barang'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->get($this->data_pesanan_detail);
		return $res->result();
	}

	public function GetSingle($kode) {
		$id_barang = $this->input->post('id_barang');
		$res = $this->db->where($this->data_pesanan_detail.'.id_barang',$id_barang)
						->where($this->data_pesanan_detail.'.id_pesanan',$kode)
						->where($this->data_pesanan_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pesanan_detail.'.id_barang'
							  )
						->get($this->data_pesanan_detail);
		return $res->row();
	}

	public function GetDetail($kode) {
		$res = $this->db->select('id_sp,id_pbf,status')->where('id_pesanan',$kode)
						->get($this->data_pesanan);
		return $res->row();
	}

	public function checkout($kode) {
		$hitung_data = $this->db->get($this->data_pembelian)->num_rows();
		$id = "FK".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		$re = $this->db->where('id_pesanan',$kode)
					   ->get($this->data_pesanan)
					   ->row();
		$pembelian = array(
							'id_pembelian' => $id,
							'id_pesanan' => $kode,
							'id_pbf' => $re->id_pbf,
							'id_user' => $this->session->userdata('kode'),
							'tanggal_pembelian' => date('Y-m-d'),
							'tanggal_jatuh_tempo' => null,
							'subtotal' => 0
						);
		$this->db->insert($this->data_pembelian,$pembelian);
		$this->db->where('id_pesanan',$kode)
				 ->update($this->data_pesanan,array('status' => 1));
		$res = $this->db->where('id_pesanan',$kode)
						->where('deleted',FALSE)
						->get($this->data_pesanan_detail)
						->result();
		foreach($res as $r) {
			$isi_satuan = $this->db->where('id_barang',$r->id_barang)
							   ->where('deleted',false)
							   ->get($this->data_barang)->row('isi_satuan');
			$detail = array(
								'id_pembelian' => $id,
								'id_barang' => $r->id_barang,
								'qty' => $r->qty/$isi_satuan
						   );
			$this->db->insert($this->data_pembelian_detail,$detail);
			$stok_masuk = array(
									'id_barang' => $r->id_barang,
									'id_pembelian' => $id,
									'stok_masuk' => $r->qty
							   );
			$this->db->insert($this->data_stok_masuk,$stok_masuk);
			$detail_barang = array(
									'id_barang' => $r->id_barang,
									'id_pembelian' => $id,
									'stok_tersedia' => $r->qty,
							      );
			$this->db->insert($this->data_barang_detail,$detail_barang);
			// $stok_lama = $this->db->where('id_barang',$r->id_barang)
			// 					  ->where('fixed',TRUE)
			// 					  ->where('deleted',FALSE)
			// 					  ->order_by('updated','DESC')
			// 					  ->get($this->data_barang_stok_tersedia)
			// 					  ->row();
			// if($stok_lama!=NULL) :
			// 	$stok_tersedia = $stok_lama->stok_tersedia+$r->qty;
			// else : 
			// 	$stok_tersedia = $r->qty;
			// endif;
			// $data_4 = array(
			// 					'id_pembelian' => $id,
			// 					'id_barang' => $r->id_barang,
			// 					'stok_tersedia' => $stok_tersedia,
			// 					'updated' => date('Y-m-d H:i:s')
			// 				);
			// $this->db->insert($this->data_barang_stok_tersedia,$data_4);
			// if($stok_lama!=NULL) :
			// 	$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
			// 		 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
			// endif;
		}
		return $id;
	}

	public function SaveData($kode) {
		$find = $this->db->where('id_barang',$this->input->post('id_barang'))
						 ->where('id_pesanan',$kode)
						 ->where('deleted',FALSE)
						 ->get($this->data_pesanan_detail);
		$isi_satuan = $this->db->where('id_barang',$this->input->post('id_barang'))
							   ->where('deleted',false)
							   ->get($this->data_barang)->row('isi_satuan');
		$qty = $isi_satuan*$this->input->post('qty');
		if($find->num_rows()==0) {
			$data = array(
							'id_barang' => $this->input->post('id_barang'),
							'id_pesanan' => $kode,
							'qty' => $qty
						);
			$this->db->insert($this->data_pesanan_detail,$data);
		} else {
			$data = array(
							'id_pesanan' => $kode,
							'qty' => $qty
						);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_pesanan',$kode)
					 ->update($this->data_pesanan_detail,$data);
		}
		return TRUE;
	}

	public function SaveAll($kode) {
		$hitung_data = $this->db->like('id_pesanan',"SP","after")->get($this->data_pesanan)->num_rows();
		$id = "SP".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		if($kode==NULL) {
			$data = array(
							'id_pesanan' => $id,
							'id_sp' => $this->input->post('id_sp'),
							'id_pbf' => $this->input->post('id_pbf'),
							'id_user' => $this->session->userdata('kode'),
							'tanggal_pembuatan' => date('Y-m-d'),
						);
			$this->db->insert($this->data_pesanan,$data);
			$this->db->where('id_pesanan',$kode)
					 ->update($this->data_pesanan_detail,array('id_pesanan' => $id));
		} else {
			$data = array(
							//'id_pesanan' => $id,
							'id_sp' => $this->input->post('id_sp'),
							'id_pbf' => $this->input->post('id_pbf'),
							'id_user' => $this->session->userdata('kode'),
							//'tanggal_pembuatan' => date('Y-m-d'),
						);
			$this->db->where('id_pesanan',$kode)
					 ->update($this->data_pesanan,$data);
		}
	}

	public function DelData($kode) {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->where('id_pesanan',$kode)
				 ->update($this->data_pesanan_detail,$data);
		return TRUE;
	}

	public function DelDataAll($kode) {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_pesanan',$kode)
				 ->update($this->data_pesanan,$data);
		$this->db->where('id_pesanan',$kode)
				 ->update($this->data_pesanan_detail,$data);
		return TRUE;
	}

	public function get_report_surat_pesanan(){
		$res = $this->db->where($this->data_pesanan_detail.'.deleted',FALSE)
						->join(
								$this->data_pesanan,
								$this->data_pesanan.'.id_pesanan='.
								$this->data_pesanan_detail.'.id_pesanan'
							)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pesanan_detail.'.id_barang'
							)
						->join(
								$this->data_pabrik,
								$this->data_pabrik.'.id_pabrik='.
								$this->data_barang.'.id_pabrik'
							)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pesanan.'.id_pbf'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->get($this->data_pesanan_detail);
		return $res->result();
	}

}