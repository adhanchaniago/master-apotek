<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Pemasukan extends CI_Model {

	protected $penjualan = "ak_data_penjualan";
	
	public function total_penjualan() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->penjualan);
		return $res->num_rows();
	}

	public function total_piutang() {
		$res = $this->db->where('deleted',FALSE)
						->where('status',FALSE)
						->get($this->penjualan);
		return $res->num_rows();
	}

	public function total_pendapatan() {
		$res = $this->db->select_sum('grandtotal')
						->where('deleted',FALSE)
						->where('status',TRUE)
						->where('tanggal_penjualan>=',date('Y-01-01'))
						->where('tanggal_penjualan<=',date('Y-12-31'))
						->get($this->penjualan);
		return $res->row('grandtotal');
	}

}