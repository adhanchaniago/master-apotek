<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {

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

	public function administrator() {
		$data['Title'] = "Dashboard Administrator";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Dashboard/V_Dashboard_Admin';
		$this->load->view('Master',$data);
	}

	public function apoteker() {
		$data['Title'] = "Dashboard Apoteker";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Subtotal'] = $this->MPenjualan->GetSubtotalPj();
		$data['Konten'] = 'Dashboard/V_Dashboard_Apoteker';
		$this->load->view('Master',$data);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('portal','refresh');
	}

}