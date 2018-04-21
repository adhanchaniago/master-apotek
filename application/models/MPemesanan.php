<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MPemesanan extends CI_Model {

	protected $data_pbf = "ak_data_pbf";
	protected $data_user = 'ak_data_user';
	protected $data_barang = "ak_data_barang";
	protected $data_kemasan = "ak_data_kemasan";
	protected $data_pesanan = "ak_data_pesanan";
	protected $data_pesanan_detail = "ak_data_pesanan_detail";

	public function GetAll() {
		$res = $this->db->where($this->data_pesanan.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pesanan.'.id_pbf'
							)
						->join(
								$this->data_user,
								$this->data_user.'.id_user='.
								$this->data_pesanan.'.id_user'
							)
						->get($this->data_pesanan);
		return $res->result();
	}

	public function GetAllDetail($kode) {
		$res = $this->db->where('id_pesanan',$kode)
						->where($this->data_pesanan_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_pesanan_detail.'.id_barang'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->get($this->data_pesanan_detail);
		return $res->result();
	}

	public function GetSingle($kode) {
		$id_barang = $this->input->post('id_barang');
		$res = $this->db->where('id_barang',$id_barang)
						->where('id_pesanan',$kode)
						->get($this->data_pesanan_detail);
		return $res->row();
	}

	public function SaveData($kode) {
		$find = $this->db->where('id_barang',$this->input->post('id_barang'))
						 ->where('id_pesanan',$kode)
						 ->where('deleted',FALSE)
						 ->get($this->data_pesanan_detail);
		if($find->num_rows()==0) {
			$data = array(
							'id_barang' => $this->input->post('id_barang'),
							'id_pesanan' => $kode,
							'qty' => $this->input->post('qty')
						);
			$this->db->insert($this->data_pesanan_detail,$data);
		} else {
			$data = array(
							'id_pesanan' => $kode,
							'qty' => $this->input->post('qty')
						);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->where('id_pesanan',$kode)
					 ->update($this->data_pesanan_detail,$data);
		}
		return TRUE;
	}

	public function SaveAll($kode) {
		$hitung_data = $this->db->get($this->data_pesanan)->num_rows();
		$id = "SP".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		if($kode==NULL) {
			$data = array(
							'id_pesanan' => $id,
							'id_pbf' => $this->input->post('id_pbf'),
							'id_user' => $this->session->userdata('kode'),
							'tanggal_pembuatan' => date('Y-m-d'),
						);
			$this->db->insert($this->data_pesanan,$data);
			$this->db->where('id_pesanan',$kode)
					 ->update($this->data_pesanan_detail,array('id_pesanan' => $id));
		} else {
			$data = array(
							//'id_pesanan' => $id,
							'id_pbf' => $this->input->post('id_pbf'),
							'id_user' => $this->session->userdata('kode'),
							//'tanggal_pembuatan' => date('Y-m-d'),
						);
			$this->db->where('id_pesanan',$kode)
					 ->update($this->data_pesanan,$data);
		}
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_pesanan_detail,$data);
		return TRUE;
	}

	public function DelDataAll($kode) {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_pesanan',$kode)
				 ->update($this->data_pesanan,$data);
		$this->db->where('id_pesanan',$kode)
				 ->update($this->data_pesanan_detail,$data);
		return TRUE;
	}

}