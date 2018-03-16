<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Retur_pbf extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MAdmin');
		$this->load->model('MFarmasi');
		$this->load->model('MGudang');
		$this->load->model('MKasir');
		$this->load->model('MKeuangan');
	}

	public function index() {
		$data['Title'] = "Retur Barang ke PBF";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MAdmin->GetUsers();
		$data['Konten'] = 'Gudang/V_Retur_Barang';
		$this->load->view('Master',$data);
	}

}