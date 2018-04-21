<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level=="Apoteker") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-money"></i>
						<h3 class="box-title">Transaksi Tanggal <?= date('d/m/Y') ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTransaksi" class="table table-striped table-bordered">
							<thead>
								<th>Nomor Transaksi</th>
								<th>Tanggal</th>
								<th>Subtotal</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
					<div class="box-footer">
						<span id="subtotal" class="label label-danger pull-right" style="font-size: 1.5em;display: inline;">
								<?= 'Rp. '.number_format($Subtotal,2,",",".") ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll">
			<a id="down" href="#up">
				<i class="fa fa-4x fa-angle-up"></i>
			</a>
		</div>
	</section>
	<script>
		$(document).ready(function(){
			var dtTransaksi = $('#dtTransaksi').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('penjualan/list-penjualan/') ?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": false,
				"ordering": false,
				"paging": false,
				"pageLength": 5,
				"lengthChange": false,
				"searching": true
			});
		});
	</script>
<?php endif ?>