<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MCashflow extends CI_Model {

	protected $data_penjualan = "ak_data_penjualan";
	protected $data_pembelian = "ak_data_pembelian";

	public function get_pemasukan_perhari($date) {
		$res = $this->db->select_sum('grandtotal')
						->where('tanggal_penjualan',$date)
						->get($this->data_penjualan);
		return $res->row('grandtotal');
	}

	public function get_pengeluaran_perhari($date) {
		$res = $this->db->select_sum('subtotal')
						->where('tanggal_pembelian',$date)
						->get($this->data_pembelian);
		return $res->row('subtotal');
	}

}