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
			$row[] = '<a id="getdata" href="'.base_url('transaksi/pemesanan/'.$data->id_pesanan).'" style="cursor:pointer">'.$data->id_pesanan.'</a>';
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

	public function list_detail_data($kode=NULL) {
		$list = $this->MPemesanan->GetAllDetail($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->qty/$data->isi_satuan.' '.$data->nm_kemasan;

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
						'qty' => $res->qty/$res->isi_satuan
					);
		echo json_encode($data);
	}

	public function get_status($kode=NULL) {
		$res = $this->MPemesanan->GetDetail($kode);
		if($res->status==1) :
			$data = array(
							'status' => TRUE
						);
		else : 
			$data = array(
							'status' => FALSE
						);
		endif;
		echo json_encode($data);
	}

	public function get_detail($kode=NULL) {
		$res = $this->MPemesanan->GetDetail($kode);
		$data = array(
						// 'tanggal_jatuh_tempo' => $res->tanggal_jatuh_tempo,
						// 'diskon' => $res->diskon,
						'id_sp' => $res->id_sp,
						'id_pbf' => $res->id_pbf
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

	public function hapus_data($kode=NULL) {
		$res = $this->MPemesanan->DelData($kode);
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

	public function checkout($kode) {
		$res = $this->MPemesanan->checkout($kode);
		$data = array(
						'url' => $res
					);
		echo json_encode($data);
	}

	public function list_report_surat_pesanan(){
		$list = $this->MPemesanan->get_report_surat_pesanan();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->tanggal_pembuatan;
			$row[] = $data->id_pesanan;
			$row[] = $data->nm_barang;
			$row[] = $data->nm_pabrik;
			$row[] = $data->nm_pbf;
 			$row[] = $data->qty.' '.$data->nm_kemasan;
			$row[] = "Rp. ".number_format($data->harga_dasar,2,",",".");
			$row[] = "Rp. ".number_format($data->harga_dasar,2,",",".");
			
			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

}