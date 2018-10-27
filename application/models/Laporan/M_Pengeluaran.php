<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Pengeluaran extends CI_Model {

	protected $pembelian = "ak_data_pembelian";
	
	public function total_pembelian() {
		return $this->db->where(
			'deleted',FALSE
		)->get($this->pembelian)->num_rows();
	}

	public function total_hutang() {
		return $this->db->where(
			'deleted',FALSE
		)->where(
			'status',FALSE
		)->get($this->pembelian)->num_rows();
	}

	public function total_pengeluaran() {
		return $this->db->select_sum(
			'subtotal'
		)->where(
			'deleted',FALSE
		)->where(
			'status',TRUE
		)->where(
			'tanggal_pembelian>=',date('Y-01-01')
		)->where(
			'tanggal_pembelian<=',date('Y-12-31')
		)->get($this->pembelian)->row('subtotal');
	}

	public function total_hutangs() {
		$res = $this->db->select_sum('subtotal')
						->where('deleted',FALSE)
						->where('status',FALSE)
						->where('tanggal_pembelian>=',date('Y-01-01'))
						->where('tanggal_pembelian<=',date('Y-12-31'))
						->get($this->pembelian);
		return $res->row('subtotal');
	}

}