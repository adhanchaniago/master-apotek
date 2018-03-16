<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="IT Support" OR $Level=="Owner") : ?>
	<div class="container">
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
										<?= form_open('user/tambah-data') ?>
										<div class="modal-body row">
											<div class="col-lg-6">
												<div class="form-group">
													<label>Nama User</label>
													<?= form_input('nm_user',null,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Username</label>
													<?= form_input('username',null,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Jenis Kelamin</label><br />
													<label class="radio-inline"><input type="radio" name="jenis_kelamin" required="true">Laki-Laki</label>
													<label class="radio-inline"><input type="radio" name="jenis_kelamin" required="true">Perempuan</label>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Tempat Lahir</label>
													<?= form_input('tempat_lahir',null,array('class' => 'form-control','maxlength' => '45')) ?>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Tanggal Lahir</label>
													<input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>No. Telepon</label>
													<?= form_input('kontak',null,array('class' => 'form-control','maxlength' => '20')) ?>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="form-group">
													<label>Level</label>
													<select name="level" class="form-control" required="true">
														<option value="">== Pilih Level ==</option>
														<?php foreach($Hak as $h) : ?>
															<option value="<?= $h->id_level ?>"><?= $h->nm_level ?></option>
														<?php endforeach ?>
													</select>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="form-group">
													<label>Alamat</label>
													<?= form_input('alamat',null,array('class' => 'form-control')) ?>
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
										<th>Kode User</th>
										<th>Nama Lengkap</th>
										<th>Username</th>
										<th>Level</th>
										<th>Login Terakhir</th>
										<th>Status</th>
										<th></th>
									</thead>
									<tbody>
										<?php $i=1;foreach($Data as $l) : ?>
											<tr>
												<td><?= $i++ ?></td>
												<td><?= $l->id_user ?></td>
												<td><?= $l->nm_user ?></td>
												<td><?= $l->username ?></td>
												<td><?= $l->nm_level ?></td>
												<td><?= $l->login_terakhir ?></td>
												<td><?= $l->status_user ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit-<?= $l->id_user ?>">Edit</button>
													<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del-<?= $l->id_user ?>">Delete</button>
												</td>
											</tr>
											<div id="edit-<?= $l->id_user ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-yellow">
															<h4 class="modal-title">Form Tambah <?= $Title ?></h4>
														</div>
														<?= form_open('user/edit-data/'.$l->id_user) ?>
														<div class="modal-body row">
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Nama User</label>
																	<?= form_input('nm_user',$l->nm_user,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Username</label>
																	<?= form_input('username',$l->username,array('class' => 'form-control','maxlength' => '45','required' => 'true')) ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Jenis Kelamin</label><br />
																	<label class="radio-inline"><input type="radio" name="jenis_kelamin" required="true" value="Laki-Laki" <?php if($l->jenis_kelamin=="Laki-Laki") : echo "checked"; endif; ?>>Laki-Laki</label>
																	<label class="radio-inline"><input type="radio" name="jenis_kelamin" required="true" value="Perempuan" <?php if($l->jenis_kelamin=="Perempuan") : echo "checked"; endif; ?>>Perempuan</label>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Tempat Lahir</label>
																	<?= form_input('tempat_lahir',$l->tempat_lahir,array('class' => 'form-control','maxlength' => '45')) ?>
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>Tanggal Lahir</label>
																	<input type="text" name="tgl_lahir" id="e_tgl_lahir" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask value="<?= $l->tanggal_lahir ?>">
																</div>
															</div>
															<div class="col-lg-6">
																<div class="form-group">
																	<label>No. Telepon</label>
																	<?= form_input('kontak',$l->kontak_user,array('class' => 'form-control','maxlength' => '20')) ?>
																</div>
															</div>
															<div class="col-lg-12">
																<div class="form-group">
																	<label>Level</label>
																	<select name="level" class="form-control" required="true">
																		<option value="">== Pilih Level ==</option>
																		<?php foreach($Hak as $h) : ?>
																			<option value="<?= $h->id_level ?>" <?php if($h->id_level==$l->level_user) : echo "selected"; endif; ?>><?= $h->nm_level ?></option>
																		<?php endforeach ?>
																	</select>
																</div>
															</div>
															<div class="col-lg-12">
																<div class="form-group">
																	<label>Alamat</label>
																	<?= form_input('alamat',$l->alamat_user,array('class' => 'form-control')) ?>
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
											<div id="del-<?= $l->id_user ?>" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-red">
															<h4 class="modal-title">Konfirmasi Menghapus <?= $Title ?></h4>
														</div>
														<?= form_open('user/del-data/'.$l->id_user) ?>
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
								<div>No user(s) found.</div>
							<?php endif ?>
						</div>
						<div class="box-footer">
							<center>
								<?php 
									if(isset($links)) {
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