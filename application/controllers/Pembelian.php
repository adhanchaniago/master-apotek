<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pembelian extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPembelian');
		$this->load->model('MPabrik');
	}

	public function ajax_list($id) {
		$list = $this->MPembelian->GetDetailFaktur($id)->result();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $d) {
			$no++;
			$row = array();
			$row[] = $d->id_pembelian_detail;
			$row[] = $d->nm_barang;
			$row[] = $d->qty_fix;
			$row[] = $d->batch;
			$row[] = $d->kadaluarsa;
			$row[] = 'Rp. '.number_format($d->harga_dasar,2,",",".");
			$row[] = $d->diskon;
			$row[] = 'Rp. '.number_format($d->subtotal_barang,2,",",".");

			$data[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"recordsTotal" => $this->MPembelian->GetDetailFaktur($id)->num_rows(),
										"data" => $data,
									 );
		echo json_encode($output);
	}

	public function index() {
		$data['Title'] = "Surat Faktur";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['TotalSurat'] = $this->MPembelian->GetTotalFaktur();
		$data['Data'] = $this->MPembelian->GetFakturDet($id);
		$data['PBF'] = $this->MPembelian->GetPBF();
		$data['Pabrik'] = $this->MPabrik->GetData();
		$data['Barang'] = $this->MPembelian->GetBarang();
		$data['Konten'] = 'Gudang/V_Surat_Faktur';
		$this->load->view('Master',$data);
	}

	public function faktur($id) {
		$data['Title'] = "Surat Faktur";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Data'] = $this->MPembelian->GetFaktur($id);
		$data['PBF'] = $this->MPembelian->GetPBF();
		$data['Pabrik'] = $this->MPabrik->GetData();
		$data['Barang'] = $this->MPembelian->GetBarang();
		$data['Konten'] = 'Gudang/V_Surat_Faktur';
		$this->load->view('Master',$data);
	}

	public function generate_faktur() {
		$x = $this->MPembelian->GenFaktur();
		redirect('pembelian/faktur/'.$x);
	}

	public function add_items($id) {
		$x = $this->MPembelian->SaveItems($id);
		redirect('pembelian/faktur/'.$x);
	}

	public function checkout($id=null) {
		$data['Title'] = "Buat Surat Faktur Pembelian";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MPembelian->GetInstansi();
		$data['TotalSurat'] = $this->MPembelian->GetTotalFaktur();
		$data['DetBarang'] = $this->MPembelian->GetBarangDetail($id);
		$data['Subtotal'] = $this->MPembelian->GetSubTotal($id);
		$data['Data'] = $this->MPembelian->GetFakturDet($id);
		$data['Konten'] = 'Gudang/V_Buat_Surat_Faktur';
		$this->load->view('Master',$data);
	}

	public function prints($id=null) {
		$data['Title'] = "Buat Surat Pesanan";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MPembelian->GetInstansi();
		$data['Barang'] = $this->MPembelian->GetBarang();
		$data['TotalSurat'] = $this->MPembelian->GetTotalFaktur();
		$data['PBF'] = $this->MPembelian->GetPBF();
		$data['DetBarang'] = $this->MPembelian->GetBarangDetail($id);
		$data['Subtotal'] = $this->MPembelian->GetSubTotal($id);
		$data['Data'] = $this->MPembelian->GetFakturDet($id);
		$this->load->view('Gudang/V_Print',$data);
	}

	public function tambah_data() {
		$x = $this->MPembelian->SaveData();
		redirect('pembelian/checkout/'.$x);
	}

	public function simpan_pembelian() {
		$x = $this->MPembelian->SimpanData();
		redirect('pembelian/checkout/'.$x);
	}

	public function edit_data($id) {
		$x = $this->MPembelian->EditData($id);
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	public function del_data($id) {
		$x = $this->MPembelian->DelData($id);
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

}