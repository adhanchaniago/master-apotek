<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
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
						<i class="fa fa-wheelchair"></i>
						<h3 class="box-title">List <?= $Title ?></h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Kode</th>
								<th>Nama Satuan</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-pencil"></i>
						<h3 class="box-title">Form <?= $Title ?></h3>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="id_satuan">Kode Satuan</label>
									<?= form_input('id_satuan',null,array(
																			'id' => 'id_satuan',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			//'required' => 'true',
																			'disabled' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="nm_satuan">Nama Satuan</label>
									<?= form_input('nm_satuan',null,array(
																			'id' => 'nm_satuan',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="submit" class="btn btn-block btn-primary">Simpan</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Hapus" class="btn btn-block btn-danger" disabled="true">Hapus</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('satuan/list_all_data') ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": true,
				"ordering": true,
				"paging": true,
				"pageLength": 5,
				"lengthChange": true,
				"searching": true
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_satuan = $("#id_satuan").val();
				var nm_satuan = $("#nm_satuan").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('satuan/tambah_data/') ?>",
						dataType: 'json',
						data: {
								id_satuan : id_satuan,
								nm_satuan : nm_satuan
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_satuan").attr("disabled","true");
	        				$("#register")[0].reset();
	        				$("#nm_satuan").focus();
	        				$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
		});
		$(document).on('click','#getdata',function() {
	  		var id_satuan = $(this).attr("data");
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('satuan/get_data/') ?>",
				dataType: 'json',
				data: {
						id_satuan : id_satuan
					},
				success: function(data) {
					$("#id_satuan").val(data.id_satuan);
					$("#nm_satuan").val(data.nm_satuan);
					$("#Hapus").removeAttr("disabled");
					$("#id_satuan").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
	  		var id_satuan = $("#id_satuan").val();
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('satuan/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_satuan : id_satuan
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_satuan").attr("disabled","true");
    				$('#register')[0].reset();
    				$('#nm_satuan').focus();
    				$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>