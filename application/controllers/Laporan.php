<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
		$this->load->model('MLPembelian');
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

	public function pembelian_barang_pabrik() {
		$data['Title'] = "Pembelian Barang Pabrik";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Pembelian_Barang_Pabrik';
		$this->load->view('Master',$data);
	}

	public function pembelian_surat_pesanan() {
		$data['Title'] = "Penerimaan Barang";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Pembelian_Surat_Pesanan';
		$this->load->view('Master',$data);
	}

	public function penerimaan_barang() {
		$data['Title'] = "Penerimaan Barang";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Penerimaan_Barang';
		$this->load->view('Master',$data);
	}

	/*public function stok_limit() {
		$data['Title'] = "Stok Limit";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Stok_Limit';
		$this->load->view('Master',$data);
	}*/

	public function stok_inhand() {
		$data['Title'] = "Stok Inhand";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Stok_Inhand';
		$this->load->view('Master',$data);
	}

	public function stok_opname() {
		$data['Title'] = "Stok Opname";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Stok_Opname';
		$this->load->view('Master',$data);
	}

	public function piutang() {
		$data['Title'] = "Piutang";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Piutang';
		$this->load->view('Master',$data);
	}

	public function hutang() {
		$data['Title'] = "Hutang";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Hutang';
		$this->load->view('Master',$data);
	}

	public function cashflow() {
		$data['Title'] = "Cashflow";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Cashflow';
		$this->load->view('Master',$data);
	}

	public function laba_rugi() {
		$data['Title'] = "Laba Rugi";
		$data['Nav'] = "Laporan";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Laporan/V_Laba_Rugi';
		$this->load->view('Master',$data);
	}

}