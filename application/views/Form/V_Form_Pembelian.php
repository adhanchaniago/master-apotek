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
						<span class="pull-left">
							<i class="fa fa-wheelchair"></i>
							<h3 class="box-title">
								List <?= $Title ?>
							</h3>
						</span>
						<span class="pull-right">
							<a href="<?= base_url('transaksi/pembelian') ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
						</span>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama Barang</th>
								<th>Qty</th>
								<th>Subtotal</th>
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
						</span>
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
									<label for="tanggal_jatuh_tempo">Jatuh Tempo</label>
									<?= form_input('tanggal_jatuh_tempo',null,array(
																		'id' => 'tanggal_jatuh_tempo',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		"data-inputmask" => "'alias': 'yyyy-mm-dd",
																		"data-mask" => "true",
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="diskon">Diskon</label>
									<?= form_input('diskon',null,array(
																		'id' => 'diskon',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="id_barang" id="id_barang" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-4">
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
							<div class="col-lg-4">
								<div class="form-group">
									<label for="batch">Batch</label>
									<?= form_input('batch',null,array(
																		'id' => 'batch',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="kadaluarsa">Kadaluarsa</label>
									<?= form_input('kadaluarsa',null,array(
																		'id' => 'kadaluarsa',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="subtotal">Subtotal</label>
									<?= form_input('subtotal',null,array(
																		'id' => 'subtotal',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div clas
							<div class="col-lg-6">
								<div class="form-group">
									<div class="checkbox">
        								<label><input type="checkbox" id="konsinyasi" name="konsinyasi" value="1"> Konsinyasi</label>
      								</div>
								</div>
							</div>
							<div class="col-lg-6" style="margin-top: 1.8%;">
								<div class="form-group pull-right">
									<button type="submit" id="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></button>
									<button type="button" id="Hapus" class="btn btn-xs btn-danger" disabled="true"><i class="fa fa-minus"></i></button>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="button" id="Simpan" class="btn btn-sm btn-block btn-primary">Simpan <?= $Title ?></button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Destroy" class="btn btn-sm btn-block btn-danger">Hapus <?= $Title ?></button>
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
			var kode = "<?= $this->uri->segment(3) ?>";
			if(kode!="") {
				$("#id_pbf").attr("disabled","true");
				$("#tanggal_jatuh_tempo").attr("disabled","true");
				$("#diskon").attr("disabled","true");
				$("#id_barang").attr("disabled","true");
				$("#qty").attr("disabled","true");
				$("#batch").attr("disabled","true");
				$("#kadaluarsa").attr("disabled","true");
				$("#subtotal").attr("disabled","true");
				$("#konsinyasi").attr("disabled","true");
				$("#submit").attr("disabled","true");
				$("#Simpan").attr("disabled","true");
				$.ajax({
					type: "POST",
					url: "<?= base_url('pembelian/get_detail/'.$this->uri->segment(3)) ?>",
					success: function(data){
						var val = $.parseJSON(data);
						$("#tanggal_jatuh_tempo").val(val.tanggal_jatuh_tempo);
						$("#diskon").val(val.diskon);
					}
				});
			}
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('pembelian/list_detail_data/'.$this->uri->segment(3)) ?>",
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
				var subtotal_barang = $("#subtotal").val();
				var tanggal_jatuh_tempo = $("#tanggal_jatuh_tempo").val();
				var batch = $("#batch").val();
				var kadaluarsa = $("#kadaluarsa").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('pembelian/tambah_data/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								id_barang : id_barang,
								qty : qty,
								subtotal_barang : subtotal_barang,
								batch : batch,
								kadaluarsa : kadaluarsa
							},
						complete: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_pbf").attr("readonly","true");
							$("#tanggal_jatuh_tempo").attr("readonly","true");
							$("#diskon").attr("readonly","true");
							$("#id_barang").focus();
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
							$("#batch").val("");
							$("#qty").val("");
							$("#subtotal").val("");
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
				url: "<?= base_url('pembelian/get_data/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang).change();
					$("#qty").val(data.qty);
					$("#batch").val(data.batch);
					$("#kadaluarsa").val(data.kadaluarsa);
					$("#subtotal").val(data.subtotal_barang);
					$("#Hapus").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#dtTable").DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_pbf").attr("readonly","true");
					$("#tanggal_jatuh_tempo").attr("readonly","true");
					$("#diskon").attr("readonly","true");
					$("#id_barang").focus();
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
					$("#qty").val("");
					$("#subtotal").val("");
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#Simpan',function() {
			var id_pbf = $("#id_pbf").val();
			var tanggal_jatuh_tempo = $("#tanggal_jatuh_tempo").val();
			var diskon = $("#diskon").val();
			var konsinyasi = $("#konsinyasi").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/simpan_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_pbf : id_pbf,
						tanggal_jatuh_tempo : tanggal_jatuh_tempo,
						diskon : diskon,
						konsinyasi : konsinyasi
					},
				success: function(data) {
					window.location.href = "<?= base_url('transaksi/pembelian') ?>";
				}
			});
		});
		$(document).on('click','#Destroy',function() {
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/hapus_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				success: function(data) {
					window.location.href = "<?= base_url('transaksi/pembelian') ?>";
				}
			});
		});
		function reload() {
			$("#dtTable").DataTable().ajax.reload();
			$("#Hapus").attr("disabled","true");
			$("#id_pbf").attr("readonly","true");
			$("#tanggal_jatuh_tempo").attr("readonly","true");
			$("#diskon").attr("readonly","true");
			$("#id_barang").focus();
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
			$("#batch").val("");
			$("#qty").val("");
			$("#subtotal").val("");
			$(document).ajaxStart(function() { Pace.restart(); });
		}
		$("#id_barang").select2();
		$('#tanggal_jatuh_tempo').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		$('#kadaluarsa').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>