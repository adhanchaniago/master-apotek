<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level!=NULL) : ?>
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li class="active"><i class="fa fa-dashboard"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-bars"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtUser" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama</th>
								<th>Level</th>
								<th>Tanggal Bergabung</th>
								<th>Login Terakhir</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<span class="pull-left">
							<i class="fa fa-pencil"></i>
							<h3 class="box-title">
								Form <?= $Title ?>
							</h3>
						</span>
						<span class="pull-right">
							<a onclick="reload()" class="btn btn-xs btn-primary"><i class="fa fa-refresh"></i></a>
							<a href="<?= base_url() ?>" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
						</span>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_user">Kode User</label>
									<?= form_input('id_user',null,array(
																			'id' => 'id_user',
																			'class' => 'form-control',
																			//'placeholder' => 'Username',
																			'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="nm_user">Nama Lengkap</label>
									<?= form_input('nm_user',null,array(
																			'id' => 'nm_user',
																			'class' => 'form-control',
																			//'placeholder' => 'Username',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="login_user">Username</label>
									<?= form_input('login_user',null,array(
																			'id' => 'login_user',
																			'class' => 'form-control',
																			//'placeholder' => 'Username',
																			'required' => 'true',
																			'autocomplete' => 'nope'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="pass_user">Password</label>
									<?= form_password('pass_user',null,array(
																			'id' => 'pass_user',
																			'class' => 'form-control',
																			//'placeholder' => 'Password',
																			'required' => 'true',
																			'autocomplete' => 'new-password'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="level_user">Level</label>
									<select name="level_user" class="form-control" id="level_user" required>
										<?php foreach($ListLv as $data) : ?>
											<option value="<?= $data->id_level ?>"><?= $data->nm_level ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-lg-4" style="padding:0">
								<button type="submit" id="submit" class="btn btn-block btn-primary">Simpan</button>
							</div>
							<div class="col-lg-4">
								<button type="button" id="chPwd" class="btn btn-block btn-warning" disabled="true">Ganti Pass</button>
							</div>
							<div class="col-lg-4" style="padding:0">
								<button type="button" id="delUsr" class="btn btn-block btn-danger" disabled="true">Hapus</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<div id="chPwdInt" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Masukan password anda</h4>
				</div>
				<form action="" method="POST" id="verif" role="form">
					<div class="modal-body">
						<div class="form-group">
							<label for="verif_pass_user">Password Lama</label>
							<?= form_password('verif_pass_user',null,array(
																			'id' => 'verif_pass_user',
																			'class' => 'form-control',
																			//'placeholder' => 'Password',
																			'required' => 'true',
																			'autocomplete' => 'new-password'
																		));
							?>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtUser = $('#dtUser').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('user/list_data_user/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": true,
				//"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
			$("#register").submit(function(e) {
				event.preventDefault();
				var id_user = $("#id_user").val();
				var nm_user = $("#nm_user").val();
				var login_user = $("#login_user").val();
				var pass_user = $("#pass_user").val();
				var level_user = $('select[name=level_user]').val()
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('user/tambah_user/') ?>",
						dataType: 'json',
						data: {
								id_user:id_user, 
								nm_user:nm_user, 
								login_user:login_user, 
								pass_user:pass_user, 
								level_user:level_user
							  },
						success: function() {
							$('#dtUser').DataTable().ajax.reload();
							$('#register')[0].reset();
							$('#nm_user').focus();
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
			$("#verif").submit(function(e) {
			event.preventDefault();
				var id_user = $("#id_user").val();
				var nm_user = $("#nm_user").val();
				var level_user = $('select[name=level_user]').val()
				var verif_pass_user = $("#verif_pass_user").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('user/verif_user/') ?>",
						dataType: 'json',
						data: {id_user:id_user, verif_pass_user:verif_pass_user},
						success: function(data) {
							if(data.status) {
								$("#login_user").removeAttr("disabled");
								$("#pass_user").removeAttr("disabled");
								$('#verif')[0].reset();
								$('#chPwdInt').modal('hide');
								$('#pass_user').focus();
								$("#editUsr").attr("id","chPwd");
								$("#nm_user").val(nm_user);
								$("#level_user").val(level_user).change();
								$("label[for=pass_user]").text("Password Baru");
								$(document).ajaxStart(function() { Pace.restart(); });
							} else {
								alert('Peringatan! password lama anda tidak sesuai!');
							}
						}
					});
				});
			})
		});
		$(document).on('click','#getdata',function() {
			var id_user = $(this).attr("user");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('user/get_data/') ?>",
				dataType: 'json',
				data: {
						id_user : id_user
					},
				success: function(data) {
					$("#id_user").val(data.id_user);
					$("#nm_user").val(data.nm_user);
					$("#login_user").val(data.login_user);
					$("#level_user").val(data.level_user).change();
					$("#login_user").attr("disabled","true");
					$("#pass_user").attr("disabled","true");
					$("#chPwd").removeAttr("disabled");
					$("#delUsr").removeAttr("disabled");
				}
				/*complete: function(){
					$('#dtPasien').DataTable().ajax.reload();
					$(document).ajaxStart(function() { Pace.restart(); });
				}*/
			});
		});
		$(document).on('click','#chPwd',function() {
			var nm_user = $("#nm_user").val();
			$('#chPwdInt').modal('show');
			$("#chPwd").attr("disabled","true");
			$("#nama").text(nm_user);
		});
		$(document).on('click','#delUsr',function() {
			var id_user = $("#id_user").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('user/del_data/') ?>",
				dataType: 'json',
				data: {
						id_user : id_user
					},
				success: function(data) {
					$('#dtUser').DataTable().ajax.reload();
					$('#register')[0].reset();
					$('#nm_user').focus();
					$("#login_user").removeAttr("disabled");
					$("#pass_user").removeAttr("disabled");
					$("#delUsr").attr("disabled","true");
					$("#chPwd").attr("disabled","true");
					$(document).ajaxStart(function() { Pace.restart(); });
				}
				/*complete: function(){
					$('#dtPasien').DataTable().ajax.reload();
					$(document).ajaxStart(function() { Pace.restart(); });
				}*/
			});
		});
		function reload() {
			$("#Hapus").attr("disabled","true");
			$("#id_user").attr("disabled","true");
			$("#login_user").removeAttr("disabled");
			$("#pass_user").removeAttr("disabled");
			$("#register")[0].reset();
			$("#login_user").removeAttr("disabled");
			$("#pass_user").removeAttr("disabled");
			$("#nm_user").focus();
		}
		$("#level_user").select2();
	</script>
<?php endif ?>