<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-bars"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama Barang</th>
								<th>Jenis</th>
								<th>Pabrik</th>
								<th>Kemasan</th>
								<th>Satuan</th>
								<th>Golongan</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<span class="pull-left">
							<i class="fa fa-pencil"></i>
							<h3 class="box-title">
								Form <?= $Title ?>
							</h3>
						</span>
						<span class="pull-right">
							<a onclick="reload()" class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i></a>
							<a href="<?= base_url() ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
						</span>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_barang">Kode Barang</label>
									<?= form_input('id_barang',null,array(
																			'id' => 'id_barang',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="nm_barang">Nama Barang</label>
									<?= form_input('nm_barang',null,array(
																			'id' => 'nm_barang',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="id_jenis">Jenis</label>
									<select name="id_jenis" id="id_jenis" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_golongan">Golongan</label>
									<select name="id_golongan" id="id_golongan" class="form-control">
										<option value="Generik">Generik</option>
										<option value="Non Generik">Non Generik</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_kemasan">Kemasan</label>
									<select name="id_kemasan" id="id_kemasan" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_satuan">Satuan</label>
									<select name="id_satuan" id="id_satuan" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="isi_satuan">Isi Satuan</label>
									<?= form_input('isi_satuan',null,array(
																			'id' => 'isi_satuan',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
								<div class="col-lg-6">
									<div class="form-group">
										<label for="id_margin">Margin (%)</label>
										<?= form_input('id_margin',null,array(
																				'id' => 'id_margin',
																				'class' => 'form-control',
																				//'placeholder' => 'Nama Pasien',
																				'required' => 'true'
																			));
										?>
									</div>
								</div>
							<?php endif; ?>
							<div class="<?php if($Level=='Master' OR $Level=='Pemilik') : echo 'col-lg-6'; else : echo 'col-lg-12'; endif; ?>">
								<div class="form-group">
									<label for="harga_dasar">Harga Dasar (+PPN)</label>
									<?= form_input('harga_dasar',null,array(
																			'id' => 'harga_dasar',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="id_pabrik">Pabrik</label>
									<select name="id_pabrik" id="id_pabrik" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="stok_maksimum">Stok Maksimum</label>
									<?= form_input('stok_maksimum',null,array(
																			'id' => 'stok_maksimum',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="stok_minimum">Stok Minimum</label>
									<?= form_input('stok_minimum',null,array(
																			'id' => 'stok_minimum',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<div class="checkbox">
        								<label><input type="checkbox" id="konsinyasi" name="konsinyasi" value="1"> Konsinyasi</label>
      								</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<div class="checkbox">
        								<label><input type="checkbox" id="formularium" name="formularium" value="1"> Formularium</label>
      								</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="submit" class="btn btn-block btn-primary">Simpan</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Hapus" class="btn btn-block btn-danger" disabled="true">Hapus</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('barang/list_all_data') ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": true,
				"ordering": true,
				"paging": true,
				//"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_barang = $("#id_barang").val();
				var nm_barang = $("#nm_barang").val();
				var id_jenis = $("#id_jenis").val();
				var id_golongan = $("#id_golongan").val();
				var id_kemasan = $("#id_kemasan").val();
				var id_satuan = $("#id_satuan").val();
				var isi_satuan = $("#isi_satuan").val();
				var margin = $("#id_margin").val();
				var harga_dasar = $("#harga_dasar").val();
				var id_pabrik = $("#id_pabrik").val();
				var stok_maksimum = $("#stok_maksimum").val();
				var stok_minimum = $("#stok_minimum").val();
				var konsinyasi = $("#konsinyasi").val();
				var formularium = $("#formularium").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('barang/tambah_data/') ?>",
						dataType: 'json',
						data: {
								id_barang : id_barang,
								nm_barang : nm_barang,
								id_jenis : id_jenis,
								id_golongan : id_golongan,
								id_kemasan : id_kemasan,
								id_satuan : id_satuan,
								isi_satuan : isi_satuan,
								margin : margin,
								harga_dasar : harga_dasar,
								id_pabrik : id_pabrik,
								stok_maksimum : stok_maksimum,
								stok_minimum : stok_minimum,
								konsinyasi : konsinyasi,
								formularium : formularium
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_barang").attr("disabled","true");
							$("#register")[0].reset();
							$("#nm_barang").focus();
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
			$.ajax({
				type: "GET",
				url: "<?= base_url('jenis/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_jenis').append('<option value="'+d.id_jenis+'">'+d.nm_jenis+'</option>');
					});
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('kemasan/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_kemasan').append('<option value="'+d.id_kemasan+'">'+d.nm_kemasan+'</option>');
					});
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('satuan/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_satuan').append('<option value="'+d.id_satuan+'">'+d.nm_satuan+'</option>');
					});
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('pabrik/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_pabrik').append('<option value="'+d.id_pabrik+'">'+d.nm_pabrik+'</option>');
					});
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('margin/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_margin').append('<option value="'+d.persentase_margin+'">'+d.nm_margin+'</option>');
					});
				}
			});
		});
		function reload() {
			$("#Hapus").attr("disabled","true");
			$("#id_barang").attr("disabled","true");
			$("#register")[0].reset();
			$("#nm_barang").focus();
		}
		$(document).on('click','#getdata',function() {
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('barang/get_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang);
					$("#nm_barang").val(data.nm_barang);
					$("#id_jenis").val(data.id_jenis).change();
					$("#id_golongan").val(data.id_golongan).change();
					$("#id_kemasan").val(data.id_kemasan).change();
					$("#id_satuan").val(data.id_satuan).change();
					$("#isi_satuan").val(data.isi_satuan);
					$("#id_margin").val(data.margin).change();
					$("#harga_dasar").val(data.harga_dasar);
					$("#id_pabrik").val(data.id_pabrik).change();
					$("#stok_maksimum").val(data.stok_maksimum);
					$("#stok_minimum").val(data.stok_minimum);
					if(data.konsinyasi) {
						$('#konsinyasi').prop('checked', true);
					}
					if(data.formularium) {
						$('#formularium').prop('checked', true);
					}
					$("#Hapus").removeAttr("disabled");
					//$("#id_barang").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('barang/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_barang").attr("disabled","true");
					$('#register')[0].reset();
					$('#nm_barang').focus();
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$("#id_jenis").select2();
		$("#id_golongan").select2();
		$("#id_kemasan").select2();
		$("#id_satuan").select2();
		// $("#id_margin").select2();
		$("#id_pabrik").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		//$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>