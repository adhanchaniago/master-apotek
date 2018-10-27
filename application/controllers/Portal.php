<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class Portal extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$isLogin = $this->session->userdata('isLogin');
		if($isLogin) {
			redirect('dashboard','refresh');
		} else {
			$this->load->model('User/M_Login','m');
		}
	}

	public function index() {
		$data['Title'] = 'Portal';
		$data['Instansi'] = $this->m->get_instansi();
		$this->load->view('V_Login',$data);
	}

	public function proses_login() {
		if ($res = $this->m->ceklog()) {
			
			$data = $this->m->datauser();
			
			$newdata = array(
				'kode' => $data->id_user,
				'nama' => $data->nm_user,
				'user' => $data->login_user,
				'level' => $data->nm_level,
				'created' => $data->created_date,
				'isLogin' => TRUE
			);
			
			$this->session->set_userdata($newdata);
			
			$this->m->setLog();
			
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