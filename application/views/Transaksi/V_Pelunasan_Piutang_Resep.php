<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Kasir") : ?>
	<div class="container" style="width: 80%">
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
									<th>Tanggal</th>
									<th>Kode</th>
									<th>Nama Pasien</th>
									<th>Tanggal Penjualan</th>
									<th>Total</th>
									<th>Status</th>
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
						<form class="form-inline" action="" method="POST" id="pay" role="form">
							<div class="modal-body">
								<table id="dtDetail" class="table table-striped table-bordered">
									<thead>
										<th>No.</th>
										<th>Nama</th>
										<th>Sisa Stok</th>
										<th>Qty</th>
										<th>Diskon</th>
										<th>Subtotal</th>
									</thead>
									<tbody></tbody>
								</table>
							</div>
							<div class="modal-footer">
								<div class="pull-left" style="padding-top:6px;padding-bottom:6px;">
								<label>Dibayar : <span id="dibayar">0</span></label> | 
								<label>Sisa : <span id="subtotal">0</span></label>
								</div>
								<div class="pull-right">
									<div class="form-group">
										<?= form_input('bayar',null,array(
											'id' => 'bayar',
											'class' => 'form-control',
											'placeholder' =>'Pembayaran',
											'required' => 'true'
										)) ?>
									</div>
									<button type="submit" id="trid" class="btn btn-primary">Bayar</button>
								</div>
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
					"url": "<?= base_url('resep/list_data_piutang') ?>",
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
			var dtDetail = $('#dtDetail').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('resep/list_detail_data/') ?>",
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
			$("#pay").submit(function(e) {
			event.preventDefault();
				var bayar = $("#bayar").val();
				var kode = $("#trid").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('resep/bayar_piutang/') ?>"+kode,
						dataType: 'json',
						data: {
								bayar : bayar
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
			Pace.track(function(){
				$('#dtDetail').DataTable().ajax.url('<?= base_url('resep/list_detail_data/') ?>'+kode).load();
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('resep/get_sisa_hutang/') ?>"+kode,
				success: function(data){
					if(data){
						var opts = $.parseJSON(data);
						$('#subtotal').text(opts.subtotal);
					} else {
						$('#subtotal').text("Rp. 0.00");
					}
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('resep/get_bayar_hutang/') ?>"+kode,
				success: function(data){
					if(data){
						var opts = $.parseJSON(data);
						$('#dibayar').text(opts.subtotal);
					} else {
						$('#dibayar').text("Rp. 0.00");
					}
				}
			});
		});
		$('#tgl_awal').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    $('#tgl_akhir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>