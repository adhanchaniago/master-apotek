<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Stok extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MLaporan');
		//$this->load->model('MBarang');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function data_stok_tersedia(){
		$list = $this->MLaporan->get_data_stok();
		$datatb = array();
		$h = "<label class='label label-danger'>Habis</label>";
		$t = "<label class='label label-success'>Tersedia</label>";
		foreach ($list as $data) {
			$row = array();
			$row[] = $data->nm_barang;
			$row[] = $data->stok_minimum;
			$row[] = $data->stok_tersedia;
			if($data->stok_tersedia<=0){
				$row[] = $h;
			}else{
				$row[] = $t;
			}
			
			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_report_stok_limit() {
		$list = $this->MLaporan->get_barang();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $this->MLaporan->get_stok_awal($data->id_barang);
			$row[] = "<a href='#' id='getmasuk' data='".$data->id_barang."'>".$this->MLaporan->get_stok_masuk($data->id_barang)."</a>";
			$row[] = "<a href='#' id='getkeluar' data='".$data->id_barang."'>".$this->MLaporan->get_stok_keluar($data->id_barang)."</a>";
			$row[] = "<a href='#' id='getsisa' data='".$data->id_barang."'>".$this->MLaporan->get_stok_sisa($data->id_barang)."</a>";

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_report_stok_masuk($kode) {
		$list = $this->MLaporan->get_history_stok_masuk($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $data->id_pembelian;
			$row[] = $data->id_opname;
			$row[] = $data->tanggal_masuk;
			$row[] = $data->stok_masuk;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_report_stok_keluar($kode) {
		$list = $this->MLaporan->get_history_stok_keluar($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $data->id_penjualan;
			$row[] = $data->id_opname;
			$row[] = $data->tanggal_keluar;
			$row[] = $data->stok_keluar;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function list_report_stok_sisa($kode) {
		$list = $this->MLaporan->get_history_stok_sisa($kode);
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = $data->nm_barang;
			$row[] = $data->id_pembelian;
			$row[] = $data->id_penjualan;
			$row[] = $data->id_opname;
			$row[] = $data->updated;
			$row[] = $data->stok_tersedia;

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
				$stok_penyesuaian = 0;
				$keuangan_penyesuaian = 0;
			} else {
				$stok_fisik = $this->MLaporan->get_stok_fisik($data->id_barang);
				$stok_penyesuaian = $this->MLaporan->get_penyesuaian($data->id_barang);
				$keuangan_penyesuaian = $this->MLaporan->get_penyesuaian_keuangan($data->id_barang);
			}
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_barang.'" style="cursor:pointer">'.$data->nm_barang.'</a>';
			$row[] = $this->MLaporan->get_stok_terakhir($data->id_barang);
			$row[] = $stok_fisik;
			$row[] = $stok_penyesuaian;
			$row[] = "Rp. ".number_format($keuangan_penyesuaian,2,",",".");

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
		$res = $this->MLaporan->SaveOpname();
		$data = array(
						'status' => TRUE
					);
		echo json_encode($data);
	}

}