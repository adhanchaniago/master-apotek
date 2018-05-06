<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLaporan extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_stok_keluar = "ak_data_barang_stok_keluar";
	protected $data_barang_stok_masuk = "ak_data_barang_stok_masuk";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";
	protected $data_barang_stok_fisik = "ak_data_barang_stok_opname";

	public function get_barang() {
		$res = $this->db->where($this->data_barang.'.deleted',FALSE)
						->order_by('nm_barang','ASC')
						->get($this->data_barang);
		return $res->result();
	}

	public function get_stok_awal($kode) {
		$date = date('Y-m-d H:i:s');
		$newdate = strtotime ( '-1 month' , strtotime ( $date ) ) ;
		$newdate = date ( 'Y-m-t H:i:s' , $newdate );
		$res = $this->db//->select_sum('stok_tersedia')
						->where('id_barang',$kode)
						//->where('fixed',TRUE)
						//->where('deleted',FALSE)
						->where('updated<=',$newdate)
						->order_by('updated','DESC')
						->get($this->data_barang_stok_tersedia);
		if($res->row()!=NULL) :
			return $res->row('stok_tersedia');
		else :
			$res = $this->db//->select_sum('stok_tersedia')
						->where('id_barang',$kode)
						//->where('fixed',TRUE)
						//->where('deleted',FALSE)
						->where('updated<=',date('Y-m-d H:i:s'))
						->order_by('updated','DESC')
						->get($this->data_barang_stok_tersedia);
			return $res->row('stok_tersedia');
		endif;
	}

	public function get_stok_terakhir($kode) {
		$res = $this->db->select_sum('stok_tersedia')
						->where('id_barang',$kode)
						->where('fixed',TRUE)
						->where('deleted',FALSE)
						//->where('updated<=',date('Y-m-d H:i:s'))
						->order_by('updated','DESC')
						->get($this->data_barang_stok_tersedia);
		return $res->row('stok_tersedia');
	}

	public function get_stok_masuk($kode) {
		$res = $this->db->select_sum('stok_masuk')
						->where('id_barang',$kode)
						->where('deleted',FALSE)
						->where('tanggal_masuk>=',date('Y-m-01'))
						->where('tanggal_masuk<=',date('Y-m-t'))
						->order_by('tanggal_masuk','ASC')
						->get($this->data_barang_stok_masuk);
		return $res->row('stok_masuk');
	}

	public function get_stok_keluar($kode) {
		$res = $this->db->select_sum('stok_keluar')
						->where('id_barang',$kode)
						->where('tanggal_keluar>=',date('Y-m-01'))
						->where('tanggal_keluar<=',date('Y-m-t'))
						->order_by('tanggal_keluar','ASC')
						->get($this->data_barang_stok_keluar);
		return $res->row('stok_keluar');
	}

	public function get_stok_sisa($kode) {
		$res = $this->db->where('id_barang',$kode)
						->where('fixed',TRUE)
						->where('deleted',FALSE)
						->get($this->data_barang_stok_tersedia);
		return $res->row('stok_tersedia');
	}

	public function get_stok_fisik($kode) {
		$res = $this->db->where('id_barang',$kode)
						->order_by('tanggal_opname','DESC')
						->get($this->data_barang_stok_fisik);
		return $res->row('stok_fisik');
	}

	public function get_penyesuaian($kode) {
		$res = $this->db->where('id_barang',$kode)
						->order_by('tanggal_opname','DESC')
						->get($this->data_barang_stok_fisik);
		return $res->row('penyesuaian');
	}

	public function GetSingle() {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->get($this->data_barang);
		return $res->row();
	}

	public function SaveData() {
		$hitung_data = $this->db->get($this->data_barang_stok_fisik)->num_rows();
		$id = "OP".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		$stok_sisa = $this->get_stok_sisa($this->input->post('id_barang'));
		$penyesuaian = $this->input->post('stok_fisik')-$stok_sisa;
		$data_0 = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_fisik' => $this->input->post('stok_fisik'),
							'penyesuaian' => $penyesuaian
						);
		$this->db->insert($this->data_barang_stok_fisik,$data_0);
		$data_1 = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_keluar' => $penyesuaian
						);
		$this->db->insert($this->data_barang_stok_keluar,$data_1);
		$stok_lama = $this->db->where('id_barang',$this->input->post('id_barang'))
							  ->where('fixed',TRUE)
							  ->where('deleted',FALSE)
							  ->order_by('updated','DESC')
							  ->get($this->data_barang_stok_tersedia)
							  ->row();
		//$stok_tersedia = $stok_lama->stok_tersedia+$this->input->post('qty');
		$data_4 = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_tersedia' => $this->input->post('stok_fisik'),
							'updated' => date('Y-m-d H:i:s'),
							'fixed' => 1
						);
		$this->db->insert($this->data_barang_stok_tersedia,$data_4);
		if($stok_lama!=NULL) :
			$this->db->where('id_stok_tersedia',$stok_lama->id_stok_tersedia)
				 	 ->update($this->data_barang_stok_tersedia,array('deleted' => TRUE));
		endif;
	}

}