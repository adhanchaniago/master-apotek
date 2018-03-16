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
				<div class="col-lg-6">
					<div class="form-group">
						<label>Filter</label>
						<select name="filter" class="form-control">
							<option value="">All</option>
							<option value="narkotika">Narkotika</option>
							<option value="psikotropika">Psikotropika</option>
							<option value="perkusor">Perkusor</option>
							<option value="bebas">Bebas</option>
							<option value="bebas-terbatas">Bebas Terbatas</option>
							<option value="keras">Keras</option>
							<option value="alkes">Alkes</option>
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
						<th>No. Transaksi</th>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>harga Jual</th>
						<th>Total Harga</th>
						<th>Alamat</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
	<script>
		$(document).ready(function(){
			var table = $('#dtTable').DataTable({
				/*"processing": true, //Feature control the processing indicator.
				"ajax": {
								"url": "<?php echo site_url('barang/ajax_list')?>",
								"type": "POST"
						},*/
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
	</script>
<?php endif ?>