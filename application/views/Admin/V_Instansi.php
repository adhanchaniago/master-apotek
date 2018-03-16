<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="IT Support") : ?>
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
					<?php 
						$str = $this->session->flashdata('item');
						$x = explode("-",$str) 
					?>
					<?php if ($str!=NULL) : ?>
						<div class="alert alert-<?= $x[0] ?>">
							<h4><i class="icon fa fa-bell-o"></i> Notice!</h4>
							<?= $x[1] ?>
						</div>
					<?php endif; ?>
					<div class="box box-success">
						<div class="box-header"><h4 class="text-center">Profil Instansi</h4></div>
						<?= form_open('administrator/instansi_update',array('id' => 'form_instansi','class' => 'form-horizontal')) ?>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label col-lg-2" style="text-align:left" for="Nama_Instansi">Nama Instansi</label>
								<div class="col-lg-10">
									<?= form_input('nm_instansi',$Instansi->nm_instansi,array('id' => 'Nama_Instansi','class' => 'form-control','required' => 'yes','disabled' => 'true')) ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-lg-2" style="text-align:left" for="Alamat">Alamat</label>
								<div class="col-lg-10">
									<?= form_input('alamat',$Instansi->alamat_instansi,array('id' => 'Alamat','class' => 'form-control','required' => 'yes','disabled' => 'true')) ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-lg-2" style="text-align:left" for="Alamat">No. Telepon</label>
								<div class="col-lg-10">
									<?php
										$data = array( 	
																	'name' => 'telepon',
																	'type' => 'number',
																	'id' => 'Telepon',
																	'class' => 'form-control',
																	'value' => $Instansi->kontak_instansi,
																	'required' => 'yes',
																	'disabled' => 'true'
																	);
										echo form_input($data);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-lg-2" style="text-align:left" for="Tuslah">Biaya Tuslah</label>
								<div class="col-lg-10">
									<?= form_input('tuslah','Rp. '.number_format($Instansi->tuslah_racik,0,",","."),array('id' => 'Tuslah','class' => 'form-control','required' => 'yes','disabled' => 'true')) ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-lg-2" style="text-align:left" for="Emblase">Biaya Emblase</label>
								<div class="col-lg-10">
									<?= form_input('emblase','Rp. '.number_format($Instansi->emblase_racik,0,",","."),array('id' => 'Emblase','class' => 'form-control','required' => 'yes','disabled' => 'true')) ?>
								</div>
							</div>	
						</div>
						<div class="box-footer">
							<span class="pull-right">
								<button type="submit" id="Save" class="btn btn-primary" disabled="true" title="Simpan" value="text 1"><i class="fa fa-save"></i></button>
								<button type="button" id="UnlockEdit" class="btn btn-warning" title="Edit"><i class="fa fa-pencil"></i></button>
								<button type="button" id="Cancel" class="btn btn-danger" disabled="true" title="Cancel"><i class="fa fa-lock"></i></button>
							</span>
						</div>
						<?= form_close() ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript">
		$('#BPJS').maskMoney({
			prefix: 'Rp. ',
			thousands: '.',
			decimal: ',',
			precision: 0
		})
		$('#Umum').maskMoney({
			prefix: 'Rp. ',
			thousands: '.',
			decimal: ',',
			precision: 0
		})
		$('#Tuslah').maskMoney({
			prefix: 'Rp. ',
			thousands: '.',
			decimal: ',',
			precision: 0
		})
		$('#Emblase').maskMoney({
			prefix: 'Rp. ',
			thousands: '.',
			decimal: ',',
			precision: 0
		})
		$('#Save').on('click',function(e) {
			e.preventDefault();
			var form = $(this).parents('form');
			swal({
					title: "Apakah anda yakin?",
					text: "Data yang anda rubah tidak akan bisa di kembalikan!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-primary",
					confirmButtonText: "Ya, rubah saja!",
					cancelButtonText: "Tidak, Saya berubah pikiran!",
					closeOnConfirm: false,
					closeOnCancel: false,
					showLoaderOnConfirm: true
			}, function(isConfirm){
					if (isConfirm) {
						form.submit();
					} else {
						swal("Cancelled", "Data tidak dirubah", "error");
					}
			});
		});
		$("#UnlockEdit").click(function(){
			swal({
				title: "Apakah Anda Yakin?",
				text: "Anda akan membuka kunci untuk mengedit data profile instansi ini!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Ya, buka kunci untuk saya!",
				cancelButtonText: "Tidak, saya berubah pikiran!",
				closeOnConfirm: false,
				closeOnCancel: false
				},
				function(isConfirm) {
				if (isConfirm) {
					swal("Kunci Terbuka!", "Silahkan klik tombol OK untuk melanjutkan.", "success");
					event.preventDefault();
					$('input').prop("disabled", false);
					$('#Save').prop("disabled", false);
					$('#Cancel').prop("disabled", false);
					$('#UnlockEdit').prop("disabled", true);
				} else {
					swal("Kunci Tertutup Kembali", "Silahkan klik tombol ok untuk melanjutkan", "error");
				}
			});
		});
		$("#Cancel").click(function(){
			swal({
				title: "Apakah Anda Yakin?",
				text: "Anda akan mengunci kembali formulir data profile instansi ini!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Ya, tutup kunci untuk saya!",
				cancelButtonText: "Tidak, saya masih belum selesai merubah data!",
				closeOnConfirm: false,
				closeOnCancel: false
				},
				function(isConfirm) {
				if (isConfirm) {
					swal("Kunci Tertutup Kembali!", "Silahkan klik tombol OK untuk melanjutkan.", "success");
					event.preventDefault();
					$('input').prop("disabled", true);
					$('#Save').prop("disabled", true);
					$('#Cancel').prop("disabled", true);
					$('#UnlockEdit').prop("disabled", false);
				} else {
					swal("Kunci Masih Terbuka", "Silahkan klik tombol ok untuk melanjutkan", "error");
				}
			});
		});
	</script>
<?php endif ?>