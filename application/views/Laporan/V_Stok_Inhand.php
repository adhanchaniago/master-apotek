<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
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
									<th>Nama Barang</th>
									<th>Stok Awal</th>
									<th>Masuk</th>
									<th>Keluar</th>
									<th>Sisa</th>
									<!-- <th>Harga Beli + PPN</th>
									<th>Total</th> -->
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div id="hist_masuk" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Kartu Stok Masuk</h4>
						</div>
						<form action="" method="POST" id="verif" role="form">
							<div class="modal-body">
								<table id="tbMasuk" class="table table-striped table-bordered">
									<thead>
										<th>No.</th>
										<th>Nama Barang</th>
										<th>Pembelian</th>
										<th>Opname</th>
										<th>Tanggal</th>
										<th>Stok</th>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="hist_keluar" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Kartu Stok Keluar</h4>
						</div>
						<form action="" method="POST" id="verif" role="form">
							<div class="modal-body">
								<table id="tbKeluar" class="table table-striped table-bordered">
									<thead>
										<th>No.</th>
										<th>Nama Barang</th>
										<th>Penjualan</th>
										<th>Opname</th>
										<th>Tanggal</th>
										<th>Stok</th>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div id="hist_sisa" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-lg">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Kartu Stok Sisa</h4>
						</div>
						<form action="" method="POST" id="verif" role="form">
							<div class="modal-body">
								<table id="tbSisa" class="table table-striped table-bordered">
									<thead>
										<th>No.</th>
										<th>Nama Barang</th>
										<th>Pembelian</th>
										<th>Penjualan</th>
										<th>Opname</th>
										<th>Tanggal</th>
										<th>Stok</th>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
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
					"url": "<?= base_url('stok/list_report_stok_limit') ?>",
					"type": "POST"
				},
				//dom: 'Bfrtip',
				buttons: [ 'print' ],
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
			var tbSisa = $('#tbSisa').DataTable({
				"processing": true,
				initComplete : function () {
    				tbSisa.buttons().container().appendTo( $('#dtTable_wrapper .col-sm-6:eq(0)'));
				},
				"ajax": {
					"url": "<?= base_url('stok/list_report_stok_sisa/null') ?>",
					"type": "POST"
				},
				//dom: 'Bfrtip',
				buttons: [ 'print' ],
				"autoWidth": false,
				"responsive": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 10,
				"lengthChange": false,
				"searching": true
			});
			$('#tbSisa').DataTable().buttons().container().appendTo( '#dtTable_wrapper .col-sm-6:eq(0)' );
			var tbMasuk = $('#tbMasuk').DataTable({
				"processing": true,
				initComplete : function () {
    				tbMasuk.buttons().container().appendTo( $('#dtTable_wrapper .col-sm-6:eq(0)'));
				},
				"ajax": {
					"url": "<?= base_url('stok/list_report_stok_masuk/null') ?>",
					"type": "POST"
				},
				//dom: 'Bfrtip',
				buttons: [ 'print' ],
				"autoWidth": true,
				"responsive": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 10,
				"lengthChange": false,
				"searching": true
			});
			$('#tbMasuk').DataTable().buttons().container().appendTo( '#dtTable_wrapper .col-sm-6:eq(0)' );
			var tbKeluar = $('#tbKeluar').DataTable({
				"processing": true,
				initComplete : function () {
    				tbKeluar.buttons().container().appendTo( $('#dtTable_wrapper .col-sm-6:eq(0)'));
				},
				"ajax": {
					"url": "<?= base_url('stok/list_report_stok_keluar/null') ?>",
					"type": "POST"
				},
				//dom: 'Bfrtip',
				buttons: [ 'print' ],
				"autoWidth": true,
				"responsive": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 10,
				"lengthChange": false,
				"searching": true
			});
			$('#tbKeluar').DataTable().buttons().container().appendTo( '#dtTable_wrapper .col-sm-6:eq(0)' );
		});
		$(document).on('click','#getmasuk',function() {
			var id_barang = $(this).attr("data");
			$('#hist_masuk').modal('show');
			Pace.track(function(){
				$('#tbSisa').DataTable().ajax.url('<?= base_url('stok/list_report_stok_masuk/') ?>'+id_barang).load();
			});
		});
		$(document).on('click','#getkeluar',function() {
			var id_barang = $(this).attr("data");
			$('#hist_keluar').modal('show');
			Pace.track(function(){
				$('#tbSisa').DataTable().ajax.url('<?= base_url('stok/list_report_stok_keluar/') ?>'+id_barang).load();
			});
		});
		$(document).on('click','#getsisa',function() {
			var id_barang = $(this).attr("data");
			$('#hist_sisa').modal('show');
			Pace.track(function(){
				$('#tbSisa').DataTable().ajax.url('<?= base_url('stok/list_report_stok_sisa/') ?>'+id_barang).load();
			});
		});
		$('#tgl_awal').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    $('#tgl_akhir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>