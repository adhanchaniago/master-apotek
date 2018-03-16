<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support") : ?>
	<script>
		$(document).ready(function(){
			var table = $('#PBFlist').DataTable({
				"ajax": '<?= base_url("pesanan/GetData/") ?>',
				"autoWidth": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"searching": true,
				"columnDefs": [{
					"targets": -1,
					"data": null,
					"defaultContent": 
						"<center><div class='btn-group'><button type='button' id='edit' class='btn btn-warning btn-xs'>Edit</button><button type='button' id='delete' class='btn btn-danger btn-xs'>Hapus</button></div></center>"

				}]
			});
			$('#PBFlist tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("are you sure you want to remove the item?");
				if (confirmation) {
					window.location.href = "<?= base_url('pesanan/del-data/') ?>"+id;
				}
			});
			$('#PBFlist tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("are you sure you want to edit the item?");
				if (confirmation) {
					window.location.href = "<?= base_url('pesanan/buat-surat-pesanan/') ?>"+id;
				}
			});
		});
	</script>
	<div class="container" style="width: 50%">
		<section class="content-header">
			<h1><?= $Title ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url('administrator') ?>"><i class="fa fa-dashboard"></i> Dashboard Admin</a></li>
				<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
				<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-success">
						<div class="box-header">
							<center>
								<a href="<?= base_url('pesanan/buat-surat-pesanan') ?>" class="btn btn-primary btn-sm">Buat <?= $Title ?></a>
							</center>
						</div>
						<div class="box-body">
							<table id="PBFlist" class="table table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<th>Kode SP</th>
									<th>PBF</th>
									<th></th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php endif ?>