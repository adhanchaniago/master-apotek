<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Margin extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MMargin');
	}

	public function index() {
		$data['Title'] = "Index Margin";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		//======== Pagination Start =========
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->MMargin->GetTotalData();

		if ($total_records > 0) 
		{
				// get current page records
			$data['Data'] = $this->MMargin->GetData($limit_per_page, $start_index);
				 
				$config['base_url'] = base_url() . 'margin/index';
				$config['total_rows'] = $total_records;
				$config['per_page'] = $limit_per_page;
				$config["uri_segment"] = 3;
				 
				$this->pagination->initialize($config);
				$data["links"] = $this->pagination->create_links();
		}
		//============== END of Pagination =============
		$data['Konten'] = 'Admin/V_Margin';
		$this->load->view('Master',$data);
	}

	public function tambah_data() {
		$x = $this->MMargin->SaveData();
		redirect('margin','refresh');
	}

	public function edit_data($id) {
		$x = $this->MMargin->EditData($id);
		redirect('margin','refresh');
	}

	public function del_data($id) {
		$x = $this->MMargin->DelData($id);
		redirect('margin','refresh');
	}

}