<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Setup extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		$data['Title'] = "Konfigurasi";
		$data['Nav'] = "Setup";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Setup/V_Setup';
		$this->load->view('Master',$data);
	}

}