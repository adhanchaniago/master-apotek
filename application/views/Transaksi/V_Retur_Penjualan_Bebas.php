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
							<span class="pull-left">
								<i class="fa fa-bars"></i>
								<h3 class="box-title">
									List <?= $Title ?>
								</h3>
							</span>
							<span class="pull-right">
								<a href="<?= base_url() ?>" class="btn btn-xs btn-danger">Keluar</a>
							</span>
						</div>
						<div class="box-body">
							<table id="dtTable" class="table table-striped table-bordered">
								<thead>
									<th>No.</th>
									<th>Kode</th>
									<th>Kasir</th>
									<th>Nama</th>
									<th>Tanggal</th>
									<th>Grandtotal</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div id="hist_masuk" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<form class="form-inline" action="" method="POST" id="retur" role="form">
							<div class="modal-header">
								<span class="pull-left">
									<label><span id="kodetr"></span></label>
								</span>
								<span class="pull-right">
									<label><?= date('d-m-Y') ?></label>
								</span>
							</div>
							<div class="modal-body">
								<table id="dtDetail" class="table table-striped table-bordered">
									<thead>
										<th>No.</th>
										<th>Kode Barang</th>
										<th>Nama Barang</th>
										<th>Batch</th>
										<th>Qty</th>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="modal-footer">
								<div class="pull-left" style="padding-top:6px;padding-bottom:6px;">
									<label>Total Kembali : <span id="kembali">0</span></label>
								</div>
								<div class="pull-right">
									<div class="form-group">
										<?= form_input('keterangan',null,array(
											'id' => 'keterangan',
											'class' => 'form-control',
											'placeholder' =>'Keterangan',
											'required' => 'true'
										)) ?>
									</div>
									<button type="submit" id="trid" class="btn btn-danger">Retur</button>
								</div>
							</div>
						</form>
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
					"url": "<?= base_url('penjualan/list_data_retur') ?>",
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
			$('#dtTable').DataTable().buttons().container().appendTo( '#dtTable_wrapper .col-sm-6:eq(0)' );
			var dtDetail = $('#dtDetail').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('penjualan/list_detail_retur/null') ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": false,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			$("#retur").submit(function(e) {
			event.preventDefault();
				var keterangan = $("#keterangan").val();
				var kode = $("#trid").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/retur_transaksi/') ?>"+kode,
						dataType: 'json',
						data: {
								keterangan : keterangan
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$('#hist_masuk').modal('hide');
							alert(data.status);
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			});
		});
		$(document).on('click','#getdetail',function() {
			var kode = $(this).attr("data");
			$("#trid").val(kode);
			$('#hist_masuk').modal('show');
			$("#kodetr").text(kode);
			Pace.track(function(){
				$('#dtDetail').DataTable().ajax.url('<?= base_url('penjualan/list_detail_retur/') ?>'+kode).load();
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('penjualan/get_subtotal/') ?>"+kode,
				success: function(data){
					if(data){
						var opts = $.parseJSON(data);
						$('#kembali').text(opts.subtotal);
					} else {
						$('#kembali').text("Rp. 0.00");
					}
				}
			});
		});
	</script>
<?php endif ?>