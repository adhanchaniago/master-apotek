<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MBarang extends CI_Model {

	protected $data_barang = "ak_data_barang";
	protected $data_barang_detail = "ak_data_barang_detail";
	protected $data_jenis_obat = "ak_data_jenis_obat";
	protected $data_pabrik = "ak_data_pabrik";
	protected $data_kemasan = "ak_data_kemasan";
	protected $data_satuan = "ak_data_satuan";
	protected $data_barang_stok_tersedia = "ak_data_barang_stok_tersedia";


	public function GetAll() {
		$res = $this->db->where($this->data_barang.'.deleted',FALSE)
						->join(
								$this->data_jenis_obat,
								$this->data_jenis_obat.'.id_jenis='.
								$this->data_barang.'.id_jenis'
							)
						->join(
								$this->data_pabrik,
								$this->data_pabrik.'.id_pabrik='.
								$this->data_barang.'.id_pabrik'
							)
						->join(
								$this->data_kemasan,
								$this->data_kemasan.'.id_kemasan='.
								$this->data_barang.'.id_kemasan'
							)
						->join(
								$this->data_satuan,
								$this->data_satuan.'.id_satuan='.
								$this->data_barang.'.id_satuan'
							)
						->order_by('nm_barang','ASC')
						->get($this->data_barang);
		return $res->result();
	}

	public function total_limit() {
		$res = $this->db->where($this->data_barang.'.deleted',FALSE)
						->where('stok_tersedia<=','stok_minimum')
						->where($this->data_barang_stok_tersedia.'.fixed',TRUE)
						->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->join(
								$this->data_barang_stok_tersedia,
								$this->data_barang_stok_tersedia.'.id_barang='.
								$this->data_barang.'.id_barang'
							  )
						->get($this->data_barang);
		return $res->num_rows();
	}

	public function total_barang() {
		return $this->db->where('deleted',FALSE)
						->get($this->data_barang)->num_rows();
	}

	public function total_kadaluarsa() {
		return $this->db->where('deleted',FALSE)
						->where('kadaluarsa<',date('Y-m-d'))
						->get($this->data_barang_detail)->num_rows();
	}

	public function total_barang_detail() {
		return $this->db->where('deleted',FALSE)
						->get($this->data_barang_detail)->num_rows();
	}

	public function GetSingle() {
		$id_barang = $this->input->post('id_barang');
		$res = $this->db->where('id_barang',$id_barang)->get($this->data_barang);
		return $res->row();
	}

	public function GetOption() {
		$res = $this->db->select($this->data_barang.'.id_barang,nm_barang,'.$this->data_barang_stok_tersedia.'.stok_tersedia')
						->where($this->data_barang.'.deleted',FALSE)
						->where($this->data_barang.'.isi_satuan!=',0)
						->where($this->data_barang_stok_tersedia.'.deleted',FALSE)
						->where($this->data_barang_stok_tersedia.'.fixed',TRUE)
						->join(
							$this->data_barang_stok_tersedia,
							$this->data_barang_stok_tersedia.'.id_barang='.
							$this->data_barang.'.id_barang'
						  )
						->order_by('nm_barang','ASC')
						->get($this->data_barang);
		return $res->result();
	}

	public function SaveData() {
		if($this->input->post('id_barang')==NULL) {
			$hitung_data = $this->db->get($this->data_barang)->num_rows();
			$id = "BRG".str_pad($hitung_data+1, 5, "0", STR_PAD_LEFT);
			$data = array(
							'id_barang' => $id,
							'nm_barang' => $this->input->post('nm_barang'),
							'id_jenis' => $this->input->post('id_jenis'),
							'id_pabrik' => $this->input->post('id_pabrik'),
							'golongan_obat' => $this->input->post('id_golongan'),
							'id_kemasan' => $this->input->post('id_kemasan'),
							'id_satuan' => $this->input->post('id_satuan'),
							'isi_satuan' => $this->input->post('isi_satuan'),
							'margin' => $this->input->post('margin'),
							'harga_dasar' => $this->input->post('harga_dasar'),
							'stok_maksimum' => $this->input->post('stok_maksimum'),
							'stok_minimum' => $this->input->post('stok_minimum'),
							'konsinyasi' => $this->input->post('konsinyasi'),
							'formularium' => $this->input->post('formularium')
						);
			$this->db->insert($this->data_barang,$data);
			$data_stok = array(
				'id_barang' => $id,
				'fixed' => true
			);
			$this->db->insert($this->data_barang_stok_tersedia,$data_stok);
		} else {
			$data = array(
							'nm_barang' => $this->input->post('nm_barang'),
							'id_jenis' => $this->input->post('id_jenis'),
							'id_pabrik' => $this->input->post('id_pabrik'),
							'golongan_obat' => $this->input->post('id_golongan'),
							'id_kemasan' => $this->input->post('id_kemasan'),
							'id_satuan' => $this->input->post('id_satuan'),
							'isi_satuan' => $this->input->post('isi_satuan'),
							'margin' => $this->input->post('margin'),
							'harga_dasar' => $this->input->post('harga_dasar'),
							'stok_maksimum' => $this->input->post('stok_maksimum'),
							'stok_minimum' => $this->input->post('stok_minimum'),
							'konsinyasi' => $this->input->post('konsinyasi'),
							'formularium' => $this->input->post('formularium')
						);
			$this->db->where('id_barang',$this->input->post('id_barang'))
					 ->update($this->data_barang,$data);
		}
		return TRUE;
	}

	public function DelData() {
		$data = array(
						'deleted' => 1
					);
		$this->db->where('id_barang',$this->input->post('id_barang'))
				 ->update($this->data_barang,$data);
		return TRUE;
	}

}