<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
		$this->load->model('MLevel');
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

	public function jenis_obat() {
		$data['Title'] = "Jenis Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Jenis_Obat';
		$this->load->view('Master',$data);
	}

	public function kemasan() {
		$data['Title'] = "Kemasan Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Kemasan';
		$this->load->view('Master',$data);
	}

	public function satuan() {
		$data['Title'] = "Satuan Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Satuan';
		$this->load->view('Master',$data);
	}

	public function pabrik() {
		$data['Title'] = "Pabrik Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Pabrik';
		$this->load->view('Master',$data);
	}

	public function pbf() {
		$data['Title'] = "PBF Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_PBF';
		$this->load->view('Master',$data);
	}

	public function margin() {
		$data['Title'] = "Index Margin Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Margin';
		$this->load->view('Master',$data);
	}

	public function barang() {
		$data['Title'] = "Barang";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Master/V_Barang';
		$this->load->view('Master',$data);
	}

	public function karyawan() {
		$data['Title'] = "Karyawan";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['ListLv'] = $this->MLevel->GetAll();
		$data['Konten'] = 'Master/V_Karyawan';
		$this->load->view('Master',$data);
	}

}