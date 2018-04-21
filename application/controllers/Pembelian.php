<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pembelian extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPembelian');
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

	public function buat_data_pembelian() {
		$data['Title'] = "Detail Pembelian";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Form/V_Form_Pembelian';
		$this->load->view('Master',$data);
	}

	public function list_all_data() {
		$list = $this->MPembelian->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" href="'.base_url('pembelian/buat_data_pembelian/'.$data->id_pembelian).'" style="cursor:pointer">'.$data->id_pembelian.'</a>';
			$row[] = $data->nm_pbf;
			$row[] = $data->tanggal_pembelian;
			$row[] = $data->tanggal_jatuh_tempo;
			$row[] = $data->subtotal;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_detail_data($kode=NULL) {
		$list = $this->MPembelian->GetAllDetail($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->qty.' '.$data->nm_kemasan;
			$row[] = $data->subtotal_barang;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data($kode=NULL) {
		$res = $this->MPembelian->GetSingle($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty,
						'batch' => $res->batch,
						'kadaluarsa' => $res->kadaluarsa,
						'subtotal_barang' => $res->subtotal_barang
					);
		echo json_encode($data);
	}

	public function get_detail($kode=NULL) {
		$res = $this->MPembelian->GetDetail($kode);
		$data = array(
						'tanggal_jatuh_tempo' => $res->tanggal_jatuh_tempo,
						'diskon' => $res->diskon,
						'id_pbf' => $res->id_pbf
					);
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$res = $this->MPembelian->SaveData($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function simpan_semua($kode=NULL) {
		$res = $this->MPembelian->SaveAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MPembelian->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_semua($kode=NULL) {
		$res = $this->MPembelian->DelDataAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}