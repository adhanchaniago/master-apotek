<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPembelian extends CI_Model {

	protected $data_pbf = "ak_data_pbf";
	protected $data_user = 'ak_data_user';
	protected $data_barang = "ak_data_barang";
	protected $data_kemasan = "ak_data_kemasan";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_pembelian_detail = "ak_data_pembelian_detail";
	protected $data_barang_stok_masuk = "ak_data_barang_stok_masuk";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_pabrik = "ak_data_pabrik";

	public function GetAll() {
		$res = $this->db->where($this->data_pembelian.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pembelian.'.id_pbf'
							)
						->get($this->data_pembelian);
		return $res->result();
	}

	public function total_pembelian() {
		return $this->db->where('deleted',FALSE)
						->get($this->data_pembelian)->num_rows();
	}

	public function total_hutang() {
		return $this->db->where('deleted',FALSE)
						->where('status',FALSE)
						->get($this->data_pembelian)->num_rows();
	}

	public function total_pengeluaran() {
		$res = $this->db->select_sum('subtotal')
						->where('deleted',FALSE)
						->where('status',TRUE)
						->where('tanggal_pembelian>=',date('Y-01-01'))
						->where('tanggal_pembelian<=',date('Y-12-31'))
						->get($this->data_pembelian);
		return $res->row('subtotal');
	}

	public function total_hutangs() {
		$res = $this->db->select_sum('subtotal')
						->where('deleted',FALSE)
						->where('status',FALSE)
						->where('tanggal_pembelian>=',date('Y-01-01'))
						->where('tanggal_pembelian<=',date('Y-12-31'))
						->get($this->data_pembelian);
		return $res->row('subtotal');
	}

	public function GetAllDetail($kode) {
		$res = $this->db->where('id_pembelian',$kode)
						->where($this->data_pembelian_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pembelian_detail.'.id_barang'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->get($this->data_pembelian_detail);
		return $res->result();
	}

	public function GetSingle($kode) {
		$id_barang = $this->input->post('id_barang');
		$res = $this->db->where($this->data_pembelian_detail.'.id_pembelian',$kode)
						->where($this->data_pembelian_detail.'.id_barang',$id_barang)
						->where($this->data_barang_detail.'.id_pembelian',$kode)
						->join(
								$this->data_barang_detail,
								$this->data_barang_detail.'.id_barang='.
								$this->data_pembelian_detail.'.id_barang'
							)
						->get($this->data_pembelian_detail);
		return $res->row();
	}

	public function GetDetail($kode) {
		$res = $this->db->select('tanggal_jatuh_tempo,diskon,id_pbf,status,id_faktur')->where('id_pembelian',$kode)
						->get($this->data_pembelian);
		return $res->row();
	}

	public function SaveData($kode) {
		$find = $this->db->where('id_barang',$this->input->post('id_barang'))
						 ->where('id_pembelian',$kode)
						 ->where('deleted',FALSE)
						 ->get($this->data_pembelian_detail);
		$isi_satuan = $this->db->where('id_barang',$this->input->post('id_barang'))
							   ->where('deleted',false)
							   ->get($this->data_barang)->row('isi_satuan');
		$qty = $isi_satuan*$this->input->post('qty');
		if($find->num_rows()==0) {
			$data_1 = array(
								'id_barang' => $this->input->post('id_barang'),
								'id_pembelian' => $kode,
								'qty' => $this->input->post('qty'),
								'ppn' => $this->input->post('ppn'),
								'diskon' => $this->input->post('diskon'),
								'subtotal_barang' => $this->input->post('subtotal_barang'),
								'checked' => 1
							);
			$this->db->insert($this->data_pembelian_detail,$data_1);
			$data_2 = array(
								'id_barang' => $this->input->post('id_barang'),
								'id_pembelian' => $kode,
								'batch' => $this->input->post('batch'),
								'kadaluarsa' => $this->input->post('kadaluarsa'),
								'stok_tersedia' => $qty,
								//'deleted' => 1
							);
			$this->db->insert($this->data_barang_detail,$data_2);
			$data_3 = array(
								'id_barang' => $this->input->post('id_barang'),
								'id_pembelian' => $kode,
								'tanggal_masuk' => date('Y-m-d H:i:s'),
								'stok_masuk' => $qty,
								//'deleted' => 1
							);
			$this->db->insert($this->data_barang_stok_masuk,$data_3);
			$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
								  ->where('fixed',TRUE)
								  ->where('deleted',FALSE)
								  ->order_by('updated','DESC')
								  ->get($this->data_barang_stok_tersedia)
								  ->row();
			$stok_tersedia = $stok_lama->stok_tersedia+$qty;
			$data_4 = array(
								'id_pembelian' => $kode,
								'id_barang' => $this->input->post('id_barang'),
								'stok_tersedia' => $stok_tersedia,
								'updated' => date('Y-m-d H:i:s')
							);
			$this->db->insert($this->data_barang_stok_tersedia,$data_4);
			if($stok_lama!=NULL) :
				$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
					 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
			endif;
		} else {
			$data_1 = array(
							'id_pembelian' => $kode,
							'qty' => $this->input->post('qty'),
							'ppn' => $this->input->post('ppn'),
							'diskon' => $this->input->post('diskon'),
							'subtotal_barang' => $this->input->post('subtotal_barang'),
							'checked' => 1
						);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_pembelian',$kode)
					 ->update($this->data_pembelian_detail,$data_1);
			$data_2 = array(
								//'id_barang' => $this->input->post('id_barang'),
								'id_pembelian' => $kode,
								'batch' => $this->input->post('batch'),
								'kadaluarsa' => $this->input->post('kadaluarsa'),
								'stok_tersedia' => 'stok_tersedia'+$qty,
								//'deleted' => 1
							);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_pembelian',$kode)
					 ->update($this->data_barang_detail,$data_2);
			$data_3 = array(
							//'id_barang' => $this->input->post('id_barang'),
							'id_pembelian' => $kode,
							//'tanggal_masuk' => date('Y-m-d'),
							'stok_masuk' => $qty
							//'deleted' => 1
						);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_pembelian',$kode)
					 ->update($this->data_barang_stok_masuk,$data_3);
			$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('fixed',TRUE)
					 ->where('deleted',FALSE)
					 ->order_by('updated','DESC')
					 ->get($this->data_barang_stok_tersedia)
					 ->row();
			$stok_tersedia = $stok_lama->stok_tersedia+$qty;
			if($stok_lama!=NULL) :
				$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
						 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
			endif;
			$data_4 = array(
							'id_pembelian' => $kode,
							'id_barang' => $this->input->post('id_barang'),
							'stok_tersedia' => $stok_tersedia,
							'updated' => date('Y-m-d H:i:s'),
							'fixed' => true
						);
			$this->db->insert($this->data_barang_stok_tersedia,$data_4);
		}
		return TRUE;
	}

	public function SaveAll($kode) {
		$hitung_data = $this->db->get($this->data_pembelian)->num_rows();
		$id = "FK".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		$subtotal = $this->db->select_sum('subtotal_barang')
							 ->where('id_pembelian',$kode)
							 ->get($this->data_pembelian_detail)
							 ->row();
		$diskon = $this->db->select_sum('diskon')
							 ->where('id_pembelian',$kode)
							 ->get($this->data_pembelian_detail)
							 ->row('diskon');
		$newDate = date("Y-m-d", strtotime($this->input->post('tanggal_jatuh_tempo')));
		if($kode==NULL) {
			$data_1 = array(
							'id_pembelian' => $id,
							'id_pbf' => $this->input->post('id_pbf'),
							'id_faktur' => $this->input->post('id_faktur'),
							'id_user' => $this->session->userdata('kode'),
							'tanggal_pembelian' => date('Y-m-d'),
							'tanggal_jatuh_tempo' => $newDate,
							'diskon' => $diskon,
							'subtotal' => $subtotal->subtotal_barang,
							'status' => 1
						);
			$this->db->insert($this->data_pembelian,$data_1);
			$data_2 = array(
								//'id_barang' => $this->input->post('id_barang'),
								'id_pembelian' => $id,
								//'batch' => $this->input->post('batch'),
								//'kadaluarsa' => $this->input->post('kadaluarsa'),
								//'stok_tersedia' => 'stok_tersedia'+$this->input->post('qty'),
								//'deleted' => 0
							);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_detail,$data_2);
			$data_3 = array(
							//'id_barang' => $this->input->post('id_barang'),
							'id_pembelian' => $id,
							//'tanggal_masuk' => date('Y-m-d'),
							//'stok_masuk' => $this->input->post('qty')
							//'deleted' => 0
						);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_stok_masuk,$data_3);
			$data_4 = array(
								'id_pembelian' => $id,
								'fixed' => TRUE
							);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_stok_tersedia,$data_4);
			$this->db->where('id_pembelian',$kode)
					 ->update($this->data_pembelian_detail,array('id_pembelian' => $id));
		} else {
			$data = array(
							//'id_pembelian' => $id,
							'id_pbf' => $this->input->post('id_pbf'),
							'id_faktur' => $this->input->post('id_faktur'),
							'id_user' => $this->session->userdata('kode'),
							'tanggal_jatuh_tempo' => $newDate,
							'diskon' => $diskon,
							'subtotal' => $subtotal->subtotal_barang,
							'status' => 1
							//'tanggal_pembuatan' => date('Y-m-d'),
						);
			$detail = array(
								//'id_barang' => $this->input->post('id_barang'),
								//'id_pembelian' => $id,
								//'batch' => $this->input->post('batch'),
								//'kadaluarsa' => $this->input->post('kadaluarsa'),
								//'stok_tersedia' => 'stok_tersedia'+$this->input->post('qty'),
								'deleted' => 0
							);
			$stok = array(
							//'id_barang' => $this->input->post('id_barang'),
							//'id_pembelian' => $id,
							//'tanggal_masuk' => date('Y-m-d'),
							//'stok_masuk' => $this->input->post('qty')
							'deleted' => 0
						);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_pembelian,$data);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_detail,$detail);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_stok_masuk,$stok);
			$data_4 = array(
								//'id_pembelian' => $id,
								'fixed' => TRUE
							);
			$this->db->where('id_pembelian',$kode)
					 ->where('deleted',FALSE)
					 ->update($this->data_barang_stok_tersedia,$data_4);
		}
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_pembelian_detail,$data);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_barang_detail,$data);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_barang_stok_masuk,$data);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_barang_stok_tersedia,$data);
		return TRUE;
	}

	public function DelDataAll($kode) {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_pembelian',$kode)
				 ->update($this->data_pembelian,$data);
		$this->db->where('id_pembelian',$kode)
				 ->update($this->data_pembelian_detail,$data);
		$this->db->where('id_pembelian',$kode)
				 ->update($this->data_barang_detail,$data);
		$this->db->where('id_pembelian',$kode)
				 //->where('id_barang',$this->input->post('id_barang'))
				 ->where('id_opname',null)
				 ->where('id_penjualan',null)
				 ->delete($this->data_barang_stok_tersedia);
		$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
							  ->where('fixed',TRUE)
							  ->where('deleted',TRUE)
							  ->order_by('updated','DESC')
							  ->get($this->data_barang_stok_tersedia)
							  ->row('id_stok_tersedia');
		$this->db->where('id_stok_tersedia',$stok_lama)
				 ->update($this->data_barang_stok_tersedia,array('deleted' => 0));
		return TRUE;
	}

	public function get_report_pembelian_brg_pab(){
		$res = $this->db->where($this->data_pembelian_detail.'.deleted',FALSE)
						->join(
								$this->data_pembelian,
								$this->data_pembelian.'.id_pembelian='.
								$this->data_pembelian_detail.'.id_pembelian'
							)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pembelian_detail.'.id_barang'
							)
						->join(
								$this->data_pabrik,
								$this->data_pabrik.'.id_pabrik='.
								$this->data_barang.'.id_pabrik'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->get($this->data_pembelian_detail);
		return $res->result();
	}

}