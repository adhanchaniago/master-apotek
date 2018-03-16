<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support" OR $Level="Owner") : ?>
	<div class="container" style="width: 60%">
		<section class="content-header">
			<h1><?= $Title ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a href="<?=base_url('barang') ?>"><?= $Nav ?></a></li>
				<li class="active"><?= $Title ?></li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-danger">
						<div class="box-header row">
							<?php if($links!=NULL) : ?>
								<div class="col-lg-6 text-right">
									<?= $links ?>
								</div>
								<div class="col-lg-6">
									<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">Tambah <?= $Title ?></button>
								</div>
							<?php else : ?>
								<div class="col-lg-6">
									<a href="<?= base_url('dashboard') ?>" class="btn btn-danger btn-sm">Tutup</a>
								</div>
								<div class="col-lg-6">
									<button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#add">Tambah <?= $Title ?></button>
								</div>
							<?php endif ?>
							<div id="add" class="modal fade" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header bg-blue">
											<h4 class="modal-title">Form Tambah <?= $Title ?></h4>
										</div>
										<?= form_open('pabrik/tambah-data') ?>
										<div class="modal-body">
											<div class="row">
												<?php if($Level="IT Support" OR $Level="Owner") : ?>
													<div class="col-lg-12">
														<label>Nama Pabrik</label>
														<?= form_input('nm_pabrik',null,array('class' => 'form-control','placeholder' => 'Nama Pabrik')) ?>
													</div>
												<?php else : ?>
													<div class="col-lg-12">
														<label>Nama Pabrik</label>
														<?= form_input('nm_pabrik',null,array('class' => 'form-control','placeholder' => 'Nama Pabrik')) ?>
													</div>
												<?php endif ?>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Simpan</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>
										<?= form_close() ?>
									</div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<table id="dtTable" class="table table-bordered">
								<thead>
									<th>Kode Pabrik</th>
									<th>Nama Pabrik</th>
									<th></th>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script>
		$(document).ready(function(){
			var table = $('#dtTable').DataTable({
				"ajax": '<?= base_url("pabrik/getdata/") ?>',
				"autoWidth": true,
				"info": true,
				"ordering": true,
				"paging": true,
				"searching": true,
				"pageLength": 5,
				"columnDefs": [
					{
					"targets": -1,
					"data": null,
					"defaultContent": 
						"<center><div class='btn-group'><button type='button' id='edit' class='btn btn-warning btn-xs'>Edit</button><button type='button' id='delete' class='btn btn-danger btn-xs'>Hapus</button></div></center>"

					}
				]
			});
			$('#dtTable tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("Apakah anda yakin ingin menghapus barang ini?");
				if (confirmation) {
					window.location.href = "<?= base_url('pabrik/del-data/') ?>"+id;
				}
			});
			$('#dtTable tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var url = "<?= base_url('pabrik/edit-data/') ?>"+id
				popupWindow = window.open(
					url,
					'popUpWindow','height=300,width=450,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes'
				)
			});
		});
	</script>
<?php endif ?>