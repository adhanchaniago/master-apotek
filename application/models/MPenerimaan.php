<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPenerimaan extends CI_Model {

	protected $data_pesanan = "ak_data_pesanan";
	protected $data_pesanan_detail = "ak_data_pesanan_detail";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_pembelian_detail = "ak_data_pembelian_detail";
	protected $data_barang = "ak_data_barang";
	protected $data_pabrik = "ak_data_pabrik";
	protected $data_pbf = "ak_data_pbf";
	protected $data_kemasan = "ak_data_kemasan";

	public function get_report_penerimaan_barang() {
		$res = $this->db->where($this->data_pembelian.'.deleted',FALSE)
						->join(
								$this->data_pesanan,
								$this->data_pesanan.'.id_pesanan='.
								$this->data_pembelian.'.id_pesanan'
							  )
						->join(
								$this->data_pembelian_detail,
								$this->data_pembelian_detail.'.id_pembelian='.
								$this->data_pembelian.'.id_pembelian'
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
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pembelian.'.id_pbf'
							  )
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							  )
						->get($this->data_pembelian);
		return $res->result();
	}

}