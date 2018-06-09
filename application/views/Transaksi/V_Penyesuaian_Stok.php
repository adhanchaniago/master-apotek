<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li> <?= $Nav ?></li>
			<li class="active"> <?= $Title ?></li>
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
								<th class="text-center">Stok Sisa</th>
								<th class="text-center">Stok Fisik</th>
								<th class="text-center">Penyesuaian Stok</th>
								<th class="text-center">Penyesuaian Keuangan</th>
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
							<a href="<?= base_url() ?>" class="btn btn-xs btn-danger">Keluar</a>
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
																			//'placeholder' => 'Tanggal Lahir',
																			//'required' => 'true'
																			//"data-inputmask" => "'alias': 'yyyy-mm-dd",
																			//"data-mask" => "true",
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
																			//'placeholder' => 'Tanggal Kunjungan',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="stok_fisik">Stok Fisik</label>
									<?= form_input('stok_fisik',null,array(
																			'id' => 'stok_fisik',
																			'class' => 'form-control',
																			//'placeholder' => 'Tanggal Kunjungan',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" id="Sesuaikan" class="btn btn-block btn-primary" disabled="true">Sesuaikan</button>
						</div>
					</form>
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
				initComplete : function () {
    				dtTable.buttons().container().appendTo( $('#dtTable_wrapper .col-sm-6:eq(0)'));
				},
				"ajax": {
					"url": "<?= base_url('stok/list_report_stok_opname') ?>",
					"type": "POST"
				},
				//dom: 'Bfrtip',
				//buttons: [ 'print' ],
				"autoWidth": true,
				"responsive": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 10,
				"lengthChange": false,
				"searching": true
			});
			$('#dtTable').DataTable().buttons().container().appendTo( '#dtTable_wrapper .col-sm-6:eq(0)' );
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_barang = $("#id_barang").val();
				var stok_fisik = $("#stok_fisik").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('stok/penyesuaian/') ?>",
						dataType: 'json',
						data: {
								id_barang : id_barang,
								stok_fisik : stok_fisik,
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Sesuaikan").attr("disabled","true");
							$("#register")[0].reset();
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			});
		});
		$(document).on('click','#getdata',function() {
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('stok/get_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang);
					$("#nm_barang").val(data.nm_barang);
					$("#stok_fisik").removeAttr("disabled");
					$("#Sesuaikan").removeAttr("disabled");
				}
			});
		});
		function reload() {
			$("#Sesuaikan").attr("disabled","true");
			$("#stok_fisik").attr("disabled","true");
			$("#register")[0].reset();
		}
		//$('#tgl_awal').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tgl_akhir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>