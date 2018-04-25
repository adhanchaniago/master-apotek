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
									<select name="barang" id="id_barang" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="qty">Qty</label>
									<?= form_input('qty',null,array(
																			'id' => 'qty',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="diskon">Diskon (%)</label>
									<?= form_input('diskon',null,array(
																			'id' => 'diskon',
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
								<button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#formByr">Bayar</button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" class="btn btn-block btn-danger" disabled="true">Hapus</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div id="formByr" class="modal fade" role="dialog">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Modal Header</h4>
					</div>
					<!--<form action="" method="POST" id="bayar" role="form">-->
						<div class="modal-body row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="bayar">Bayar</label>
									<?= form_input('bayar',null,array(
																			'id' => 'bayar',
																			'class' => 'form-control',
																			//'disabled' => 'true'
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" id="pay">Bayar</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					<!--</form>-->
				</div>
			</div>
		</div>
	</section>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script>
		$(document).ready(function(){
			$.ajax({
				type: "GET",
				url: "<?= base_url('penjualan/get_nama_pasien_resep/'.$this->uri->segment(3)) ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$('#nm_pasien').val(opts.nm_pasien);
					$('#nm_dokter').val(opts.nm_dokter);
					$('#alamat_pasien').val(opts.alamat_pasien);
				}
			});
			$.ajax({
				type: "GET",
				url: "<?= base_url('penjualan/get_subtotal_resep/'.$this->uri->segment(3)) ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$('#subtotal').text(opts.subtotal);
				}
			});
			$("#nm_pasien").focus();
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('penjualan/list_detail_data_resep/'.$this->uri->segment(3)) ?>",
					"type": "POST"
				},
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
				var nm_pasien = $("#nm_pasien").val();
				var nm_dokter = $("#nm_dokter").val();
				var alamat_pasien = $("#alamat_pasien").val();
				var id_barang = $("#id_barang").val();
				var qty = $("#qty").val();
				var diskon = $("#diskon").val();
				//var kasbon = $("#kasbon").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/tambah_data_resep/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								nm_pasien : nm_pasien,
								nm_dokter : nm_dokter,
								alamat_pasien : alamat_pasien,
								id_barang : id_barang,
								qty : qty,
								diskon : diskon
								//kasbon : kasbon
							},
						success: function(data) {
							if(data.status) {
								$("#dtTable").DataTable().ajax.reload();
								$("#Hapus").attr("disabled","true");
								$("#nm_pasien").attr("disabled","true");
								$("#nm_dokter").attr("disabled","true");
								$("#alamat_pasien").attr("disabled","true");
								$.ajax({
									type: "GET",
									url: "<?= base_url('penjualan/get_subtotal_resep/'.$this->uri->segment(3)) ?>",
									success: function(data){
										var opts = $.parseJSON(data);
										$('#subtotal').text(opts.subtotal);
									}
								});
								//$("#register")[0].reset();
								$("#id_barang").focus();
								$("#qty").val("");
								$("#diskon").val("");
								$(document).ajaxStart(function() { Pace.restart(); });
							} else {
								alert('Barang sudah di tambahkan atau stok telah habis!');
								$("#id_barang").focus();
								$("#qty").val("");
								$("#diskon").val("");
							}
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
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('penjualan/get_data_resep/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang);
					$("#qty").val(data.qty);
					$("#diskon").val(data.diskon);
					$("#Hapus").removeAttr("disabled");
					$("#nm_pasien").attr("disabled","true");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('penjualan/hapus_data_resep/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_barang").attr("disabled","true");
					$("#qty").val("");
					$("#diskon").val("");
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#pay',function() {
			var nm_pasien = $("#nm_pasien").val();
			var nm_dokter = $("#nm_dokter").val();
			var alamat_pasien = $("#alamat_pasien").val();
			var bayar = $("#bayar").val();
			var check = $('#kasbon').is(':checked');
			if(check) {
				var kasbon = $("#kasbon").val();
			} else {
				var kasbon = 0;
			}
			Pace.track(function(){
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('penjualan/simpan_semua_resep/'.$this->uri->segment(3)) ?>",
					dataType: 'json',
					data: {
							nm_pasien : nm_pasien,
							nm_dokter : nm_dokter,
							alamat_pasien : alamat_pasien,
							bayar : bayar,
							kasbon : kasbon
						},
					success: function(data) {
						alert(data.kembalian)
					}
				});
			});
		});
		$("#id_barang").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		//$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>