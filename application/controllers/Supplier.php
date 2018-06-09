<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Supplier extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MSupplier');
		$isLogin = $this->session->userdata('isLogin');
		if(!$isLogin) {
			redirect('portal','refresh');
		}
	}

	public function index() {
		$level = $this->session->userdata('level');
		if($level=="Master" OR $level=="Pemilik") {
			redirect('dashboard/administrator');
		} elseif($level=="Gudang") {
			redirect('dashboard/gudang');
		} else {
			redirect('dashboard/kasir');
		}
	}

	public function list_all_data() {
		$list = $this->MSupplier->GetAll();
		$datatb= array();
		$nomor = 1;
		foreach ($list as $data) {
			$row = array();
			$row[] = $nomor++;
			$row[] = '<a id="getdata" data="'.$data->id_supplier.'" style="cursor:pointer">'.$data->id_supplier.'</a>';
			$row[] = $data->nm_supplier;
			$row[] = $data->alamat_supplier;
			$row[] = $data->kota_supplier;
			$row[] = $data->kontak_kantor_supplier;
			$row[] = $data->kontak_person_supplier;

			$datatb[] = $row;
		}

		$output = array(
						"draw" => $this->input->post('draw'),
						"data" => $datatb
					);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->MSupplier->GetSingle();
		$data = array(
						'id_supplier' => $res->id_supplier,
						'nm_supplier' => $res->nm_supplier,
						'alamat_supplier' => $res->alamat_supplier,
						'kota_supplier' => $res->kota_supplier,
						'kontak_kantor_supplier' => $res->kontak_kantor_supplier,
						'kontak_person_supplier' => $res->kontak_person_supplier
					);
		echo json_encode($data);
	}

	public function get_option() {
		$res = $this->MSupplier->GetOption();
		echo json_encode($res);
	}

	public function tambah_data() {
		$res = $this->MSupplier->SaveData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

	public function hapus_data() {
		$res = $this->MSupplier->DelData();
		$data = array(
						'status' => $res
					);
		echo json_encode($data);
	}

}