<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Piutang extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPiutang');
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

	public function list_report_piutang() {
		$list = $this->MPiutang->GetAll();
		$datatb= array();
		$nomor = 1;
		$b = "Belum Lunas";
		$l = "Lunas";
		foreach ($list as $data) {
			if($data->tanggal_pelunasan==NULL) : 
				$pelunasan = "-"; 
			else : 
				$pelunasan = date("d-m-Y", strtotime($data->tanggal_pelunasan)); 
			endif;
			$row = array();
			$row[] = $nomor++;
			$row[] = date("d-m-Y", strtotime($data->tanggal_penjualan));
			$row[] = $data->id_penjualan;
			$row[] = $data->nm_pasien;
			$row[] = $pelunasan;
			$row[] = "Rp. ".number_format($data->grandtotal,2,",",".");
			if($data->status==0){
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