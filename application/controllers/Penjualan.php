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

	public function get_data($kode=NULL) {
		$res = $this->MPenjualan->GetSingle($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty,
						'diskon' => $res->diskon
					);
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$isDouble = $this->MPenjualan->find_duplicate($kode);
		$isStock = $this->MPenjualan->cek_stok();
		if($isDouble<1) :
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

	public function simpan_semua($kode=NULL) {
		$res = $this->MPenjualan->SaveAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MPenjualan->DelData();
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

}