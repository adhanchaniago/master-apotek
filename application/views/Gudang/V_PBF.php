<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support" OR $Level="Owner") : ?>
	<div class="container">
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
					<div class="box box-success">
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
										<?= form_open('pbf/tambah-data') ?>
										<div class="modal-body">
											<div class="row">
												<?php if($Level="IT Support" OR $Level="Owner") : ?>
													<div class="col-lg-6">
														<label>Nama Instansi PBF</label>
														<?= form_input('nm_pbf',null,array('class' => 'form-control','placeholder' => 'Nama pbf')) ?>
													</div>
													<div class="col-lg-6">
														<label>Alamat</label>
														<?= form_input('alamat',null,array('class' => 'form-control','placeholder' => 'Alamat Instansi PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kota PBF</label>
														<?= form_input('kota_pbf',null,array('class' => 'form-control','placeholder' => 'Kota PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kontak Kantor PBF</label>
														<?= form_input('kontak_kantor_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Kantor PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kontak Orang PBF</label>
														<?= form_input('kontak_orang_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Orang PBF')) ?>
													</div>
												<?php else : ?>
													<div class="col-lg-6">
														<label>Nama Instansi PBF</label>
														<?= form_input('nm_pbf',null,array('class' => 'form-control','placeholder' => 'Nama pbf')) ?>
													</div>
													<div class="col-lg-6">
														<label>Alamat</label>
														<?= form_input('alamat',null,array('class' => 'form-control','placeholder' => 'Alamat Instansi PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kota PBF</label>
														<?= form_input('kota_pbf',null,array('class' => 'form-control','placeholder' => 'Kota PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kontak Kantor PBF</label>
														<?= form_input('kontak_kantor_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Kantor PBF')) ?>
													</div>
													<div class="col-lg-6">
														<label>Kontak Orang PBF</label>
														<?= form_input('kontak_orang_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Orang PBF')) ?>
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
								<table class="table table-bordered">
									<thead>
										<th>Kode PBF</th>
										<th>Nama Instansi</th>
										<th>Alamat</th>
										<th>Kota</th>
										<th>Kontak Kantor PBF</th>
										<th>Kontak Agen PBF</th>
										<th></th>
									</thead>
									<tbody>
										<?php foreach($Data as $l) : ?>
											<tr>
												<td><?= $l->id_pbf ?></td>
												<td><?= $l->nm_pbf ?></td>
												<td><?= $l->alamat_pbf ?></td>
												<td><?= $l->kota_pbf ?></td>
												<td><?= $l->kontak_kantor_pbf ?></td>
												<td><?= $l->kontak_orang_pbf ?></td>
												<td class="text-center">
													<div class="btn-group">
														<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_pbf ?>">Edit</button>
														<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_pbf ?>">Delete</button>
													</div>
												</td>
											</tr>
											<div id="edit-<?= $l->id_pbf ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-yellow">
															<h4 class="modal-title">Form Edit <?= $l->nm_jenis ?></h4>
														</div>
														<?= form_open('pbf/edit-data/'.$l->id_pbf) ?>
														<div class="modal-body">
															<?php if($Level="IT Support" OR $Level="Owner") : ?>
															<div class="col-lg-6">
																<label>Nama Instansi PBF</label>
																<?= form_input('nm_pbf',$l->nm_pbf,array('class' => 'form-control','placeholder' => 'Nama pbf')) ?>
															</div>
															<div class="col-lg-6">
																<label>Alamat</label>
																<?= form_input('alamat',null,array('class' => 'form-control','placeholder' => 'Alamat Instansi PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kota PBF</label>
																<?= form_input('kota_pbf',null,array('class' => 'form-control','placeholder' => 'Kota PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kontak Kantor PBF</label>
																<?= form_input('kontak_kantor_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Kantor PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kontak Orang PBF</label>
																<?= form_input('kontak_orang_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Orang PBF')) ?>
															</div>
														<?php else : ?>
															<div class="col-lg-6">
																<label>Nama Instansi PBF</label>
																<?= form_input('nm_pbf',null,array('class' => 'form-control','placeholder' => 'Nama pbf')) ?>
															</div>
															<div class="col-lg-6">
																<label>Alamat</label>
																<?= form_input('alamat',null,array('class' => 'form-control','placeholder' => 'Alamat Instansi PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kota PBF</label>
																<?= form_input('kota_pbf',null,array('class' => 'form-control','placeholder' => 'Kota PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kontak Kantor PBF</label>
																<?= form_input('kontak_kantor_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Kantor PBF')) ?>
															</div>
															<div class="col-lg-6">
																<label>Kontak Orang PBF</label>
																<?= form_input('kontak_orang_pbf',null,array('class' => 'form-control','placeholder' => 'Kontak Orang PBF')) ?>
															</div>
														<?php endif ?>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Simpan</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														</div>
														<?= form_close() ?>
													</div>
												</div>
											</div>
											<div id="del-<?= $l->id_pbf ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-red">
															<h4 class="modal-title">Konfirmasi Menghapus <?= $Title ?></h4>
														</div>
														<?= form_open('pbf/del-data/'.$l->id_pbf) ?>
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