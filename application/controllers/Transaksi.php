<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
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

	public function pemesanan() {
		$data['Title'] = "Pemesanan";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Pemesanan';
		$this->load->view('Master',$data);
	}

	public function pembelian() {
		$data['Title'] = "Pembelian";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Pembelian';
		$this->load->view('Master',$data);
	}

	public function pj_bebas() {
		$data['Title'] = "Penjualan Bebas";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Penjualan_Bebas';
		$this->load->view('Master',$data);
	}

	public function pj_resep() {
		$data['Title'] = "Penjualan Resep";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Penjualan_Resep';
		$this->load->view('Master',$data);
	}

	public function retur_obat() {
		$data['Title'] = "Retur Obat";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Retur_Obat';
		$this->load->view('Master',$data);
	}

	public function penyesuaian_stok() {
		$data['Title'] = "Penyesuaian Stok";
		$data['Nav'] = "Transaksi";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Transaksi/V_Penyesuaian_Stok';
		$this->load->view('Master',$data);
	}

}