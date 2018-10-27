<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		} else {
			$this->load->model('Laporan/M_Barang','b');
			$this->load->model('Laporan/M_Pengeluaran','o');
			$this->load->model('Laporan/M_Pemasukan','i');
			$this->load->model('Laporan/M_Statistik','m');
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
		$data['Konten'] = 'Dashboard/V_Dashboard_Admin';
		
		$data['Limit'] = $this->b->total_limit();
		$data['Barang'] = $this->b->total_barang();
		$data['Kadaluarsa'] = $this->b->total_kadaluarsa();
		$data['Detail'] = $this->b->total_barang_detail();

		$data['Hutang'] = $this->o->total_hutang();
		$data['Pembelian'] = $this->o->total_pembelian();
		$data['Pengeluaran'] = $this->o->total_pengeluaran();

		$data['Piutang'] = $this->i->total_piutang();
		$data['Penjualan'] = $this->i->total_penjualan();
		$data['Pendapatan'] = $this->i->total_pendapatan();
		//$data['Hutangs'] = $this->m->total_hutangs();
		
		$data['Statistik'] = $this->m->get_statistik();
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