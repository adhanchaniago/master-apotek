<?php defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MUser');
	}

	public function index() {
		$data['Title'] = "Data User";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Hak'] = $this->MUser->GetLevel();
		//======== Pagination Start =========
		$limit_per_page = 5;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->MUser->GetTotalData();

		if ($total_records > 0) 
		{
				// get current page records
				$data["Data"] = $this->MUser->GetData($limit_per_page, $start_index);
				
				$config['base_url'] = base_url() . 'user/index';
				$config['total_rows'] = $total_records;
				$config['per_page'] = $limit_per_page;
				$config["uri_segment"] = 3;
				 
				$this->pagination->initialize($config);
				$data["links"] = $this->pagination->create_links();
		}
		//============== END of Pagination =============
		$data['Konten'] = 'Admin/V_Users';
		$this->load->view('Master',$data);
	}

	public function tambah_data() {
		$x = $this->MUser->SaveData();
		redirect('user','refresh');
	}

	public function edit_data($id) {
		$x = $this->MUser->EditData($id);
		redirect('user','refresh');
	}

	public function del_data($id) {
		$x = $this->MUser->DelData($id);
		redirect('user','refresh');
	}

}