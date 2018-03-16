<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Instansi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MAdmin');
		$this->load->model('MFarmasi');
		$this->load->model('MGudang');
		$this->load->model('MKasir');
		$this->load->model('MKeuangan');
	}

	public function index() {
		$data['Title'] = "Data Instansi";
		$data['Nav'] = "Sistem";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MAdmin->GetUsers();
		$data['Konten'] = 'Admin/V_Instansi';
		$this->load->view('Master',$data);
	}

	public function instansi_update() {
		$res = $this->MAdmin->SaveInstansi();
		$this->session->set_flashdata('item',$res);
		$this->session->keep_flashdata('item');
		redirect('administrator/instansi','refresh');
	}

}