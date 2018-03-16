<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pabrik extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPabrik');
		if($this->session->userdata('isLogin')==NULL) :
			redirect('portal','refresh');
		endif;
	}

	public function index() {
		$data['Title'] = "Pabrik Obat";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Konten'] = 'Farmasi/V_Pabrik';
		$this->load->view('Master',$data);
	}

	public function GetData($id=null) {
		$data = $this->MPabrik->GetData();
		echo
			'{
				"data": [';
				foreach($data as $d) :
					echo'	[
								"'.$d->id_pabrik.'",
								"'.$d->nm_pabrik.'"
							],';
				endforeach;
					echo'	[
								"-",
								"-"
							]';
		echo'	]
			}';
	}

	public function tambah_data() {
		$data['Konten'] = 'Farmasi/V_Pabrik';
		$this->load->view('Farmasi/V_Pabrik',$data);
	}

	public function edit_data($id) {
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Data'] = $this->MPabrik->GetSingleData($id);
		$this->load->view('Farmasi/V_Edit_Pabrik',$data);
	}

	public function proses_edit_data($id) {
		$x = $this->MPabrik->EditData($id);
		echo "<script>window.close();</script>";
	}

	public function del_data($id) {
		$x = $this->MPabrik->DelData($id);
		redirect('pabrik','refresh');	
	}

}