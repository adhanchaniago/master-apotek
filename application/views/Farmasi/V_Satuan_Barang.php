<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support" OR $Level="Owner") : ?>
	<div class="container" style="width: 50%">
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
										<?= form_open('satuan/tambah-data') ?>
										<div class="modal-body">
											<div class="row">
												<?php if($Level="IT Support" OR $Level="Owner") : ?>
													<div class="col-lg-12">
														<label>Nama Satuan</label>
														<?= form_input('nm_satuan',null,array('class' => 'form-control','placeholder' => 'Nama Satuan')) ?>
													</div>
												<?php else : ?>
													<div class="col-lg-12">
														<label>Nama Satuan</label>
														<?= form_input('nm_satuan',null,array('class' => 'form-control','placeholder' => 'Nama Satuan')) ?>
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
							<?php if (isset($Data)) : ?>
								<table id="TableData" class="table table-bordered">
									<thead>
										<th>Kode Satuan</th>
										<th>Nama Satuan</th>
										<th></th>
									</thead>
									<tbody>
										<?php foreach($Data as $l) : ?>
											<tr>
												<td><?= $l->id_satuan ?></td>
												<td><?= $l->nm_satuan ?></td>
												<td class="text-center">
													<div class="btn-group">
														<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_satuan ?>">Edit</button>
														<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_satuan ?>">Delete</button>
													</div>
												</td>
											</tr>
											<div id="edit-<?= $l->id_satuan ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-yellow">
															<h4 class="modal-title">Form Edit <?= $l->nm_jenis ?></h4>
														</div>
														<?= form_open('satuan/edit-data/'.$l->id_satuan) ?>
														<div class="modal-body">
															<div class="row">
																<?php if($Level="IT Support" OR $Level="Owner") : ?>
																	<div class="col-lg-12">
																		<label>Nama Satuab</label>
																		<?= form_input('nm_satuan',$l->nm_satuan,array('class' => 'form-control')) ?>
																	</div>
																<?php else : ?>
																	<div class="col-lg-12">
																		<label>Nama Satuan</label>
																		<?= form_input('nm_satuan',$l->nm_satuan,array('class' => 'form-control')) ?>
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
											<div id="del-<?= $l->id_satuan ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-red">
															<h4 class="modal-title">Konfirmasi Menghapus <?= $Title ?></h4>
														</div>
														<?= form_open('satuan/del-data/'.$l->id_satuan) ?>
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
								<div class="text-center"><h4 class="title"><?= $Title ?> masih kosong!</h4></div>
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php endif ?>