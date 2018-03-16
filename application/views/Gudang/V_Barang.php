<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administrator') ?>"><i class="fa fa-dashboard"></i> Dashboard Admin</a></li>
			<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
			<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-12">
				<div class="box box-success">
					<div class="box-body">
						<table id="dtTable" class="table table-bordered">
							<thead>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Jenis Obat</th>
								<th>Pabrik</th>
								<th>Golongan Obat</th>
								<th>Isi Kemasan</th>
								<th class="text-center">HNA + PPN</th>
								<th></th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="box-footer">
						<span class="pull-right">
							<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">Tambah <?= $Title ?></button>
						</span>
						<span class="pull-left">
							<a href="<?= base_url('dashboard') ?>" class="btn btn-danger btn-sm">Kembali</a>
						</span>
						<div id="add" class="modal fade" role="dialog">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header bg-blue">
										<h4 class="modal-title">Form Tambah <?= $Title ?></h4>
									</div>
									<?= form_open('barang/tambah-data') ?>
										<div class="modal-body">
											<div class="row">
												<div class="col-lg-6">
													<label>Nama Barang</label>
													<?= form_input('nm_barang',null,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
												</div>
												<div class="col-lg-6">
													<label>Nama Pabrik</label>
													<select name="nm_pabrik" class="form-control" required="true">
															<option value="">== Pilih Pabrik ==</option>
															<?php foreach($Pabrik as $d) : ?>
																<option value="<?= $d->id_pabrik ?>"><?= $d->nm_pabrik ?></option>
															<?php endforeach ?>
													</select>
												</div>
												<div class="col-lg-4">
													<label>Kemasan</label>
													<select name="kemasan" class="form-control" required="true">
														<option value="">== Pilih Kemasan ==</option>
														<?php foreach($Kemasan as $d) : ?>
															<option value="<?= $d->id_kemasan ?>"><?= $d->nm_kemasan ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-lg-4">
													<label>Isi Kemasan</label>
													<?= form_input('isi_kemasan',null,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
												</div>
												<div class="col-lg-4">
													<label>Satuan</label>
													<select name="satuan" class="form-control" required="true">
														<option value="">== Pilih Satuan ==</option>
														<?php foreach($Satuan as $d) : ?>
															<option value="<?= $d->id_satuan ?>"><?= $d->nm_satuan ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-lg-4">
													<label>HNA</label>
													<?= form_input('hna',null,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
												</div>
												<div class="col-lg-4">
													<label>Golongan Obat</label>
													<select name="gol_obat" class="form-control" required="true">
														<option value="">== Pilih Golongan Obat ==</option>
														<option value="Generik">Generik</option>
														<option value="Non Generik">Non Generik</option>
														<option value="Alkes">Alkes</option>
													</select>
												</div>
												<div class="col-lg-4">
													<label>Jenis Obat</label>
													<select name="jenis_obat" class="form-control" required="true">
														<option value="">== Pilih Jenis Obat ==</option>
														<?php foreach($Jenis as $d) : ?>
															<option value="<?= $d->id_kategori ?>"><?= $d->nm_kategori ?></option>
														<?php endforeach ?>
													</select>
												</div>
												<div class="col-lg-6">
													<label>Formularium</label>
													<select name="formularium" class="form-control" required="true">
														<option value="">== Formularium ==</option>
														<option value="1">Ya</option>
														<option value="0">Tidak</option>
													</select>
												</div>
												<div class="col-lg-6">
													<label>Konsinyasi</label>
													<select name="konsinyasi" class="form-control" required="true">
														<option value="">== Konsinyasi ==</option>
														<option value="1">Ya</option>
														<option value="0">Tidak</option>
													</select>
												</div>
												<div class="col-lg-6">
													<label>Dosis</label>
													<textarea name="dosis" class="form-control" rows="2"></textarea>
												</div>
												<div class="col-lg-6">
													<label>Komposisi</label>
													<textarea name="komposisi" class="form-control" rows="2"></textarea>
												</div>
												<div class="col-lg-6">
													<label>Indikasi</label>
													<textarea name="indikasi" class="form-control" rows="2"></textarea>
												</div>
												<div class="col-lg-6">
													<label>Efek Samping</label>
													<textarea name="efek_samping" class="form-control" rows="2"></textarea>
												</div>
												<div class="col-lg-6">
													<label>Stok Maksimum</label>
													<?= form_input('stok_max',null,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
												</div>
												<div class="col-lg-6">
													<label>Stok Minimum</label>
													<?= form_input('stok_min',null,array('class' => 'form-control','required' => 'true','maxlength' => '50')) ?>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Simpan</button>
											<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
										</div>
									<?= form_close() ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		$(document).ready(function(){
			var table = $('#dtTable').DataTable({
				"processing": true, //Feature control the processing indicator.
				"ajax": {
								"url": "<?php echo site_url('barang/ajax_list')?>",
								"type": "POST"
						},
				"autoWidth": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 25,
				"pageChange": false,
				"searching": true,
				"scrollY": 250,
				"columnDefs": [
					{
					"targets": -1,
					"data": null,
					"defaultContent": 
						"<center><div class='btn-group'><button type='button' id='edit' class='btn btn-warning btn-xs'>Edit</button><button type='button' id='delete' class='btn btn-danger btn-xs'>Hapus</button></div></center>"

					},
					{
										"targets": -8,
										"visible": false,
										"searchable": false
								},
				]
			});
			$('#dtTable tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("Apakah anda yakin ingin menghapus barang ini?");
				if (confirmation) {
					alert(id);
				}
			});
			$('#dtTable tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var url = "<?= base_url('barang/edit-data/') ?>"+id;
				popupWindow = window.open(
					url,
					'popUpWindow','height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes'
				)
			});
		});
	</script>
<?php endif ?>	