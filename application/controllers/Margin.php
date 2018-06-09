<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Margin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MMargin');
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
		$list = $this->MMargin->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_margin.'" style="cursor:pointer">'.$data->id_margin.'</a>';
			$row[] = $data->nm_margin;
			$row[] = $data->persentase_margin;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MMargin->GetSingle();
		$data = array(
						'id_margin' => $res->id_margin,
						'nm_margin' => $res->nm_margin,
						'persentase_margin' => $res->persentase_margin
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MMargin->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MMargin->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MMargin->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}