<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Resep extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MResep');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		$level = $this->session->userdata('level');
		if($level=="Master" OR $level=="Pemilik") {
			redirect('dashboard/administrator');
		} else {
			redirect('dashboard/apoteker');
		}
	}

	public function list_all_data() {
		$list = $this->MResep->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" href="'.base_url('transaksi/pj-bebas/'.$data->id_resep).'" style="cursor:pointer">'.$data->id_resep.'</a>';
			$row[] = $data->nm_user;
			//$row[] = $data->nm_pasien;
			$row[] = $data->tanggal_resep;
			$row[] = "Rp. ".number_format($data->grandtotal,2,",",".");

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_data_piutang() {
		$list = $this->MResep->get_piutang();
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

	public function get_sisa_hutang($kode=NULL) {
		$res = $this->MResep->get_sisa_hutang($kode);
		$data = array(
						'subtotal' => $res
					 );
		echo json_encode($data);
	}

	public function get_bayar_hutang($kode=NULL) {
		$res = $this->MResep->get_bayar_hutang($kode);
		$data = array(
						'subtotal' => $res
					 );
		echo json_encode($data);
	}

	public function bayar_piutang($kode) {
		$res = $this->MResep->bayar_piutang($kode);
		$data = array(
			'status' => $res
		);
		echo json_encode($data);
	}

	public function list_detail_data($kode=NULL) {
		$list = $this->MResep->GetAllDetail($kode);
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

	public function get_data($kode=NULL) {
		$res = $this->MResep->GetSingle($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty,
						'diskon' => $res->diskon,
						'etiket' => $res->etiket
					 );
		echo json_encode($data);
	}

	public function get_nama_pasien($kode=NULL) {
		$res = $this->MResep->get_nama_pasien_bebas($kode);
		$data = array(
						'id_kwitansi' => $res->id_kwitansi,
						'nm_pasien' => $res->nm_pasien,
						'nm_dokter' => $res->nm_dokter,
						'alamat_pasien' => $res->alamat_pasien
					 );
		echo json_encode($data);
	}

	public function get_subtotal($kode=NULL) {
		$res = $this->MResep->get_subtotal($kode);
		$data = array(
						'subtotal' => $res
					 );
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$isDouble = $this->MResep->find_duplicate($kode);
		$isStock = $this->MResep->cek_stok();
		if($isDouble<1 AND $isStock>0) :
			$res = $this->MResep->SaveData($kode);
			$data = array(
							'status' => TRUE
						);
			echo json_encode($data);
		else :
			$data = array(
							'status' => FALSE
						 );
			echo json_encode($data);
		endif;
	}

	public function simpan_semua($kode=NULL) {
		$res = $this->MResep->SaveAll($kode);
		$data = array(
						'kembalian' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data($kode=null) {
		$res = $this->MResep->DelData($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_semua($kode=NULL) {
		$res = $this->MResep->DelDataAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function data_stok_tersedia(){
		$list = $this->MResep->get_data_stok();
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
		$list = $this->MResep->get_data_barang_expired();
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
		$list = $this->MResep->get_data_jatuh_tempo();
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