<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Portal extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPortal');
		if($this->session->userdata('isLogin')!=NULL) :
			if($this->session->userdata('level')=="IT Support") {
				redirect('dashboard','refresh');
			} elseif($this->session->userdata('level')=="Owner") {
				redirect('dashboard/owner');
			} elseif($this->session->userdata('level')=="Farmasi") {
				redirect('dashboard/farmasi');
			} elseif($this->session->userdata('level')=="keuangan") {
				redirect('dashboard/keuangan');
			} elseif($this->session->userdata('level')=="Kasir") {
				redirect('dashboard/kasir');
			}
		endif;
	}

	public function index() {
		$data['Title'] = 'Portal';
		$this->load->view('Login',$data);
	}

	public function proses_login() {
		$u = $this->input->post('usr');
		$p = $this->input->post('pwd');
		if ($res = $this->MPortal->ceklog($u,$p)) {
			$x = $this->MPortal->datauser($u);
			foreach ($x->result() as $x) :
				$newdata = array(
													'kode' => $x->id_user,
													'nama' => $x->nm_user,
													'user' => $x->username,
													'level' => $x->nm_level,
													'status' => $x->status_user,
													'created' => $x->created_date,
													'isLogin' => TRUE
												);
				$this->session->set_userdata($newdata);
				$this->MPortal->setLog($this->session->userdata('kode'));
				if($this->session->userdata('status')=="Aktif") {
					if($this->session->userdata('level')=="IT Support") {
						redirect('dashboard','refresh');
					} elseif($this->session->userdata('level')=="Owner") {
						redirect('dashboard/owner');
					} elseif($this->session->userdata('level')=="Farmasi") {
						redirect('dashboard/farmasi');
					} elseif($this->session->userdata('level')=="keuangan") {
						redirect('dashboard/keuangan');
					} elseif($this->session->userdata('level')=="Kasir") {
						redirect('dashboard/kasir');
					}
				} else {
					$this->session->set_flashdata('item', 'danger-Akun anda ditangguhkan, harap hubungi administrator.');
					redirect('portal');
				}
			endforeach;
		} else { 
			$this->session->set_flashdata('item', 'danger-Username atau Password tidak cocok.');
					redirect('portal');
		}
	}

}