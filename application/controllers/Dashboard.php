<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MAdmin');
		$this->load->model('MUser');
		if($this->session->userdata('isLogin')==NULL) :
			redirect('portal','refresh');
		endif;
	}

	public function index() {
		$data['Title'] = "Dashboard";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MUser->GetData(0,0);
		$data['Konten'] = 'Admin/V_Dashboard';
		$this->load->view('Master',$data);
	}

	public function owner() {
		$data['Title'] = "Dashboard";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MUser->GetData();
		$data['Konten'] = 'Admin/V_Admin';
		$this->load->view('Master',$data);
	}

	public function farmasi() {
		$data['Title'] = "Dashboard";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MUser->GetData();
		$data['Konten'] = 'Admin/V_Admin';
		$this->load->view('Master',$data);
	}

	public function kasir() {
		$data['Title'] = "Dashboard";
		$data['Nav'] = "Dashboard";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Users'] = $this->MUser->GetData();
		$data['Konten'] = 'Admin/V_Admin';
		$this->load->view('Master',$data);
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('portal','refresh');
	}

}