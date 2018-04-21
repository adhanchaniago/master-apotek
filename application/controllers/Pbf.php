<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pbf extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPBF');
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
		$list = $this->MPBF->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_pbf.'" style="cursor:pointer">'.$data->id_pbf.'</a>';
			$row[] = $data->nm_pbf;
			$row[] = $data->alamat_pbf;
			$row[] = $data->kota_pbf;
			$row[] = $data->kontak_kantor_pbf;
			$row[] = $data->kontak_person_pbf;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MPBF->GetSingle();
		$data = array(
						'id_pbf' => $res->id_pbf,
						'nm_pbf' => $res->nm_pbf,
						'alamat_pbf' => $res->alamat_pbf,
						'kota_pbf' => $res->kota_pbf,
						'kontak_kantor_pbf' => $res->kontak_kantor_pbf,
						'kontak_person_pbf' => $res->kontak_person_pbf
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MPBF->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MPBF->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MPBF->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}