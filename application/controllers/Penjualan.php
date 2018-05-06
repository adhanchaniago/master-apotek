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
		$level = $this->session->userdata('level');
		if($level=="Master" OR $level=="Pemilik") {
			redirect('dashboard/administrator');
		} else {
			redirect('dashboard/apoteker');
		}
	}

	public function list_all_data() {
		$list = $this->MPenjualan->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" href="'.base_url('transaksi/pj-bebas/'.$data->id_penjualan).'" style="cursor:pointer">'.$data->id_penjualan.'</a>';
			$row[] = $data->nm_user;
			$row[] = $data->nm_pasien;
			$row[] = $data->tanggal_penjualan;
			$row[] = $data->grandtotal;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_detail_data($kode=NULL) {
		$list = $this->MPenjualan->GetAllDetail($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->stok_tersedia.' '.$data->nm_satuan;
			$row[] = $data->qty.' '.$data->nm_satuan;
			$row[] = $data->diskon;
			$row[] = $data->subtotal;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_detail_data_resep($kode=NULL) {
		$list = $this->MPenjualan->GetAllDetailResep($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->stok_tersedia.' '.$data->nm_satuan;
			$row[] = $data->qty.' '.$data->nm_satuan;
			$row[] = $data->diskon;
			$row[] = $data->subtotal;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data($kode=NULL) {
		$res = $this->MPenjualan->GetSingle($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty,
						'diskon' => $res->diskon
					 );
		echo json_encode($data);
	}

	public function get_data_resep($kode=NULL) {
		$res = $this->MPenjualan->GetSingleResep($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty,
						'diskon' => $res->diskon
					 );
		echo json_encode($data);
	}

	public function get_nama_pasien_bebas($kode=NULL) {
		$res = $this->MPenjualan->get_nama_pasien_bebas($kode);
		$data = array(
						'nm_pasien' => $res->nm_pasien
					 );
		echo json_encode($data);
	}

	public function get_nama_pasien_resep($kode=NULL) {
		$res = $this->MPenjualan->get_nama_pasien_resep($kode);
		$data = array(
						'nm_pasien' => $res->nm_pasien,
						'nm_dokter' => $res->nm_dokter,
						'alamat_pasien' => $res->alamat_pasien
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

	public function get_subtotal_resep($kode=NULL) {
		$res = $this->MPenjualan->get_subtotal_resep($kode);
		$data = array(
						'subtotal' => $res
					 );
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$isDouble = $this->MPenjualan->find_duplicate($kode);
		$isStock = $this->MPenjualan->cek_stok();
		if($isDouble<1 AND $isStock>0) :
			$res = $this->MPenjualan->SaveData($kode);
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

	public function tambah_data_resep($kode=NULL) {
		$isDouble = $this->MPenjualan->find_duplicate_resep($kode);
		$isStock = $this->MPenjualan->cek_stok();
		if($isDouble<1 AND $isStock>0) :
			$res = $this->MPenjualan->SaveDataResep($kode);
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
		$res = $this->MPenjualan->SaveAll($kode);
		$data = array(
						'kembalian' => $res
					);
		echo json_encode($data);
	}

	public function simpan_semua_resep($kode=NULL) {
		$res = $this->MPenjualan->SaveAllResep($kode);
		$data = array(
						'kembalian' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data($kode) {
		$res = $this->MPenjualan->DelData($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data_resep() {
		$res = $this->MPenjualan->DelDataResep();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_semua($kode=NULL) {
		$res = $this->MPenjualan->DelDataAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_semua_resep($kode=NULL) {
		$res = $this->MPenjualan->DelDataAllResep($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}