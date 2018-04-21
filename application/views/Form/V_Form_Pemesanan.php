<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
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
						<i class="fa fa-wheelchair	"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama Barang</th>
								<th>Qty</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-pencil"></i>
						<h3 class="box-title">Form <?= $Title ?></h3>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-12">
								<div class="form-group">
									<label>PBF</label>
									<select name="id_pbf" id="id_pbf" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="id_barang" id="id_barang" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="qty">Qty</label>
									<?= form_input('qty',null,array(
																		'id' => 'qty',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-12" style="margin-top: 1.8%;">
								<div class="form-group pull-right">
									<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
									<button type="button" id="Hapus" class="btn btn-danger"><i class="fa fa-minus"></i></button>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="button" id="Simpan" class="btn btn-block btn-primary">Simpan Pesanan</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Destroy" class="btn btn-block btn-danger">Hapus Pesanan</button>
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
					"url": "<?= base_url('pemesanan/list_detail_data/'.$this->uri->segment(3)) ?>",
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
				var qty = $("#qty").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('pemesanan/tambah_data/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								id_barang : id_barang,
								qty : qty
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_pbf").attr("disabled","true");
							$("#register")[0].reset();
							$("#nm_barang").focus();
							$("#id_barang").find('option').remove().end();
							$.ajax({
								type: "GET",
								url: "<?= base_url('barang/get_option') ?>",
								success: function(data){
									var opts = $.parseJSON(data);
									$.each(opts, function(i, d) {
										$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+'</option>');
									});
								}
							});
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
			$.ajax({
				type: "GET",
				url: "<?= base_url('barang/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+'</option>');
					});
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('pbf/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_pbf').append('<option value="'+d.id_pbf+'">'+d.nm_pbf+'</option>');
					});
				}
			});
		});
		$(document).on('click','#getdata',function() {
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pemesanan/get_data/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang).change();
					$("#qty").val(data.qty);
					$("#Hapus").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pemesanan/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#dtTable").DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#register")[0].reset();
					$("#nm_barang").focus();
					$("#id_barang").find('option').remove().end();
					$.ajax({
						type: "GET",
						url: "<?= base_url('barang/get_option') ?>",
						success: function(data){
							var opts = $.parseJSON(data);
							$.each(opts, function(i, d) {
								$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+'</option>');
							});
						}
					});
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#Simpan',function() {
			var id_pbf = $("#id_pbf").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pemesanan/simpan_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_pbf : id_pbf
					},
				success: function(data) {
					window.location.href = "<?= base_url('transaksi/pemesanan') ?>";
				}
			});
		});
		$(document).on('click','#Destroy',function() {
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pemesanan/hapus_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				success: function(data) {
					window.location.href = "<?= base_url('transaksi/pemesanan') ?>";
				}
			});
		});
		$("#id_barang").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		//$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>