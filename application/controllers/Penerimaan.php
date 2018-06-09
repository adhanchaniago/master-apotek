<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Penerimaan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenerimaan');
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

	public function list_report_penerimaan_barang() {
		$list = $this->MPenerimaan->get_report_penerimaan_barang();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->tanggal_pembuatan;
			$row[] = $data->id_pesanan;
			$row[] = $data->id_pembelian;
			$row[] = $data->tanggal_pembelian;
			$row[] = $data->nm_barang;
			$row[] = $data->nm_pabrik;
			$row[] = $data->nm_pbf;
			$row[] = $data->qty.' '.$data->nm_kemasan;
			$row[] = "Rp. ".number_format(($data->subtotal/$data->qty),2,",",".");
			$row[] = "Rp. ".number_format($data->subtotal,2,",",".");

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

}