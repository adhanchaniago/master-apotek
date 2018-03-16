<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
	$(function() {
		$('#TableData').DataTable({
			"pageLength": 5,
			//"lengthChange": false,
			//"searching": false,
			"scrollY": "265px",
			"scrollCollapse": true
		})
	})
</script>
<?php if($Level="IT Support") : ?>
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
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="box box-success">
							<div class="box-header">
								<center>
									<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">Tambah <?= $Title ?></button>
								</center>
								<div id="add" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Form Tambah <?= $Title ?></h4>
											</div>
											<?= form_open('gudang/add-jenis-obat') ?>
											<div class="modal-body">
												<div class="row">
													<div class="col-lg-6">
														<label>Nama Jenis</label>
														<?= form_input('nm_jenis',null,array('class' => 'form-control')) ?>
													</div>
													<div class="col-lg-6">
														<label>Harga Margin</label>
														<?= form_input('hrg_margin',null,array('class' => 'form-control')) ?>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button class="submit" class="btn btn-primary">Simpan</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>
											<?= form_close() ?>
										</div>
									</div>
								</div>
							</div>
							<div class="box-body">
								<table id="TableData" class="table table-bordered">
									<thead>
										<th>No.</th>
										<th>Nama Jenis Obat</th>
										<th></th>
									</thead>
									<tbody>
										<?php $i=1;foreach($GolObat as $l) : ?>
											<tr>
												<td><?= $i++ ?></td>
												<td><?= $l->nm_kategori ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_kategori ?>">Edit</button>
													<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_kategori ?>">Delete</button>
												</td>
											</tr>
											<div id="edit-<?= $l->id_kategori ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title">Modal Header</h4>
														</div>
														<?= form_open('gudang/edit-jenis-obat/'.$l->id_kategori) ?>
														<div class="modal-body">
															<div class="row">
																<div class="col-lg-6">
																	<label>Nama Jenis</label>
																	<?= form_input('nm_jenis',$l->nm_kategori,array('class' => 'form-control')) ?>
																</div>
																<div class="col-lg-6">
																	<label>Harga Margin</label>
																	<?= form_input('hrg_margin',$l->harga_margin,array('class' => 'form-control')) ?>
																</div>
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
											<div id="del-<?= $l->id_kategori ?>" class="modal fade" role="dialog">
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
										</div>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php endif ?>