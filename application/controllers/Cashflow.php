<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cashflow extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MCashflow');
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

	public function list_report_cashflow() {
		$list = date('t',strtotime('2018-05-01'));
		$datatb = array();
		$nomor = 1;
		for($x=01;$x<=$list;$x++) {
			$d = date('Y-m-'.str_pad($x, 2, "0", STR_PAD_LEFT));
			$pemasukan = $this->MCashflow->get_pemasukan_perhari($d);
			$pengeluaran = $this->MCashflow->get_pengeluaran_perhari($d);
			$row = array();
			if($pemasukan!=NULL OR $pengeluaran!=NULL) :
				$row[] = $nomor++;
				$row[] = $d;
				$row[] = "Rp. ".number_format($pemasukan,2,",",".");
				$row[] = "Rp. ".number_format($pengeluaran,2,",",".");
				$datatb[] = $row;
			endif;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

}