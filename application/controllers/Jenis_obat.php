<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis_obat extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MJenis');
		if($this->session->userdata('isLogin')==NULL) :
			redirect('portal','refresh');
		endif;
	}

	public function index() {
		$data['Title'] = "Jenis Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		//======== Pagination Start =========
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->MJenis->GetTotalData();

		if ($total_records > 0) 
		{
			// get current page records
			$data["Data"] = $this->MJenis->GetData($limit_per_page, $start_index);
			
			$config['base_url'] = base_url() . 'jenis-obat/index';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;
			$config["uri_segment"] = 3;
			
			$this->pagination->initialize($config);
			$data["links"] = $this->pagination->create_links();
		}
		//============== END of Pagination =============
		$data['Konten'] = 'Farmasi/V_Jenis_Obat';
		$this->load->view('Master',$data);
	}

	public function tambah_data() {
		$x = $this->MJenis->SaveData();
		redirect('jenis-obat','refresh');
	}

	public function edit_data($id) {
		$x = $this->MJenis->EditData($id);
		redirect('jenis-obat','refresh');
	}

	public function del_data($id) {
		$x = $this->MJenis->DelData($id);
		redirect('jenis-obat','refresh');	
	}

}