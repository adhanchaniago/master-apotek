<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Barang extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MFarmasi');
		$this->load->model('MGudang');
		$this->load->model('MPabrik');
		$this->load->model('MKemasan');
	}

	public function index() {
		$data['Title'] = "Barang";
		$data['Nav'] = "Master Data";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Pabrik'] = $this->MPabrik->Getdata(NULL,NULL);
		$data['Kemasan'] = $this->MKemasan->Getdata(NULL,NULL);
		$data['Satuan'] = $this->MFarmasi->GetSatuan();
		$data['Jenis'] = $this->MFarmasi->GetGolObat();
		$data['Konten'] = 'Gudang/V_Barang';
		$this->load->view('Master',$data);
	}

	public function ajax_list() {
		$list = $this->MGudang->GetBarang();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$row = array();
			$row[] = $d->id_barang;
			$row[] = $d->nm_barang;
			$row[] = $d->nm_jenis;
			$row[] = $d->nm_pabrik;
			$row[] = $d->golongan_obat;
			$row[] = $d->isi_satuan.' '.$d->nm_satuan;
			$row[] = 'Rp. '.number_format($d->harga_dasar,2,",",".");

			$data[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"recordsTotal" => $this->MGudang->GetTotalBarang(),
										"data" => $data,
									 );
		echo json_encode($output);
	}

	public function tambah_data() {
		$x = $this->MGudang->SaveData();
		redirect('barang','refresh');
	}

	public function edit_data($id) {
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Data'] = $this->MGudang->GetSingleData($id);
		$data['Pabrik'] = $this->MPabrik->Getdata(NULL,NULL);
		$data['Kemasan'] = $this->MKemasan->Getdata(NULL,NULL);
		$data['Satuan'] = $this->MFarmasi->GetSatuan();
		$data['Jenis'] = $this->MFarmasi->GetGolObat();
		$this->load->view('Gudang/Barang/V_Edit_Barang',$data);
	}

	public function proses_edit_data($id) {
		$x = $this->MGudang->EditData($id);
		echo "<script>window.close();</script>";
	}

	public function del_data($id) {
		$x = $this->MGudang->DelData($id);
		redirect('barang','refresh');
	}

}