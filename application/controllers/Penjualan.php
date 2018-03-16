<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Penjualan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('MPenjualan');
		$this->load->model('MBarang');
	}

	public function GetData($id=null) {
		$data = $this->MPenjualan->GetData($id);
		echo
			'{
				"data": [';
				foreach($data->result() as $d) :
					echo'	[
								"'.$d->nm_barang.'",
								"Rp. '.number_format($d->harga_dasar+$d->margin,2,",",".").'",
								"'.$d->qty.' '.$d->nm_satuan.'",
								"'.$d->etiket.'",
								"'.$d->diskon.'",
								"Rp. '.number_format($d->total,2,",",".").'",
								"'.$d->id_resep_detail.'"
							],';
				endforeach;
					echo'	[
								"",
								"",
								"",
								"",
								"",
								"",
								""
							]';
		echo'	]
			}';
	}

	public function GetDataPJ($id=null) {
		$data = $this->MPenjualan->GetDataPJ($id);
		echo
			'{
				"data": [';
				foreach($data->result() as $d) :
					echo'	[
								"'.$d->nm_barang.'",
								"Rp. '.number_format($d->harga_dasar+$d->margin,2,",",".").'",
								"'.$d->qty.' '.$d->nm_satuan.'",
								"'.$d->diskon.'",
								"Rp. '.number_format($d->total,2,",",".").'",
								"'.$d->id_penjualan_barang_detail.'"
							],';
				endforeach;
					echo'	[
								"",
								"",
								"",
								"",
								"",
								""
							]';
		echo'	]
			}';
	}

	public function pj_resep($id=null) {
		$data['Title'] = "Penjualan Resep";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Barang'] = $this->MBarang->GetBarang();
		$data['TotalTransaksi'] = $this->MPenjualan->GetTotalTransaksi();
		$data['Data'] = $this->MPenjualan->GetDataPasien($id);
		$data['Subtotal'] = $this->MPenjualan->GetFixSubTotal($id);
		$data['Konten'] = 'Farmasi/V_Penjualan_Obat';
		$this->load->view('Master',$data);
	}

	public function ajax_list($id=null) {
		$list = $this->MPenjualan->GetData($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list->result() as $data) {
			$no++;
			$row = array();
			$row[] = $data->nm_barang;
			$row[] = 'Rp. '.number_format($data->harga_dasar+$data->margin,2,",",".").'- ,';
			$row[] = $data->qty.' '.$data->nm_satuan;
			$row[] = $data->etiket;
			$row[] = $data->diskon;
			$row[] = 'Rp. '.number_format($data->total,2,",",".").'-,';
			$row[] = 'Rp. '.number_format($data->harga_dasar,2,",",".");
			$row[] = $data->id_resep_detail;

			$datatb[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"recordsTotal" => $this->MPenjualan->GetData($id)->num_rows(),
										"data" => $datatb,
									 );
		echo json_encode($output);
	}

	public function data_pj_non_resep($id=null) {
		$list = $this->MPenjualan->GetDataPJ($id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list->result() as $data) {
			$no++;
			$row = array();
			$row[] = '<a id="'.$data->id_penjualan_bebas_detail.'" href="#">'.$data->nm_barang.'</a>';
			$row[] = 'Rp. '.number_format($data->harga_dasar+$data->margin,2,",",".").'- ,';
			$row[] = $data->qty_penjualan_bebas.' '.$data->nm_satuan;
			$row[] = $data->diskon_penjualan_bebas;
			$row[] = 'Rp. '.number_format($data->total_penjualan_bebas,2,",",".").'-,';

			$datatb[] = $row;
		}

		$output = array(
										"draw" => $_POST['draw'],
										"recordsTotal" => $this->MPenjualan->GetDataPJ($id)->num_rows(),
										"data" => $datatb,
									 );
		echo json_encode($output);
	}

	public function pj_non_resep($id=null) {
		$data['Title'] = "Penjualan Bebas";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Barang'] = $this->MPenjualan->GetBarang();
		$data['TotalTransaksi'] = $this->MPenjualan->GetTotalTransaksi();
		//$data['Data'] = $this->MPenjualan->GetData($id);
		$data['Subtotal'] = $this->MPenjualan->GetSubTotalPJ($id);
		$data['Konten'] = 'Farmasi/V_Penjualan_Bebas';
		$this->load->view('Master',$data);
	}

	public function prints($id=null) {
		$data['Title'] = "Buat Surat Pesanan";
		$data['Nav'] = "Pembelian";
		$data['Nama'] = $this->session->userdata('nama');
		$data['Level'] = $this->session->userdata('level');
		$data['Instansi'] = $this->MAdmin->GetInstansi();
		$data['Barang'] = $this->MAdmin->GetBarang();
		$data['TotalSurat'] = $this->MAdmin->GetTotalSP();
		$data['PBF'] = $this->MAdmin->GetPBF();
		$data['DetBarang'] = $this->MAdmin->GetBarangDetail($id);
		$data['Subtotal'] = $this->MAdmin->GetTotal($id);
		$data['Data'] = $this->MAdmin->GetSuratPesananDet($id);
		$this->load->view('Gudang/V_Print',$data);
	}

	public function simpan_data_resep() {
		$x = $this->MPenjualan->SaveDataResep();
		redirect('penjualan/pj-resep/'.$x);
	}

	public function tambah_data($id) {
		$x = $this->MPenjualan->SaveData($id);
		//echo json_encode($x);
	}

	public function tambah_data_pj() {
		$x = $this->MPenjualan->SaveDataPJ();
		return TRUE;
		//redirect('penjualan/pj-non-resep/'.$x);
	}

	public function edit_data($id) {
		$x = $this->MPenjualan->EditData($id);
		$this->session->set_flashdata($x,'item');
		$this->session->keep_flashdata('item');
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	public function simpan_tr($id) {
		$x = $this->MPenjualan->SimpanTR($id);
		$data = array(
			'bayar' => $x
		);
		echo json_encode($data);
	}

	public function del_data($id_tr,$id_brg) {
		$x = $this->MPenjualan->DelData($id_tr,$id_brg);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	public function tes() {
		echo $this->MPenjualan->pembulatan('1527777.00');
	}

}