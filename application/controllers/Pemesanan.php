<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pemesanan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPemesanan');
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
		$list = $this->MPemesanan->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" href="'.base_url('pemesanan/buat_data_pemesanan/'.$data->id_pesanan).'" style="cursor:pointer">'.$data->id_pesanan.'</a>';
			$row[] = $data->nm_pbf;
			$row[] = $data->tanggal_pembuatan;
			$row[] = $data->nm_user;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function buat_data_pemesanan() {
		$data['Title'] = "Detail Pemesanan";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Form/V_Form_Pemesanan';
		$this->load->view('Master',$data);
	}

	public function list_detail_data($kode=NULL) {
		$list = $this->MPemesanan->GetAllDetail($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->qty;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data($kode=NULL) {
		$res = $this->MPemesanan->GetSingle($kode);
		$data = array(
						'id_barang' => $res->id_barang,
						'qty' => $res->qty
					);
		echo json_encode($data);
	}

	public function tambah_data($kode=NULL) {
		$res = $this->MPemesanan->SaveData($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function simpan_semua($kode=NULL) {
		$res = $this->MPemesanan->SaveAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MPemesanan->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_semua($kode=NULL) {
		$res = $this->MPemesanan->DelDataAll($kode);
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}