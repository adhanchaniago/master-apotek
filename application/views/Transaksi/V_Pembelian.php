<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
	<div class="container">
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
				<div class="col-lg-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<i class="fa fa-wheelchair"></i>
							<h3 class="box-title">List <?= $Title ?></h3>
						</div>
						<div class="box-body">
							<table id="dtTable" class="table table-striped table-bordered">
								<thead>
									<th>No.</th>
									<th>Kode</th>
									<th>PBF</th>
									<th>Tanggal Beli</th>
									<th>Jatuh Tempo</th>
									<th>Subtotal</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="box-footer">
							<span class="pull-right">
								<a href="<?= base_url('pembelian/buat-data-pembelian') ?>" class="btn btn-primary">Buat Data Pembelian</a>
							</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script>
		$(document).ready(function(){
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('pembelian/list_all_data') ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
		});
	</script>
<?php endif ?>