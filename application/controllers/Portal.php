<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class Portal extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MLogin');
		$isLogin = $this->session->userdata('isLogin');
		if($isLogin) {
			redirect('dashboard','refresh');
		}
	}

	public function index() {
		$data['Title'] = 'Portal';
		$data['Instansi'] = $this->MLogin->get_instansi();
		$this->load->view('Login',$data);
	}

	public function proses_login() {
		if ($res = $this->MLogin->ceklog()) {
			$data = $this->MLogin->datauser();
			$newdata = array(
												'kode' => $data->id_user,
												'nama' => $data->nm_user,
												'user' => $data->login_user,
												'level' => $data->nm_level,
												'created' => $data->created_date,
												'isLogin' => TRUE
											);
			$this->session->set_userdata($newdata);
			$this->MLogin->setLog();
			redirect('dashboard','refresh');
		} else { 
			$this->session->set_flashdata(
											'item', 
											'danger-Username atau Password tidak cocok.'
										);
			redirect('portal');
		}
	}

}