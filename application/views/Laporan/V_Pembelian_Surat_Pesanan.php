<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
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
						<i class="fa fa-wheelchair"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Tanggal Surat</th>
								<th>Nomor Surat</th>
								<th>Nama Barang</th>
								<th>Pabrik</th>
								<th>PBF</th>
								<th>Qty</th>
								<th>Harga + PPN</th>
								<th>Total</th>
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
					<h3 class="box-title">Form Filter <?= $Title ?></h3>
				</div>
				<!-- <form action="" method="POST" id="filter" role="form"> -->
					<div class="box-body row">
						<div class="col-lg-6">
							<div class="form-group">
								<label for="tgl_awal">Dari Tanggal</label>
								<?= form_input('tgl_awal',null,array(
																		'id' => 'tgl_awal',
																		'class' => 'form-control',
																		//'placeholder' => 'Tanggal Lahir',
																		//'required' => 'true'
																		"data-inputmask" => "'alias': 'yyyy-mm-dd",
																		"data-mask" => "true",
																		'required' => 'true'
																	));
								?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label for="tgl_akhir">Sampai Tanggal</label>
								<?= form_input('tgl_akhir',null,array(
																		'id' => 'tgl_akhir',
																		'class' => 'form-control',
																		//'placeholder' => 'Tanggal Kunjungan',
																		'required' => 'true'
																	));
								?>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="button" id="filter" class="btn btn-block btn-primary">Filter</button>
					</div>
				<!-- </form> -->
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
				/*"ajax": {
					"url": "<?= base_url('pembelian/list_report_surat_pesanan') ?>",
					"type": "POST"
				},*/
				"autoWidth": false,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
		});
		$('#tgl_awal').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    $('#tgl_akhir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>