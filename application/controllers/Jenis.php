<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MJenis');
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
		$list = $this->MJenis->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_jenis.'" style="cursor:pointer">'.$data->id_jenis.'</a>';
			$row[] = $data->nm_jenis;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MJenis->GetSingle();
		$data = array(
						'id_jenis' => $res->id_jenis,
						'nm_jenis' => $res->nm_jenis
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MJenis->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MJenis->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MJenis->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}