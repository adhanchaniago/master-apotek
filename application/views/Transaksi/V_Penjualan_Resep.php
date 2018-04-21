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
						<i class="fa fa-money"></i>
						<h3 class="box-title">List Barang</h3>
					</div>
					<div class="box-body">
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama</th>
								<th>Sisa Stok</th>
								<th>Qty</th>
								<th>Diskon</th>
								<th>Subtotal</th>
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
							<div class="col-lg-6">
								<div class="form-group">
									<label for="nm_pasien">Nama Pasien</label>
									<?= form_input('nm_pasien',null,array(
																			'id' => 'nm_pasien',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="nm_dokter">Nama Dokter</label>
									<?= form_input('nm_dokter',null,array(
																			'id' => 'nm_dokter',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label for="alamat_pasien">Alamat</label>
									<?= form_input('alamat_pasien',null,array(
																			'id' => 'alamat_pasien',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="barang" id="id_barang" class="form-control">
										<option></option>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="qty_penjualan_bebas">Qty</label>
									<?= form_input('qty_penjualan_bebas',null,array(
																			'id' => 'qty_penjualan_bebas',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="diskon_penjualan_bebas">Diskon (%)</label>
									<?= form_input('diskon_penjualan_bebas',null,array(
																			'id' => 'diskon_penjualan_bebas',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<div class="checkbox">
        								<label><input type="checkbox" id="kasbon" name="kasbon" value="2"> Kasbon</label>
      								</div>
								</div>
							</div>
							<div class="col-lg-6" style="margin-top: 1.8%;">
								<div class="form-group pull-right	">
									<button type="submit" class="btn btn-success"><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-warning"><i class="fa fa-unlock"></i></button>
									<button type="button" id="Hapus" class="btn btn-danger"><i class="fa fa-minus"></i></button>
								</div>
							</div>
							<div class="col-lg-12" style="margin-top: 1.8%;">
								<span class="pull-left label label-default" style="font-size: 1.5em;display: inline;">SUBTOTAL</span>
								<span class="pull-right label label-danger" style="font-size: 1.5em;display: inline;">Rp. <span id="subtotal">0 -,</span></span>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="button" class="btn btn-block btn-primary" onclick="bayar()">Simpan</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" class="btn btn-block btn-danger" disabled="true">Hapus</button>
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
				/*"ajax": {
					"url": "<?= base_url('jenis/list_all_data') ?>",
					"type": "POST"
				},*/
				"autoWidth": false,
				"info": false,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_jenis = $("#id_jenis").val();
				var nm_jenis = $("#nm_jenis").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('jenis/tambah_data/') ?>",
						dataType: 'json',
						data: {
								id_jenis : id_jenis,
								nm_jenis : nm_jenis
							},
						success: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#nm_jenis").attr("disabled","true");
	        				$("#register")[0].reset();
	        				$("#nm_jenis").focus();
	        				$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('barang/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+'</option>');
					});
				}
			});
		});
		$(document).on('click','#getdata',function() {
	  		var id_jenis = $(this).attr("data");
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('jenis/get_data/') ?>",
				dataType: 'json',
				data: {
						id_jenis : id_jenis
					},
				success: function(data) {
					$("#id_jenis").val(data.id_jenis);
					$("#nm_jenis").val(data.nm_jenis);
					$("#Hapus").removeAttr("disabled");
					$("#id_jenis").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
	  		var id_jenis = $("#id_jenis").val();
	  		jQuery.ajax({
				type: "POST",
				url: "<?= base_url('jenis/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_jenis : id_jenis
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_jenis").attr("disabled","true");
    				$('#register')[0].reset();
    				$('#nm_jenis').focus();
    				$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		function bayar() {
			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this imaginary file!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					event.preventDefault();
				var user = "<?= $this->session->userdata('kode') ?>";
				var nama = $("#nm_pasien").val();
				var bayar = $("#bayar").val();
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('penjualan/bayar/') ?>",
					dataType: 'json',
					data: {user: user, nama: nama, bayar: bayar},
					success: function(res) {
						if (res)
						{
							swal("Kembali Rp. "+res.bayar+" -,", {
									icon: "success",
								})
								.then((Confirm) => {
									if(Confirm) {
										window.location.href = "<?= base_url('dashboard') ?>"
									};
								});
						}
					}
				});
				} else {
					swal("Your imaginary file is safe!");
				}
			});
		}
		$("#id_barang").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	    //$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>