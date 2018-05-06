<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Stok extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MLaporan');
		$this->load->model('MBarang');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function list_report_stok_limit() {
		$list = $this->MLaporan->get_barang();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $this->MLaporan->get_stok_awal($data->id_barang);//+$this->MLaporan->get_stok_keluar($data->id_barang);
			$row[] = $this->MLaporan->get_stok_masuk($data->id_barang);
			$row[] = $this->MLaporan->get_stok_keluar($data->id_barang);
			$row[] = $this->MLaporan->get_stok_sisa($data->id_barang);

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_report_stok_opname() {
		$list = $this->MLaporan->get_barang();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			if($this->MLaporan->get_stok_fisik($data->id_barang)==NULL) {
				$stok_fisik = 0;
			} else {
				$stok_fisik = $this->MLaporan->get_penyesuaian($data->id_barang);
			}
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $this->MLaporan->get_stok_terakhir($data->id_barang);
			$row[] = $this->MLaporan->get_stok_fisik($data->id_barang);
			$row[] = $stok_fisik;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MLaporan->GetSingle();
		$data = array(
						'id_barang' => $res->id_barang,
						'nm_barang' => $res->nm_barang
					 );
		echo json_encode($data);
	}

	public function penyesuaian() {
		$res = $this->MLaporan->SaveData();
		$data = array(
						'status' => TRUE
					);
		echo json_encode($data);
	}

}