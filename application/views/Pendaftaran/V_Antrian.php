<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
	$(function() {
		$('#Racikan').DataTable({
			"pageLength": 5,
			"lengthChange": false,
			"searching": false,
			"scrollY": "350px",
			"scrollCollapse": true
		})
	})
	$(function() {
		$('#NRacikan').DataTable({
			"pageLength": 5,
			"lengthChange": false,
			"searching": false,
			"scrollY": "350px",
			"scrollCollapse": true
		})
	})
	$(function () {
		$('.select2').select2()
	})
</script>
<?php if($Level="IT Support") : ?>
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
						<div class="form-group">
							<label>Cari Obat</label>
							<select class="form-control select2" style="width: 100%;">
								<option selected="selected">Alabama</option>
								<option>Alaska</option>
								<option>California</option>
								<option>Delaware</option>
								<option>Tennessee</option>
								<option>Texas</option>
								<option>Washington</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-primary" style="height:320px;">
					<div class="box-header">
						<span class="pull-left">
							<h4>Antrian Racikan</h4>
						</span>
						<span class="pull-right">
							<h4><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#TAntrian">Daftar Resep Racikan</button></h4>
						</span>
					</div>
					<div class="box-body">
						<table id="Racikan" class="table table-bordered">
							<thead>
								<th>No. Antrian</th>
								<th>Nama Pasien</th>
								<th>Tanggal</th>
								<th>Jam</th>
								<th>Status</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-danger" style="height:320px;">
					<div class="box-header">
						<span class="pull-left">
							<h4>Antrian Non Racikan</h4>
						</span>
						<span class="pull-right">
							<h4><button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#TNAntrian">Daftar Resep Non Racikan</button></h4>
						</span>
					</div>
					<div class="box-body">
						<table id="NRacikan" class="table table-bordered">
							<thead>
								<th>No. Antrian</th>
								<th>Nama Pasien</th>
								<th>Tanggal</th>
								<th>Jam</th>
								<th>Status</th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div id="TAntrian" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					<p>Some text in the modal.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div id="TNAntrian" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					<p>Some text in the modal.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<?php endif ?>	