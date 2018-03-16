<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support") : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
			<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="box box-danger">
			<div class="box-header">
				<div class="col-lg-3">
					<div class="form-group">
						<label>Resep/Non Resep</label>
						<select id="filter" name="filter" class="form-control" onchange="filter()">
							<option value="pendapatan-resep">Resep</option>
							<option value="pendapatan-non-resep">Non Resep</option>
							<option value="">ALL</option>
						</select>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label>Filter</label>
						<select id="racikan" name="filter" class="form-control" onchange="racikan()">
							<option value="">All</option>
							<option value="racikan">Racikan</option>
							<option value="non-racikan">Non Racikan</option>
						</select>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label>Dari</label>
						<?= form_input('tgl1',null,array('class' => 'form-control')) ?>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label>Sampai</label>
						<?= form_input('tgl2',null,array('class' => 'form-control')) ?>
					</div>
				</div>
			</div>
			<div class="box-body">
				<table id="dtTable" class="table table-responsive table-bordered">
					<thead>
						<th>No</th>
						<th>Tanggal</th>
						<th>Nama Pasien</th>
						<th>No. Resep</th>
						<th>Nama Dokter</th>
						<th>Jumlah</th>
						<th>Jenis Resep</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
	<script>
		$(document).ready(function(){
			var table = $('#dtTable').DataTable({
				"processing": true, //Feature control the processing indicator.
				"ajax": {
								"url": "<?php echo site_url('report/ajax_list')?>",
								"type": "POST"
						},
				"autoWidth": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 25,
				"pageChange": false,
				"searching": true,
				"scrollY": 250,
			});
		});
		function filter() {
			var filter = $('#filter').val();
			var url = "<?= base_url('report/') ?>";
			window.location.href = url+filter;
		}
		function racikan() {
			var filter = $('#racikan').val();
			var url = "<?= base_url('report/pendapatan-resep/') ?>";
			window.location.href = url+filter;
		}
	</script>
<?php endif ?>