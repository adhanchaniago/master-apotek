<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kemasan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MKemasan');
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
		$list = $this->MKemasan->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_kemasan.'" style="cursor:pointer">'.$data->id_kemasan.'</a>';
			$row[] = $data->nm_kemasan;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MKemasan->GetSingle();
		$data = array(
						'id_kemasan' => $res->id_kemasan,
						'nm_kemasan' => $res->nm_kemasan
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MKemasan->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MKemasan->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MKemasan->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}