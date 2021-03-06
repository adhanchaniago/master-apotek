<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pabrik extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPabrik');
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
		$list = $this->MPabrik->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_pabrik.'" style="cursor:pointer">'.$data->id_pabrik.'</a>';
			$row[] = $data->nm_pabrik;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MPabrik->GetSingle();
		$data = array(
						'id_pabrik' => $res->id_pabrik,
						'nm_pabrik' => $res->nm_pabrik
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MPabrik->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MPabrik->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MPabrik->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}