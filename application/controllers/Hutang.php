<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Hutang extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MHutang');
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

	public function list_report_hutang() {
		$list = $this->MHutang->GetAll();
		$datatb= array();
		$nomor = 1;
		$b = "Belum Lunas";
		$l = "Lunas";
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->id_pembelian;
			$row[] = $data->nm_pbf;
			$row[] = $data->tanggal_jatuh_tempo;
			$row[] = "Rp. ".number_format($data->subtotal,2,",",".");
			if($data->lunas==0){
				$row[] = $b;
			}else{
				$row[] = $l;
			}
			

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

}