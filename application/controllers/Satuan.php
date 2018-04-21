<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Satuan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MSatuan');
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
		$list = $this->MSatuan->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_satuan.'" style="cursor:pointer">'.$data->id_satuan.'</a>';
			$row[] = $data->nm_satuan;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MSatuan->GetSingle();
		$data = array(
						'id_satuan' => $res->id_satuan,
						'nm_satuan' => $res->nm_satuan
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MSatuan->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MSatuan->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MSatuan->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}