<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Gudang") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-cube"></i>
						<h3 class="box-title">Saldo Stok Tersedia</h3>
					</div>
					<div class="box-body">
						<table id="dtStok" class="table table-striped table-bordered">
							<thead>
								<th>Nama Barang</th>
								<th>Stok Minimum</th>
								<th>Sisa Stok</th>
								<th>Status</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-exclamation-triangle"></i>
						<h3 class="box-title">Barang Expired</h3>
					</div>
					<div class="box-body">
						<table id="dtExpire" class="table table-striped table-bordered">
							<thead>
								<th>Nama Barang</th>
								<th>Batch</th>
								<th>Tanggal Kadaluarsa</th>
								<th>Sisa Hari</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-credit-card"></i>
						<h3 class="box-title">Hutang Jatuh Tempo</h3>
					</div>
					<div class="box-body">
						<table id="dtHutang" class="table table-striped table-bordered">
							<thead>
								<th>Nomor Faktur</th>
								<th>Nama PBF</th>
								<th>Tanggal Pembelian</th>
								<th>Tanggal Jatuh Tempo</th>
								<th>Sisa Hari</th>
							</thead>
							<tbody></tbody>
						</table>
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
	<script src="<?= base_url('assets/flot/jquery.flot.js') ?>"></script>
	<script src="<?= base_url('assets/flot/jquery.flot.categories.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtStok = $('#dtStok').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('penjualan/data_stok_tersedia/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": true,
				"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			var dtExpire = $('#dtExpire').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('penjualan/data_barang_expired/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": true,
				"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			var dtHutang = $('#dtHutang').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('penjualan/data_jatuh_tempo/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": true,
				"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
		});
		/*
		 * BAR CHART
		 * ---------
		 */

		var bar_data = {
			data : [['Januari', 10], ['Februari', 8], ['Maret', 4], ['April', 13], ['Mei', 17], ['Juni', 18], ['Juli', 9], ['Agustus', 9], ['September', 9], ['Oktober', 9], ['November', 9], ['Desember', 9]],
			color: '#3c8dbc'
		}
		$.plot('#bar-chart', [bar_data], {
			grid  : {
				borderWidth: 1,
				borderColor: '#f3f3f3',
				tickColor  : '#f3f3f3'
			},
			series: {
				bars: {
					show    : true,
					barWidth: 0.5,
					align   : 'center'
				}
			},
			xaxis : {
				mode      : 'categories',
				tickLength: 0
			}
		})
		/* END BAR CHART */
	</script>
<?php endif ?>