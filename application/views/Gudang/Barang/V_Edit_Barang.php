<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support" OR $Level="Owner") : ?>
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/select2/dist/css/select2.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/adminLTE/css/AdminLTE.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/adminLTE/css/skins/skin-green.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/sweet-alert/sweetalert.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/DataTables/datatables.min.css'); ?>">
	<div class="container">
		<h4 class="text-center">Form Edit Barang</h4>
		<?= form_open('barang/proses-edit-data/'.$Data->id_barang) ?>
			<div class="row">
				<div class="col-lg-6">
					<label>Nama Barang</label>
					<?= form_input('nm_barang',$Data->nm_barang,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
				<div class="col-lg-6">
					<label>Nama Pabrik</label>
					<select name="nm_pabrik" class="select2 form-control" required="true">
						<option value="">== Pilih Pabrik ==</option>
						<?php foreach($Pabrik as $d) : ?>
							<option value="<?= $d->id_pabrik ?>" <?php if($Data->id_pabrik==$d->id_pabrik) : echo "selected"; endif ?> class="select2"><?= $d->nm_pabrik ?></option>
						<?php endforeach ?>
				</select>
				</div>
				<div class="col-lg-4">
					<label>Kemasan</label>
					<select name="kemasan" class="select3 form-control" required="true">
						<option value="">== Pilih Kemasan ==</option>
						<?php foreach($Kemasan as $d) : ?>
							<option value="<?= $d->id_kemasan ?>" <?php if($d->id_kemasan==$Data->id_kemasan) : echo "selected"; endif; ?>><?= $d->nm_kemasan ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-lg-4">
					<label>Isi Kemasan</label>
					<?= form_input('isi_kemasan',$Data->isi_satuan,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
				<div class="col-lg-4">
					<label>Satuan</label>
					<select name="satuan" class="select4 form-control" required="true">
						<option value="">== Pilih Satuan ==</option>
						<?php foreach($Satuan as $d) : ?>
							<option value="<?= $d->id_satuan ?>" <?php if($d->id_satuan==$Data->id_satuan) : echo "selected"; endif; ?>><?= $d->nm_satuan ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-lg-4">
					<label>HNA</label>
					<?= form_input('hna',$Data->harga_dasar,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
				<div class="col-lg-4">
					<label>Margin</label>
					<?= form_input('margin',$Data->margin,array('class' => 'form-control','required' => 'true','maxlength' => '8')) ?>
				</div>
				<div class="col-lg-4">
					<label>Golongan Obat</label>
					<select name="gol_obat" class="select5 form-control" required="true">
						<option value="">== Pilih Golongan Obat ==</option>
						<option value="Generik" <?php if($Data->golongan_obat=="Generik") : echo "selected"; endif; ?>>Generik</option>
						<option value="Non Generik" <?php if($Data->golongan_obat=="Non Generik") : echo "selected"; endif; ?>>Non Generik</option>
						<option value="Alkes" <?php if($Data->golongan_obat=="Alkes") : echo "selected"; endif; ?>>Alkes</option>
					</select>
				</div>
				<div class="col-lg-4">
					<label>Jenis Obat</label>
					<select name="jenis_obat" class="select6 form-control" required="true">
						<option value="">== Pilih Jenis Obat ==</option>
						<?php foreach($Jenis as $d) : ?>
							<option value="<?= $d->id_jenis ?>" <?php if($d->id_jenis==$Data->id_jenis) : echo "selected"; endif; ?>><?= $d->nm_jenis ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<div class="col-lg-6">
					<label>Formularium</label>
					<select name="formularium" class="select7 form-control" required="true">
						<option value="">== Formularium ==</option>
						<option value="1" <?php if($Data->formularium==true) : echo "selected"; endif; ?>>Ya</option>
						<option value="0" <?php if($Data->formularium==false) : echo "selected"; endif; ?>>Tidak</option>
					</select>
				</div>
				<div class="col-lg-6">
					<label>Konsinyasi</label>
					<select name="konsinyasi" class="select8 form-control" required="true">
						<option value="">== Konsinyasi ==</option>
						<option value="1" <?php if($Data->konsinyasi==true) : echo "selected"; endif; ?>>Ya</option>
						<option value="0" <?php if($Data->formularium==false) : echo "selected"; endif; ?>>Tidak</option>
					</select>
				</div>
				<div class="col-lg-6">
					<label>Dosis</label>
					<textarea name="dosis" class="form-control" rows="2" value="<?= $Data->dosis ?>"></textarea>
				</div>
				<div class="col-lg-6">
					<label>Komposisi</label>
					<textarea name="komposisi" class="form-control" rows="2" value="<?= $Data->komposisi ?>"></textarea>
				</div>
				<div class="col-lg-6">
					<label>Indikasi</label>
					<textarea name="indikasi" class="form-control" rows="2" value="<?= $Data->indikasi ?>"></textarea>
				</div>
				<div class="col-lg-6">
					<label>Efek Samping</label>
					<textarea name="efek_samping" class="form-control" rows="2" value="<?= $Data->efek_samping ?>"></textarea>
				</div>
				<div class="col-lg-6">
					<label>Stok Maksimum</label>
					<?= form_input('stok_max',$Data->stok_maksimum,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
				<div class="col-lg-6">
					<label>Stok Minimum</label>
					<?= form_input('stok_min',$Data->stok_minimum,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
				<div class="col-lg-6">
					<label>Stok Tersedia</label>
					<?= form_input('stok_tersedia',$Data->stok_tersedia,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
				</div>
			</div>
			<br />
			<button type="submit">Simpan</button> <button type="button" onclick="window.close()">Tutup</button>
		<?= form_close() ?>
	</div>
	<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/bootstrap/js/bootstrap.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.slimscroll.js') ?>"></script>
	<script src="<?= base_url('assets/fastclick/lib/fastclick.js') ?>"></script>
	<script src="<?= base_url('assets/adminLTE/js/adminlte.min.js') ?>"></script>
	<script src="<?= base_url('assets/input-mask/jquery.inputmask.xjs') ?>"></script>
	<script src="<?= base_url('assets/input-mask/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/select2/dist/js/select2.full.min.js') ?>"></script>
	<script>
	    window.onunload = refreshParent;
	    function refreshParent() {
	        window.opener.location.reload();
	    }
	    $(function () {
			$('.select2, .select3, .select4, .select5, .select6, .select7, .select8').select2();
		})
	</script>
<?php endif; ?>