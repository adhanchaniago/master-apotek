<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Penyesuaian extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$Data['Title'] = "Penyesuaian Stok";
		$Data['Nav'] = "Pembelian";
		$Data['Nama'] = $this->session->userdata('nama');
		$Data['Level'] = $this->session->userdata('level');
		$Data['Konten'] = 'Penyesuaian/V_Penyesuaian';
		$this->load->view('Master',$Data);
	}

}