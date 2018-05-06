<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MHutang extends CI_Model{

	protected $data_pbf = "ak_data_pbf";
	protected $data_user = 'ak_data_user';
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_pesanan = "ak_data_pesanan";


	public function GetAll(){
		$res = $this->db->where($this->data_pembelian.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pembelian.'.id_pbf'
							)
						->get($this->data_pembelian);
		return $res->result();
	}

}
?>