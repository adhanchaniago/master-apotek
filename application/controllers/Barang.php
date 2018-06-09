<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Barang extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MBarang');
		$this->load->model('MLaporan');
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
		$list = $this->MBarang->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $data->nm_jenis;
			$row[] = $data->nm_pabrik;
			$row[] = $data->nm_kemasan;
			$row[] = $data->nm_satuan;
			$row[] = $data->golongan_obat;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MBarang->GetSingle();
		$data = array(
						'id_barang' => $res->id_barang,
						'nm_barang' => $res->nm_barang,
						'id_jenis' => $res->id_jenis,
						'id_golongan' => $res->golongan_obat,
						'id_kemasan' => $res->id_kemasan,
						'id_satuan' => $res->id_satuan,
						'isi_satuan' => $res->isi_satuan,
						'margin' => explode(",",$res->margin),
						'harga_dasar' => $res->harga_dasar,
						'id_pabrik' => $res->id_pabrik,
						'stok_maksimum' => $res->stok_maksimum,
						'stok_minimum' => $res->stok_minimum,
						'konsinyasi' => $res->konsinyasi,
						'formularium' => $res->formularium
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MBarang->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MBarang->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MBarang->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function data_barang_expired(){
		$list = $this->MLaporan->get_data_barang_expired();
		$datatb = array();
		foreach ($list as $data) {
			$start_date = date('Y-m-d');
			$s = $data->kadaluarsa;
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

}