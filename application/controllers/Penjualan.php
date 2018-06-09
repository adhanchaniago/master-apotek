<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Penjualan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		redirect('dashboard','refresh');
	}
	// Retur Penjualan
	public function retur_transaksi($kode) {
		$res = $this->MPenjualan->retur_transaksi($kode);
		$data = array(
			'status' => $res
		);
		echo json_encode($data);
	}
	
	public function list_detail_retur($kode) {
		$list = $this->MPenjualan->get_detail_retur($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->id_barang;
			$row[] = $data->nm_barang;
			$row[] = $data->batch;
			$row[] = $data->qty;

			$datatb[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"data" => $datatb
		);
		echo json_encode($output);
	}

	public function list_data_retur() {
		$list = $this->MPenjualan->get_all_retur();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = "<a href='#' id='getdetail' data='".$data->id_penjualan."'>".$data->id_penjualan."</a>";
			$row[] = $data->nm_user;
			$row[] = $data->nm_pasien;
			$row[] = $data->tanggal_penjualan;
			$row[] = "Rp. ".number_format($data->grandtotal,2,",",".");

			$datatb[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"data" => $datatb
		);
		echo json_encode($output);
	}
	// Pelunasan Piutang
	public function bayar_piutang($kode) {
		$res = $this->MPenjualan->bayar_piutang($kode);
		$data = array(
			'status' => $res
		);
		echo json_encode($data);
	}

	public function get_bayar_hutang($kode=NULL) {
		$res = $this->MPenjualan->get_bayar_hutang($kode);
		$data = array(
			'subtotal' => $res
		);
		echo json_encode($data);
	}

	public function get_sisa_hutang($kode=NULL) {
		$res = $this->MPenjualan->get_sisa_hutang($kode);
		$data = array(
			'subtotal' => $res
		);
		echo json_encode($data);
	}

	public function list_detail_piutang($kode) {
		$list = $this->MPenjualan->get_detail_piutang($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $data->stok_tersedia;
			$row[] = $data->qty;
			$row[] = $data->diskon;
			$row[] = "Rp. ".number_format($data->subtotal,2,",",".");

			$datatb[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"data" => $datatb
		);
		echo json_encode($output);
	}

	public function list_data_piutang() {
		$list = $this->MPenjualan->get_all_piutang();
		$datatb= array();
		$nomor = 1;
		$b = "Belum Lunas";
		$l = "Lunas";
		foreach ($list as $data) {
			$kode = substr($data->id_penjualan,0,3);
			$row = array();
			$row[] = $nomor++;
			$row[] = "<a href='#' id='getdetail' data='".$data->id_penjualan."'>".$data->id_penjualan."</a>";
			$row[] = $data->nm_user;
			$row[] = $data->nm_pasien;
			$row[] = $data->tanggal_penjualan;
			$row[] = "Rp. ".number_format($data->sisa_hutang,2,",",".");
			if($data->status==0){
				$row[] = $b;
			}else{
				$row[] = $l;
			}
			$datatb[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"data" => $datatb
		);
		echo json_encode($output);
	}
	// Transaksi
	public function pembayaran($kode=NULL) {
		$res = $this->MPenjualan->pembayaran($kode);
		$data = array(
			'kembalian' => $res
		);
		echo json_encode($data);
	}

	public function hapus_data($kode=null) {
		$res = $this->MPenjualan->delete_data($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function get_data_penjualan($kode=NULL) {
		$res = $this->MPenjualan->get_data_penjualan($kode);
		if($res!=NULL) :
			$data = array(
				'id_nota' => $res->id_special,
				'nm_pasien' => $res->nm_pasien,
				'status' => $res->status
			);
		else :
			$data = array(
				'id_nota' => null,
				'nm_pasien' => null,
				'status' => null
			);
		endif;
		echo json_encode($data);
	}

	public function get_data_barang($kode=NULL) {
		$res = $this->MPenjualan->get_data_barang($kode);
		$data = array(
			'id_barang' => $res->id_barang,
			'qty' => $res->qty,
			'diskon' => $res->diskon
		);
		echo json_encode($data);
	}

	public function get_subtotal($kode=NULL) {
		$res = $this->MPenjualan->get_subtotal($kode);
		$data = array(
			'subtotal' => $res
		);
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$isDouble = $this->MPenjualan->find_duplicate($kode);
		$isStock = $this->MPenjualan->cek_stok();
		if($isDouble<1 AND $isStock>0) :
			$res = $this->MPenjualan->save_data($kode);
			$data = array(
				'status' => TRUE
			);
			echo json_encode($data);
		else :
			$res = $this->MPenjualan->edit_data($kode);
			$data = array(
				'status' => TRUE
			);
			echo json_encode($data);
		endif;
	}

	public function list_shopping($kode=NULL) {
		$list = $this->MPenjualan->get_shopping_list($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->stok_tersedia.' '.$data->nm_satuan;
			$row[] = $data->qty.' '.$data->nm_satuan;
			$row[] = $data->diskon;
			$row[] = "Rp. ".number_format($data->subtotal,2,",",".");

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_all_data() {
		$list = $this->MPenjualan->get_all();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = "<a href='#' id='getdetail' data='".$data->id_penjualan."'>".$data->id_penjualan."</a>";
			$row[] = $data->nm_user;
			$row[] = $data->nm_pasien;
			$row[] = $data->tanggal_penjualan;
			$row[] = "Rp. ".number_format($data->grandtotal,2,",",".");

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function data_stok_tersedia(){
		$list = $this->MPenjualan->get_data_stok();
		$datatb = array();
		$h = "<label class='label label-danger'>Habis</label>";
		$t = "<label class='label label-success'>Tersedia</label>";
		foreach ($list as $data) {
			$row = array();
			$row[] = $data->nm_barang;
			$row[] = $data->stok_minimum;
			$row[] = $data->stok_tersedia;
			if($data->stok_tersedia<=0){
				$row[] = $h;
			}else{
				$row[] = $t;
			}
			
			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function data_barang_expired(){
		$list = $this->MPenjualan->get_data_barang_expired();
		$datatb = array();
		foreach ($list as $data) {
			$start_date = date('Y-m-d');
			$s = $data->tanggal_pembelian;
			$selisih = strtotime($s) - strtotime($start_date);
			$hari = $selisih/(60*60*24);
                //60 detik * 60 menit * 24 jam = 1 hari
			$row = array();
			$row[] = $data->nm_barang;
			$row[] = $data->batch;
			$row[] = $data->kadaluarsa;
			$row[] = $hari." Hari";

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function data_jatuh_tempo(){
		$list = $this->MPenjualan->get_data_jatuh_tempo();
		$datatb = array();
		foreach ($list as $data) {
			$start_date = date('Y-m-d');
			$s = $data->tanggal_jatuh_tempo;
			$selisih = strtotime($s) - strtotime($start_date);
			$hari = $selisih/(60*60*24);
                //60 detik * 60 menit * 24 jam = 1 hari
			$row = array();
			$row[] = $data->id_pembelian;
			$row[] = $data->nm_pbf;
			$row[] = $data->tanggal_pembelian;
			$row[] = $data->tanggal_jatuh_tempo;
			$row[] = $hari." Hari";

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

}