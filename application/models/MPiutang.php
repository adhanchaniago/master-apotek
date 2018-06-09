<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPiutang extends CI_Model{

	protected $data_user = 'ak_data_user';
	protected $data_penjualan = "ak_data_penjualan";


	public function GetAll(){
		$res = $this->db->where($this->data_penjualan.'.deleted',FALSE)
						->where("status",FALSE)
						->get($this->data_penjualan);
		return $res->result();
	}

}
?>