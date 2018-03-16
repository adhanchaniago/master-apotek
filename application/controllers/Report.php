<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
	}

	public function ajax_list() {
		$list = $this->MPenjualan->GetReportPJR();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $d->tanggal;
			$row[] = $d->nm_pasien;
			$row[] = $d->nomor_resep;
			$row[] = $d->nm_dokter;
			$row[] = $d->subtotal;
			$row[] = '';

			$data[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"data" => $data,
									 );
		echo json_encode($output);
	}

	public function penjualan_resep() {
		$Data['Title'] = "Penjualan Resep";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Penjualan_Resep';
		$this->load->view('Master',$Data);
	}

	public function penjualan_non_resep() {
		$Data['Title'] = "Penjualan Non Resep";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Penjualan_Non_Resep';
		$this->load->view('Master',$Data);
	}

	public function penjualan_alkes() {
		$Data['Title'] = "Penjualan Alkes";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Penjualan_Alkes';
		$this->load->view('Master',$Data);
	}

	public function penjualan_obat() {
		$Data['Title'] = "Penjualan Obat";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Penjualan_Obat';
		$this->load->view('Master',$Data);
	}

	public function rekap_penjualan() {
		$Data['Title'] = "Rekap Penjualan";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Rekap_Penjualan';
		$this->load->view('Master',$Data);
	}

	public function pembelian() {
		$Data['Title'] = "Report Pembelian";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Pembelian';
		$this->load->view('Master',$Data);
	}

	public function stok() {
		$Data['Title'] = "Stok";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Opname';
		$this->load->view('Master',$Data);
	}

	public function penyesuaian() {
		$Data['Title'] = "Penyesuaian";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Inhand';
		$this->load->view('Master',$Data);
	}

	public function piutang() {
		$Data['Title'] = "Piutang";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Piutang';
		$this->load->view('Master',$Data);
	}

	public function piutang_jatuh_tempo() {
		$Data['Title'] = "Piutang Jatuh Tempo";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Piutang_Jatuh_Tempo';
		$this->load->view('Master',$Data);
	}

	public function hutang() {
		$Data['Title'] = "Hutang";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Hutang';
		$this->load->view('Master',$Data);
	}

	public function hutang_jatuh_tempo() {
		$Data['Title'] = "Hutang Jatuh Tempo";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Hutang_Jatuh_Tempo';
		$this->load->view('Master',$Data);
	}

	public function retur_pasien() {
		$Data['Title'] = "Retur Pasien";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Retur_Pasien';
		$this->load->view('Master',$Data);
	}

	public function retur_pbf() {
		$Data['Title'] = "Retur PBF";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Retur_Pbf';
		$this->load->view('Master',$Data);
	}

	public function stok_limit() {
		$Data['Title'] = "Stok Limit";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Stok_Limit';
		$this->load->view('Master',$Data);
	}

	public function penerimaan_barang() {
		$Data['Title'] = "Penerimaan Barang";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Penerimaan_barang';
		$this->load->view('Master',$Data);
	}

	public function near_expire() {
		$Data['Title'] = "Near Expire";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Near_Expire';
		$this->load->view('Master',$Data);
	}	

	public function expire() {
		$Data['Title'] = "Expire";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Expire';
		$this->load->view('Master',$Data);
	}

	public function konsinyasi() {
		$Data['Title'] = "Konsinyasi";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Konsinyasi';
		$this->load->view('Master',$Data);
	}

	public function barang_hilang() {
		$Data['Title'] = "Barang Hilang";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/V_Barang_Hilang';
		$this->load->view('Master',$Data);
	}

	public function shu() {
		$Data['Title'] = "Report SHU";
		$Data['Nav'] = "Laporan";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Report/SHU';
		$this->load->view('Master',$Data);
	}

}