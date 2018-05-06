<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLPembelian extends CI_Model{

	protected $data_pbf = "ak_data_pbf";
	protected $data_user = 'ak_data_user';
	protected $data_barang = "ak_data_barang";
	protected $data_pabrik = "ak_data_pabrik";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_pesanan = "ak_data_pesanan";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_pesanan_detail = "ak_data_pesanan_detail";
	protected $data_pembelian_detail = "ak_data_pembelian_detail";


	public function GetAll(){
		$res = $this->db->where($this->data_pembelian_detail.'.deleted',FALSE)
						->join(
								$this->data_pembelian,
								$this->data_pembelian.'.id_pembelian='.
								$this->data_pembelian_detail.'.id_pembelian'
							)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pembelian_detail.'.id_barang'
							)
						->join(
								$this->data_pabrik,
								$this->data_pabrik.'.id_pabrik='.
								$this->data_barang.'.id_pabrik'
							)
						->get($this->data_pembelian_detail);
		return $res->result();
	}
	
	public function GetAllSurat(){
		$res = $this->db->where($this->data_pesanan_detail.'.deleted',FALSE)
						->join(
								$this->data_pesanan,
								$this->data_pesanan.'.id_pesanan='.
								$this->data_pesanan_detail.'.id_pesanan'
							)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pesanan_detail.'.id_barang'
							)
						->join(
								$this->data_pabrik,
								$this->data_pabrik.'.id_pabrik='.
								$this->data_barang.'.id_pabrik'
							)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pesanan.'.id_pbf'
							)
						->get($this->data_pesanan_detail);
		return $res->result();
	}

}
?>