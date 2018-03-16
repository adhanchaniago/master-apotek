<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Satuan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MSatuan');
		if($this->session->userdata('isLogin')==NULL) :
			redirect('portal','refresh');
		endif;
	}

	public function index() {
		$data['Title'] = "Satuan Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		//======== Pagination Start =========
		$limit_per_page = 10;
		$start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$total_records = $this->MSatuan->GetTotalData();

		if ($total_records > 0) 
		{
			// get current page records
			$data["Data"] = $this->MSatuan->GetData($limit_per_page, $start_index);
			
			$config['base_url'] = base_url() . 'satuan/index';
			$config['total_rows'] = $total_records;
			$config['per_page'] = $limit_per_page;
			$config["uri_segment"] = 3;
			
			$this->pagination->initialize($config);
			$data["links"] = $this->pagination->create_links();
		}
		//============== END of Pagination =============
		$data['Konten'] = 'Farmasi/V_Satuan_Barang';
		$this->load->view('Master',$data);
	}

	public function tambah_data() {
		$x = $this->MSatuan->SaveData();
		redirect('satuan','refresh');
	}

	public function edit_data($id) {
		$x = $this->MSatuan->EditData($id);
		redirect('satuan','refresh');
	}

	public function del_data($id) {
		$x = $this->MSatuan->DelData($id);
		redirect('satuan','refresh');	
	}

}