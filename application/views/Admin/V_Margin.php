<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="IT Support" OR $Level=="Owner") : ?>
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
										<?= form_open('margin/tambah-data') ?>
										<div class="modal-body row">
											<div class="col-lg-6">
												<div class="form-group">
													<label>Nama Margin</label>
													<?= form_input('nm_margin',null,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Ketentuan (%)</label>
													<?= form_input('ketentuan',null,array('class' => 'form-control','maxlength' => '3		','required' => 'true')) ?>
												</div>
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
								<table class="table table-responsive table-striped">
									<thead>
										<th>No.</th>
										<th>Nama Margin</th>
										<th>Ketentuan (%)</th>
										<th></th>
									</thead>
									<tbody>
										<?php $i=1;foreach($Data as $l) : ?>
											<tr>
												<td><?= $i++ ?></td>
												<td><?= $l->nm_margin ?></td>
												<td><?= $l->harga_margin ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_margin ?>">Edit</button>
													<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_margin ?>">Delete</button>
												</td>
											</tr>
											<div id="edit-<?= $l->id_margin ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-yellow">
															<h4 class="modal-title">Form Edit <?= $Title ?></h4>
														</div>
														<?= form_open('margin/edit-data/'.$l->id_margin) ?>
														<div class="modal-body row">
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Nama Margin</label>
																	<?= form_input('nm_margin',$l->nm_margin,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Ketentuan (%)</label>
																	<?= form_input('ketentuan',$l->harga_margin,array('class' => 'form-control','maxlength' => '3','required' => 'true')) ?>
																</div>
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
											<div id="del-<?= $l->id_margin ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-red">
															<h4 class="modal-title">Konfirmasi Menghapus <?= $Title ?></h4>
														</div>
														<?= form_open('margin/del-data/'.$l->id_margin) ?>
														<div class="modal-body">
															<p>Anda yakin ingin menghapus data ini?</p>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Hapus</button>
															<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
														</div>
														<?= form_close() ?>
													</div>
												</div>
											</div>
										</div>
										<?php endforeach ?>
									</tbody>
								</table>
							<?php else : ?>
								<div>Data Margin belum terisi.</div>
							<?php endif ?>
						</div>
						<div class="box-footer">
							<center>
								<?php 
									if(isset($Data)) {
										echo $links;
									}
								?>
							</center>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script>
		$('#tgl_lahir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' });
		$('#e_tgl_lahir').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>