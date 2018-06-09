<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MLaporan extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_pembelian = "ak_data_pembelian";
	protected $data_pembelian_detail = "ak_data_pembelian_detail";
	protected $data_pabrik = "ak_data_pabrik";
	protected $data_pbf = "ak_data_pbf";
	protected $data_kemasan = "ak_data_kemasan";
	protected $data_pesanan = "ak_data_pesanan";
	protected $data_pesanan_detail = "ak_data_pesanan_detail";
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

	public function get_data_stok(){
		$res = $this->db->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->where($this->data_barang_stok_tersedia.'.fixed',TRUE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_barang_stok_tersedia.'.id_barang'
							)
						->limit(5)
						->order_by($this->data_barang_stok_tersedia.'.stok_tersedia','ASC')
						->get($this->data_barang_stok_tersedia);
		return $res->result();
	}

	public function get_data_barang_expired(){
		$res = $this->db->where($this->data_barang_detail.'.deleted',FALSE)
						->join(
								$this->data_barang,
								$this->data_barang.'.id_barang='.
								$this->data_barang_detail.'.id_barang'
							)
						->join(
								$this->data_pembelian,
								$this->data_pembelian.'.id_pembelian='.
								$this->data_barang_detail.'.id_pembelian'
							)
						->limit(5)
						->order_by($this->data_barang_detail.'.kadaluarsa','ASC')
						->get($this->data_barang_detail);
		return $res->result();
	}

	public function get_data_jatuh_tempo(){
		$res = $this->db->where($this->data_pembelian.'.deleted',FALSE)
						->join(
								$this->data_pbf,
								$this->data_pbf.'.id_pbf='.
								$this->data_pembelian.'.id_pbf'
							)
						->limit(5)
						->order_by($this->data_pembelian.'.tanggal_jatuh_tempo','ASC')
						->get($this->data_pembelian);
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
			return 0;
		endif;
	}

	public function get_history_stok_masuk($kode) {
		$res = $this->db//->select_sum('stok_tersedia')
						->where($this->data_barang_stok_masuk.'.id_barang',$kode)
						->join($this->data_barang,
							   $this->data_barang.'.id_barang='.
							   $this->data_barang_stok_masuk.'.id_barang')
						//->where('fixed',TRUE)
						//->where('deleted',FALSE)
						//->where('updated<=',$newdate)
						->order_by('tanggal_masuk','DESC')
						->get($this->data_barang_stok_masuk);
		//if($res->row()!=NULL) :
			return $res->result();
		//else :
			//return 0;
		//endif;
	}

	public function get_history_stok_keluar($kode) {
		$res = $this->db//->select_sum('stok_tersedia')
						->where($this->data_barang_stok_keluar.'.id_barang',$kode)
						->join($this->data_barang,
							   $this->data_barang.'.id_barang='.
							   $this->data_barang_stok_keluar.'.id_barang')
						//->where('fixed',TRUE)
						//->where('deleted',FALSE)
						//->where('updated<=',$newdate)
						->order_by('tanggal_keluar','DESC')
						->get($this->data_barang_stok_keluar);
		//if($res->row()!=NULL) :
			return $res->result();
		//else :
			//return 0;
		//endif;
	}

	public function get_history_stok_sisa($kode) {
		$res = $this->db//->select_sum('stok_tersedia')
						->where($this->data_barang_stok_tersedia.'.id_barang',$kode)
						->join($this->data_barang,
							   $this->data_barang.'.id_barang='.
							   $this->data_barang_stok_tersedia.'.id_barang')
						//->where('fixed',TRUE)
						//->where('deleted',FALSE)
						//->where('updated<=',$newdate)
						->order_by('updated','DESC')
						->get($this->data_barang_stok_tersedia);
		//if($res->row()!=NULL) :
			return $res->result();
		//else :
			//return 0;
		//endif;
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
		return $res->row('penyesuaian_stok');
	}

	public function get_penyesuaian_keuangan($kode) {
		$res = $this->db->where('id_barang',$kode)
						->order_by('tanggal_opname','DESC')
						->get($this->data_barang_stok_fisik);
		return $res->row('penyesuaian_keuangan');
	}

	public function GetSingle() {
		$res = $this->db->where('id_barang',$this->input->post('id_barang'))
						->get($this->data_barang);
		return $res->row();
	}

	public function SaveOpname() {
		$hitung_data = $this->db->get($this->data_barang_stok_fisik)->num_rows();
		$id = "OP".date('dmy').str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
		$stok_sisa = $this->get_stok_sisa($this->input->post('id_barang'));
		$harga_barang = $this->db->where('id_barang',$this->input->post('id_barang'))->get($this->data_barang)->row('harga_dasar');
		$penyesuaian_stok = $this->input->post('stok_fisik')-$stok_sisa;
		if($penyesuaian_stok<0) {
			$penyesuaian_keuangan = -$harga_barang;
		} else {
			$penyesuaian_keuangan = $harga_barang;
		}
		$data_0 = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_fisik' => $this->input->post('stok_fisik'),
							'penyesuaian_stok' => $penyesuaian_stok,
							'penyesuaian_keuangan' => $penyesuaian_keuangan
						);
		$this->db->insert($this->data_barang_stok_fisik,$data_0);
		$data_1 = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_keluar' => abs($penyesuaian_stok)
						);
		$data_a = array(
							'id_opname' => $id,
							'id_barang' => $this->input->post('id_barang'),
							'stok_masuk' => $penyesuaian_stok
						);
		if($penyesuaian_stok<0) :
			$this->db->insert($this->data_barang_stok_keluar,$data_1);
		else :
			$this->db->insert($this->data_barang_stok_masuk,$data_a);
		endif;
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