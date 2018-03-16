<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPenjualan extends CI_Model {

	protected $table = array(
							'ak_data_penjualan_resep', // 0
							'ak_data_penjualan_resep_detail', //1
							'ak_data_penjualan', //2
							'ak_data_barang', //3
							'ak_data_barang_detail', //4
							'ak_data_satuan', //5
							'ak_data_barang_stok', //6
							'ak_data_margin', //7
							'ak_data_penjualan_bebas_detail' //8
							);

	public function GetBarang() {
		$res = $this->db->where($this->table[3].'.deleted',false)
						->join(
							$this->table[5],
							$this->table[5].'.id_satuan='.$this->table[3].'.id_satuan')
						->get($this->table[3]);
		return $res->result();
	}

	public function GetDataPasien($id) {
		$res = $this->db->where($this->table[0].'.id_penjualan',$id)
						->get($this->table[0]);
		return $res;
	}

	public function GetData($id) {
		$res = $this->db->where($this->table[2].'.id_penjualan',$id)
						->where($this->table[1].'.deleted',false)
						->join(
							$this->table[0],
							$this->table[0].'.id_penjualan='.$this->table[2].'.id_penjualan')
						->join(
							$this->table[1],
							$this->table[1].'.id_resep='.$this->table[0].'.id_resep')
						->join(
							$this->table[4],
							$this->table[4].'.id_barang_detail='.$this->table[1].'.id_barang_detail')
						->join(
							$this->table[3],
							$this->table[3].'.id_barang='.$this->table[4].'.id_barang')
						->join(
							$this->table[5],
							$this->table[5].'.id_satuan='.$this->table[3].'.id_satuan')
						->get($this->table[2]);
		return $res;
	}

	public function GetReportPJR() {
		$res = $this->db->where($this->table[2].'.status',true)
						->join(
							$this->table[0],
							$this->table[0].'.id_penjualan='.$this->table[2].'.id_penjualan')
						->join(
							$this->table[1],
							$this->table[1].'.id_resep='.$this->table[0].'.id_resep')
						->join(
							$this->table[4],
							$this->table[4].'.id_barang_detail='.$this->table[1].'.id_barang_detail')
						->join(
							$this->table[3],
							$this->table[3].'.id_barang='.$this->table[4].'.id_barang')
						->join(
							$this->table[5],
							$this->table[5].'.id_satuan='.$this->table[3].'.id_satuan')
						->get($this->table[2]);
		return $res->result();
	}

	public function GetDataPJ($id) {
		$query = $this->db->query("call ak_procedure_show_brg_pj_bebas('1')");
		return $query;
	}

	public function GetSubTotal($id) {
		$no_resep = $this->GetResepID($id);
		$res = $this->db->select_sum('total')
						->where('id_resep',$no_resep)
						->where($this->table[1].'.deleted',false)
						->get($this->table[1]);
		return $res->row('total');
	}

	public function GetSubTotalPJ($id) {
		$res = $this->db->select_sum('total_penjualan_bebas')
						->where('id_penjualan_bebas',$id)
						->where($this->table[8].'.deleted_penjualan_bebas',false)
						->get($this->table[8]);
		return $res->row('total_penjualan_bebas');
	}

	public function GetSubTotalRsp($id) {
		$res = $this->db->select_sum('total')
						->where('id_resep',$id)
						->where($this->table[1].'.deleted',false)
						->get($this->table[1]);
		return $res->row('total');
	}

	public function GetTotalTransaksi() {
		$res = $this->db->get($this->table[2]);
		return $res->num_rows();
	}

	public function GetTotalResep() {
		$res = $this->db->get($this->table[0]);
		return $res->num_rows();
	}

	public function GetFixSubTotal($id) {
		$res = $this->db->where('id_penjualan',$id)
						->get($this->table[2]);
		return $res->row();
	}

	public function SaveDataResep() {
		$a = $this->input->post('id_penjualan');
		$b = $this->input->post('nama_dokter');
		$c = $this->input->post('nama_pasien');
		$d = $this->input->post('kontak');
		$e = $this->input->post('alamat');
		$no_resep = $this->GetNoResep($a);
		$cek_resep = $this->CekTransacID($a);
		$data1 = array(
						'id_resep' => $no_resep,
						'id_penjualan' => $a,
						'nm_pasien' => $c,
						'nm_dokter' => $b,
						'alamat' => $e,
						'kontak_pasien' => $d,
						'biaya_resep' => 0
					  );
		$data3 = array(
						'id_penjualan' => $a,
						'id_user' => $this->session->userdata('kode'),
						'tanggal' => date('Y-m-d'),
						'pembulatan' => 0,
						'subtotal' => 0
					);
		var_dump($cek_resep);
		if($cek_resep==NULL) {
			$result_3 = $this->db->insert($this->table[2],$data3);
			$result_1 = $this->db->insert($this->table[0],$data1);
		} else {
			$result_1 = $this->db->where('id_resep',$no_resep)->update($this->table[0],$data1);
			$result_3 = $this->db->where('id_penjualan',$a)->update($this->table[2],$data3);
		}
		return $a;
	}

	public function SaveData($id) {
		$a = $id;
		$f = $this->input->post('barang');
		$g = $this->input->post('qty');
		$h = $this->input->post('diskon');
		$i = $this->input->post('etiket');
		$j = $this->input->post('paramedis');
		$no_resep = $this->GetNoResep($a);
		$barang_detail = $this->GetDetBrgID($f);
		$harga_barang = $this->GetHargaBarang($f);
		$stok = $this->GetStok($f);
		$margin_resep = $harga_barang*$this->GetMarginResep()/100;
		$margin_paramedis = $harga_barang*$this->GetMarginParamedis()/100;
		if($j==1) :
			$harga_fix = $harga_barang+$margin_resep+$margin_paramedis;
			$subtotal =  $this->GetSubTotal($a)+$harga_fix;
			$total = $this->pembulatan($subtotal);
		else :
			$harga_fix = $harga_barang+$margin_resep;
			$subtotal =  $this->GetSubTotal($a)+$harga_fix;
			$total = $this->pembulatan($subtotal);
		endif;
		$cek_penjualan = $this->CekTransacID($a);
		$cek_resep = $this->CekResepID($no_resep);
		$data1 = array(
						'id_resep' => $no_resep,
						'id_penjualan' => $a,
						'nm_pasien' => $c,
						'nm_dokter' => $b,
						'alamat' => $e,
						'kontak_pasien' => $d,
						'biaya_resep' => $margin_resep
					);
		$data2 = array(
						'id_resep' => $no_resep,
						'id_barang_detail' => $barang_detail,
						'etiket' => $i,
						'qty' => $g,
						'margin' => $margin_resep,
						'total' => $harga_fix*$g
					);
		$data3 = array(
						'id_penjualan' => $a,
						'id_user' => $this->session->userdata('kode'),
						'pembulatan' => $total-($this->GetSubTotal($a)+$harga_fix*$g),
						'subtotal' => $total
					);
		$data4 = array(
						'stok_tersedia' => $stok-$g
					  );
		$data5 = array(
						'stok_keluar' => -$stok-$g
					  );
		if($cek_penjualan==NULL) {
			$result_3 = $this->db->insert($this->table[2],$data3);
			$result_1 = $this->db->insert($this->table[0],$data1);
			$result_2 = $this->db->insert($this->table[1],$data2);
			$result_4 = $this->db->where('id_barang',$f)->update($this->table[3],$data4);
			$result_5 = $this->db->insert($this->table[6],$data5);
		} else {
			$result_2 = $this->db->insert($this->table[1],$data2);
			$result_3 = $this->db->where('id_penjualan',$a)->update($this->table[2],$data3);
			$result_4 = $this->db->where('id_barang',$f)->update($this->table[3],$data4);
			$result_5 = $this->db->insert($this->table[6],$data5);
			return TRUE;
		}
	}

	public function SaveDataPJ() {
		$a = $this->input->post('id_penjualan');
		$f = $this->input->post('barang');
		$g = $this->input->post('qty');
		$h = $this->input->post('diskon');
		$j = $this->input->post('paramedis');
		$user = $this->session->userdata('kode');
		$barang_detail = $this->GetDetBrgID($f);
		$harga_barang = $this->GetHargaBarang($f);
		$harga_fix = $this->pembulatan($harga_barang);
		$pembulatan = $harga_fix-$harga_barang;
		$total = $harga_fix*$g-$h;
		$stok = $this->GetStok($f);
		$query = $this->db->query(
															"call ak_procedure_insert_penjualan_bebas_detail(
																'".$barang_detail."',
																'".$user."',
																'".$g."',
																'".$h."',
																'".$pembulatan."',
																'".$total."',
																'1'
															)"
														 );
		return $a;
	}

	private function GetStok($id) {
		$res = $this->db->where('id_barang',$id)
						->get($this->table[3]);
		return $res->row('stok_tersedia');
	}

	public function DelData($id_tr,$id_brg,$id_rsp) {
		$var1 = $this->db->where('id_resep_detail',$id_brg)->get($this->table[1])->row();
		$var2 = $this->db->where('id_penjualan',$id_tr)->get($this->table[2])->row();
		$data1 = array(
						'deleted' => 1
					  );
		$data2 = array(
						'subtotal' => $this->pembulatan($var2->subtotal-$var1->total)
					  );
		$this->db->where('id_resep_detail',$id_brg)
				 ->update($this->table[1],$data1);
		$this->db->where('id_penjualan',$id_tr)
				 ->update($this->table[2],$data2);
	}

	public function SimpanTR($id) {
		$bayar = $this->input->post('bayar');
		$var = $this->db->where('id_penjualan',$id)->get($this->table[2])->row();
		$data1 = array(
						'status' => 1,
						'bayar' => $bayar,
						'kembali' => $bayar-$var->subtotal
					  );
		$this->db->where('id_penjualan',$id)
				 ->update($this->table[2],$data1);
		return $bayar-$var->subtotal;
	}

	private function GetMarginResep() {
		$res = $this->db->where('nm_margin','Resep')
						->get($this->table[7]);
		return $res->row('harga_margin');
	}

	private function GetMarginParamedis() {
		$res = $this->db->where('nm_margin','Paramedis')
						->get($this->table[7]);
		return $res->row('harga_margin');
	}

	private function pembulatan($uang) {
		$ratusan = substr($uang, -5);
		if($ratusan<50) 
			$akhir = $uang - $ratusan;
		else 
			$akhir = $uang + (100-$ratusan);
		return $akhir;
	}

	private function GetNoResep($no_resep_raw) {
		$no_resep = $this->GetResepID($no_resep_raw);
		if($no_resep==NULL) :
			$tot = $this->GetTotalResep();
			$raw = $tot+1;
			return 'R'.$this->zerofill($raw);
		else :
			return $no_resep;
		endif;
	}

	private function GetResepID($id) {
		$res = $this->db->where('id_penjualan',$id)
						->get($this->table[0]);
		return $res->row('id_resep');
	}

	private function CekResepID($no_resep) {
		$res = $this->db->where('id_resep',$id)
						->get($this->table[0]);
		$param = $res->row();
		return $param;
	}

	private function CekTransacID($no_pj) {
		$res = $this->db->where('id_penjualan',$no_pj)
						->get($this->table[2]);
		$param = $res->row('id_penjualan');
		return $param;
	}

	private function GetDetBrgID($id_barang) {
		$res = $this->db->where('id_barang',$id_barang)
						->order_by('id_detail_barang','desc')
						->get($this->table[4]);
		return $res->row('id_detail_barang');
	}

	private function GetHargaBarang($id_barang) {
		$res = $this->db->where('id_barang',$id_barang)
					->order_by('id_barang','desc')
					->get('ak_data_barang');
		return $res->row('harga_dasar');
	}

	private function zerofill ($num, $zerofill = 5) {
		return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
	}

}