<?php defined('BASEPATH') OR edatait('No direct script access allowed');
class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MUser');
		$this->load->model('MLevel');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		$data['Title'] = "Data User";
		$data['Nav'] = "User";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['ListLv'] = $this->MLevel->GetAll();
		$data['Konten'] = "Administrator/User/V_User";
		$this->load->view('Master',$data);
	}

	public function list_data_user() {
		$list = $this->MUser->GetAll();
		$datatb = array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" user="'.$data->id_user.'" style="cursor:pointer">'.$data->nm_user.'</a>';
			$row[] = $data->nm_level;
			$row[] = $data->created_date;
			$row[] = $data->login_terakhir;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					 );
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MUser->get_single();
		$data = array(
						'id_user' => $res->id_user,
						'nm_user' => $res->nm_user,
						'login_user' => $res->login_user,
						'pass_user' => $res->pass_user,
						'level_user' => $res->level_user,
					);
		echo json_encode($data);
	}

	public function tambah_user() {
		$res = $this->MUser->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function edit_user() {
		$res = $this->MUser->EditData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function del_data() {
		$res = $this->MUser->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function verif_user() {
		$res = $this->MUser->verif_user();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function unlock() {
		$res = $this->MUser->unlock();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}


}