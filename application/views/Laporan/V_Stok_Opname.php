<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
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
									<th>Nama Barang</th>
									<th>Sisa</th>
									<th>Fisik</th>
									<th>Penyesuaian</th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
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
					"url": "<?= base_url('stok/list_report_stok_opname') ?>",
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
		//$('#tgl_awal').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tgl_akhir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>