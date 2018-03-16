<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pesanan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPesanan');
	}

	public function index() {
		$data['Title'] = "Surat Pesanan";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Gudang/V_Surat_Pesanan';
		$this->load->view('Master',$data);
	}

	public function GetData() {
		$data = $this->MPesanan->GetData();
		echo
			'{
				"data": [';
				foreach($data as $d) :
					echo'	[
								"'.$d->id_pesanan.'",
								"'.$d->nm_pbf.'"
							],';
				endforeach;
					echo'	[
								"-",
								"-"
							]';
		echo'	]
			}';
	}

	public function ajax_list($id) {
		$list = $this->MPesanan->GetBarangDetail($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$row = array();
			$row[] = $d->nm_barang;
			$row[] = $d->qty_raw;

			$data[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"data" => $data,
									 );
		echo json_encode($output);
	}

	public function buat_surat_pesanan($id=null) {
		$data['Title'] = "Buat Surat Pesanan";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MPesanan->GetDataInstansi();
		$data['Barang'] = $this->MPesanan->GetBarang();
		$data['TotalSurat'] = $this->MPesanan->GetTotalSP();
		$data['PBF'] = $this->MPesanan->GetPBF();
		$data['DetBarang'] = $this->MPesanan->GetDataPesanan($id);
		$data['Subtotal'] = $this->MPesanan->GetSubTotal($id);
		$data['Konten'] = 'Gudang/V_Buat_Surat_Pesanan';
		$this->load->view('Master',$data);
	}

	public function prints($id=null) {
		$data['Title'] = "Buat Surat Pesanan";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MPesanan->GetDataInstansi();
		$data['Barang'] = $this->MPesanan->GetBarang();
		$data['TotalSurat'] = $this->MPesanan->GetTotalSP();
		$data['PBF'] = $this->MPesanan->GetPBF();
		$data['DetBarang'] = $this->MPesanan->GetBarangDetail($id);
		$data['Subtotal'] = $this->MPesanan->GetSubTotal($id);
		$data['Data'] = $this->MPesanan->GetSuratPesananDet($id);
		$this->load->view('Gudang/V_Print',$data);
	}

	public function tambah_data() {
		$x = $this->MPesanan->SaveData();
		redirect('pesanan/buat-surat-pesanan/'.$x);
	}

	public function tambah_barang($id) {
		$x = $this->MPesanan->AddBrg($id);
		//redirect('pesanan/buat-surat-pesanan/'.$x);
		return TRUE;
	}

	public function del_data($id) {
		$x = $this->MPesanan->DelData($id);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}