<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="IT Support" OR $Level=="Owner") : ?>
	<script>
		function cariData() {
			var keywords = $("input[name=keywords]").val();
			var url = "<?= base_url('level/index/') ?>";
  		window.location = url + keywords;
		}
	</script>
	<div class="container" style="width:50%">
		<section class="content-header">
			<h1><?= $Title ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url('administrator') ?>"><i class="fa fa-dashboard"></i> Dashboard Admin</a></li>
				<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
				<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
			</ol>
		</section>
	</div>
	<section class="content">
		<div class="container" style="width:50%">
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-success">
						<div class="box-header">
							<span class="pull-left">
								<?= $links ?>
							</span>
							<span class="pull-right">
								<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add">Tambah <?= $Title ?></button>
							</span>
							<div id="add" class="modal fade" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header bg-blue">
											<h4 class="modal-title">Form Tambah <?= $Title ?></h4>
										</div>
										<?= form_open('level/tambah-data') ?>
										<div class="modal-body">
											<div class="form-group">
												<label>Nama Level</label>
												<?= form_input('nm_level',null,array('class' => 'form-control','maxlength' => '50','required' => 'true')) ?>
											</div>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-primary">Simpan</button>
											<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
										</div>
										<?= form_close() ?>
									</div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<?php if (isset($Data)) : ?>
								<table class="table table-responsive table-bordered table-striped">
									<thead>
										<th class="text-center">Nama Level</th>
										<th></th>
									</thead>
									<tbody>
										<?php foreach($Data as $l) : ?>
											<tr>
												<td class="text-center"><?= $l->nm_level ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_level ?>">Edit</button>
													<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_level ?>">Delete</button>
												</td>
											</tr>
											<div id="edit-<?= $l->id_level ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-yellow">
															<h4 class="modal-title">Form Edit <?= $Title ?></h4>
														</div>
														<?= form_open('level/edit-data/'.$l->id_level) ?>
														<div class="modal-body">
															<div class="form-group">
																<label>Nama Level</label>
																<?= form_input('nm_level',$l->nm_level,array('class' => 'form-control','maxlength' => '50','required' => 'true')) ?>
															</div>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Simpan</button>
															<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
														</div>
														<?= form_close() ?>
													</div>
												</div>
											</div>
										</div>
										<div id="del-<?= $l->id_level ?>" class="modal fade" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header bg-red">
														<h4 class="modal-title">Konfirmasi Hapus <?= $Title ?></h4>
													</div>
													<?= form_open('level/del-data/'.$l->id_level) ?>
													<div class="modal-body">
														<p>Apakah anda yakin ingin menghapus data ini?</p>
													</div>
													<div class="modal-footer">
														<button type="submit" class="btn btn-primary">Ya</button>
														<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
													</div>
													<?= form_close() ?>
												</div>
											</div>
										</div>
										<?php endforeach ?>
									</tbody>
								</table>
							<?php else : ?>
								<div class="text-center">
									Sayangnya, <?= $Title ?> Tidak ditemukan :( <br /><a href="<?= base_url('level') ?>">Kembali</a>
								</div>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif ?>