<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dokter extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MDokter');
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
		$list = $this->MDokter->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_dokter.'" style="cursor:pointer">'.$data->id_dokter.'</a>';
			$row[] = $data->nm_dokter;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MDokter->GetSingle();
		$data = array(
						'id_dokter' => $res->id_dokter,
						'nm_dokter' => $res->nm_dokter
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MDokter->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MDokter->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MDokter->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}