<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
		$this->load->model('MBarang');
		$this->load->model('MPembelian');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		$level = $this->session->userdata('level');
		if($level=="Master" OR $level=="Pemilik") {
			redirect('dashboard/administrator');
		} elseif($level=="Apoteker") {
			redirect('dashboard/apoteker');
		} else {
			redirect('dashboard/kasir');
		}
	}

	public function administrator() {
		$data['Title'] = "Dashboard Administrator";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Statistik'] = $this->MPenjualan->get_statistik();
		$data['Limit'] = $this->MBarang->total_limit();
		$data['Barang'] = $this->MBarang->total_barang();
		$data['Kadaluarsa'] = $this->MBarang->total_kadaluarsa();
		$data['Detail'] = $this->MBarang->total_barang_detail();
		$data['Hutang'] = $this->MPembelian->total_hutang();
		$data['Pembelian'] = $this->MPembelian->total_pembelian();
		$data['Piutang'] = $this->MPenjualan->total_piutang();
		$data['Penjualan'] = $this->MPenjualan->total_penjualan();
		$data['Pendapatan'] = $this->MPenjualan->total_pendapatan();
		$data['Pengeluaran'] = $this->MPembelian->total_pengeluaran();
		$data['Hutangs'] = $this->MPembelian->total_hutangs();
		$data['Konten'] = 'Dashboard/V_Dashboard_Admin';
		$this->load->view('Master',$data);
	}

	public function apoteker() {
		$data['Title'] = "Dashboard Apoteker";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Dashboard/V_Dashboard_Apoteker';
		$this->load->view('Master',$data);
	}

	public function kasir() {
		$data['Title'] = "Dashboard Kasir";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Dashboard/V_Dashboard_Kasir';
		$this->load->view('Master',$data);
	}

	public function gudang() {
		$data['Title'] = "Dashboard Gudang";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Dashboard/V_Dashboard_Gudang';
		$this->load->view('Master',$data);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('portal','refresh');
	}

}