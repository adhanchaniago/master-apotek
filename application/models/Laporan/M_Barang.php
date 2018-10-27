<?php defined('BASEPATH') OR exit('No direct script access allowed');
class M_Barang extends CI_Model {

	protected $barang = "ak_data_barang";
	protected $barang_detail = "ak_data_barang_detail";
	protected $barang_stok_tersedia = "ak_data_barang_stok_tersedia";

	public function total_limit() {
		return $this->db->where(
			$this->barang.'.deleted',FALSE
		)->where(
			'stok_tersedia<=','stok_minimum'
		)->where(
			$this->barang_stok_tersedia.'.fixed',TRUE
		)->where(
			$this->barang_stok_tersedia.'.deleted',FALSE
		)->join(
			$this->barang_stok_tersedia,
			$this->barang_stok_tersedia.'.id_barang='.
			$this->barang.'.id_barang'
		)->get($this->barang)->num_rows();
	}

	public function total_barang() {
		return $this->db->where(
			'deleted',FALSE
		)->get($this->barang)->num_rows();
	}

	public function total_kadaluarsa() {
		return $this->db->where(
			'deleted',FALSE
		)->where(
			'kadaluarsa<',date('Y-m-d')
		)->get($this->barang_detail)->num_rows();
	}

	public function total_barang_detail() {
		return $this->db->where(
			'deleted',FALSE
		)->get($this->barang_detail)->num_rows();
	}

}