<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MBarang extends CI_Model {

	protected $table = array( 'ak_data_pbf',
														'ak_data_barang',
														'ak_data_barang_detail',
														'ak_data_barang_info',
														'ak_data_barang_stok',
														'ak_data_jenis_obat',
														'ak_data_satuan',
														'ak_data_pabrik',
														'ak_data_kemasan'
													);

	public function GetTotalBarang() {
		$res = $this->db->where($this->table[1].'.deleted',FALSE)
						->join($this->table[5],$this->table[5].'.id_jenis='.$this->table[1].'.id_jenis')
						->join($this->table[8],$this->table[8].'.id_kemasan='.$this->table[1].'.id_kemasan')
						->join($this->table[6],$this->table[6].'.id_satuan='.$this->table[1].'.id_satuan')
						->join($this->table[7],$this->table[7].'.id_pabrik='.$this->table[1].'.id_pabrik')
						->get($this->table[1]);
		return $res->num_rows();
	}

	public function GetPBF() {
		$res = $this->db->where('deleted',FALSE)
						->get($this->table[0]);
		return $res->result();
	}

	public function GetBarang() {
		$res = $this->db->where($this->table[1].'.deleted',FALSE)
						->join($this->table[5],$this->table[5].'.id_jenis='.$this->table[1].'.id_jenis')
						->join($this->table[8],$this->table[8].'.id_kemasan='.$this->table[1].'.id_kemasan')
						->join($this->table[6],$this->table[6].'.id_satuan='.$this->table[1].'.id_satuan')
						->join($this->table[7],$this->table[7].'.id_pabrik='.$this->table[1].'.id_pabrik')
						->order_by('nm_barang','asc')
						->get($this->table[1]);
		return $res->result();
	}

	public function GetSingleData($id) {
		$res = $this->db->where($this->table[1].'.deleted',FALSE)
						->where($this->table[1].'.id_barang',$id)
						->join($this->table[5],$this->table[5].'.id_jenis='.$this->table[1].'.id_jenis')
						->join($this->table[8],$this->table[8].'.id_kemasan='.$this->table[1].'.id_kemasan')
						->join($this->table[6],$this->table[6].'.id_satuan='.$this->table[1].'.id_satuan')
						->join($this->table[7],$this->table[7].'.id_pabrik='.$this->table[1].'.id_pabrik')
						->order_by('nm_barang','asc')
						->get($this->table[1]);
		return $res->row();
	}

	public function SaveData() {
		$a = $this->input->post('nm_barang');
		$b = $this->input->post('nm_pabrik');
		$c = $this->input->post('kemasan');
		$d = $this->input->post('isi_kemasan');
		$e = $this->input->post('satuan');
		$f = $this->input->post('hna');
		$g = $this->input->post('gol_obat');
		$h = $this->input->post('jenis_obat');
		$i = $this->input->post('formularium');
		$j = $this->input->post('konsinyasi');
		$k = $this->input->post('dosis');
		$l = $this->input->post('komposisi');
		$m = $this->input->post('indikasi');
		$n = $this->input->post('efek_samping');
		$o = $this->input->post('stok_max');
		$p = $this->input->post('stok_min');
		$data = array(
						'id_jenis' => $h,
						'nm_barang' => $a,
						'id_pabrik' => $b,
						'id_kemasan' => $c,
						'id_satuan' => $e,
						'isi_satuan' => $d,
						'golongan_obat' => $g,
						'harga_dasar' => $f,
						'dosis' => $k,
						'komposisi' => $l,
						'indikasi' => $m,
						'efek_samping' => $n,
						'formularium' => $i,
						'konsinyasi' => $j,
						'stok_maksimum' => $o,
						'stok_minimum' => $p
					 );
		$this->db->insert($this->table[1],$data);
		return "success-Data telah tersimpan!";
	}

	public function EditData($id) {
		$a = $this->input->post('nm_barang');
		$b = $this->input->post('nm_pabrik');
		$c = $this->input->post('kemasan');
		$d = $this->input->post('isi_kemasan');
		$e = $this->input->post('satuan');
		$f = $this->input->post('hna');
		$g = $this->input->post('gol_obat');
		$h = $this->input->post('jenis_obat');
		$i = $this->input->post('formularium');
		$j = $this->input->post('konsinyasi');
		$k = $this->input->post('dosis');
		$l = $this->input->post('komposisi');
		$m = $this->input->post('indikasi');
		$n = $this->input->post('efek_samping');
		$o = $this->input->post('stok_max');
		$p = $this->input->post('stok_min');
		$data = array(
						'id_jenis' => $h,
						'nm_barang' => $a,
						'id_pabrik' => $b,
						'id_kemasan' => $c,
						'id_satuan' => $e,
						'isi_satuan' => $d,
						'golongan_obat' => $g,
						'harga_dasar' => $f,
						'dosis' => $k,
						'komposisi' => $l,
						'indikasi' => $m,
						'efek_samping' => $n,
						'formularium' => $i,
						'konsinyasi' => $j,
						'stok_maksimum' => $o,
						'stok_minimum' => $p
					 );
		$this->db->where('id_barang',$id)
				 ->update($this->table[1],$data);
		return "success-Data telah diubah!";
	}

	public function DelData($id) {
		$data = array(
									'deleted' => 1
								 );
		$this->db->where('id_barang',$id)
						 ->update($this->table[1],$data);
		return "success-Data telah dihapus!";
	}

	private function hash_pwd($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	private function zerofill ($num, $zerofill = 5) {
		return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
	}

}