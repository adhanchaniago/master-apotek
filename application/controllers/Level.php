<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Level extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MLevel');
	}

	public function index() {
		$data['Title'] = "Data Level";
		$data['Nav'] = "Petugas";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		//======== Pagination Start =========
		$limit_per_page = 5;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->MLevel->GetTotalLevel($keywords);

		if ($total_records > 0) 
		{
				// get current page records
				$data["Data"] = $this->MLevel->GetLevel($limit_per_page, $start_index, $keywords);
				
				$config['base_url'] = base_url() . 'level/index';
				$config['total_rows'] = $total_records;
				$config['per_page'] = $limit_per_page;
				$config["uri_segment"] = 3;
				 
				$this->pagination->initialize($config);
				$data["links"] = $this->pagination->create_links();
		}
		//============== END of Pagination =============
		$data['Konten'] = 'Admin/V_Level';
		$this->load->view('Master',$data);
	}

	public function tambah_data() {
		$x = $this->MLevel->SaveData();
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		redirect('level','refresh');
	}

	public function edit_data($id) {
		$x = $this->MLevel->EditData($id);
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		redirect('level','refresh');
	}

	public function del_data($id) {
		$x = $this->MLevel->DelData($id);
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		redirect('level','refresh');
	}

}