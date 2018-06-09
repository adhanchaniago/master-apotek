<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Setup extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MSetup');
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

	public function list_data_instansi() {
		$list = $this->MSetup->GetAll();
		$datatb= array();
		foreach ($list as $data) {
			$row = array();
			$row[] = '<a id="getdata" data="'.$data->id_instansi.'" style="cursor:pointer">'.$data->nm_instansi.'</a>';
			$row[] = $data->alamat_instansi;
			$row[] = $data->kontak_instansi;
			$row[] = $data->tuslah_racik;
			$row[] = $data->emblase_racik;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MSetup->GetSingle();
		$data = array(
						'id_instansi' => $res->id_instansi,
						'nm_instansi' => $res->nm_instansi,
						'alamat_instansi' => $res->alamat_instansi,
						'kontak_instansi' => $res->kontak_instansi,
						'tuslah_racik' => $res->tuslah_racik,
						'emblase_racik' => $res->emblase_racik
					);
		echo json_encode($data);
	}

	public function update_data() {
		$res = $this->MSetup->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}