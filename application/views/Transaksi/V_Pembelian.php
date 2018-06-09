<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik" OR $Level="Apoteker") : ?>
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
						<table id="dtTable" class="table table-striped table-bordered">
							<thead>
								<th>No.</th>
								<th>Nama Barang</th>
								<th>Qty</th>
								<th>Diskon</th>
								<th>PPN</th>
								<th>Jumlah Harga</th>
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
							<a href="<?= base_url() ?>" class="btn btn-xs btn-danger">Keluar</a>
						</span>
					</div>
					<form action="" method="POST" id="register" role="form">
						<div class="box-body row">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="id_faktur">Nomor Faktur</label>
									<?= form_input('id_faktur',null,array(
																		'id' => 'id_faktur',
																		'class' => 'form-control',
																		//'placeholder' => 'Tahun-Bulan-Hari',
																		//"data-inputmask" => "'alias': 'yyyy-mm-dd",
																		//"data-mask" => "true",
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Supplier</label>
									<select name="id_pbf" id="id_pbf" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="tanggal_jatuh_tempo">Jatuh Tempo</label>
									<?= form_input('tanggal_jatuh_tempo',null,array(
																		'id' => 'tanggal_jatuh_tempo',
																		'class' => 'form-control',
																		'placeholder' => 'Hari/Bulan/Tahun',
																		//"data-inputmask" => "'alias': 'yyyy-mm-dd",
																		//"data-mask" => "true",
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="diskon">Diskon</label>
									<?= form_input('diskon',null,array(
																		'id' => 'diskon',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="id_barang" id="id_barang" class="form-control"></select>
								</div>
							</div>
							<div class="col-lg-4">
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
									<label for="batch">Batch</label>
									<?= form_input('batch',null,array(
																		'id' => 'batch',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label for="kadaluarsa">Kadaluarsa</label>
									<?= form_input('kadaluarsa',null,array(
																		'id' => 'kadaluarsa',
																		'class' => 'form-control',
																		'placeholder' => 'Bulan/Tahun',
																		//'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label for="subtotal">Jumlah Harga</label>
									<?= form_input('subtotal',null,array(
																		'id' => 'subtotal',
																		'class' => 'form-control',
																		//'placeholder' => 'Nama Pasien',
																		'required' => 'true'
																	));
									?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="ppn">PPN</label>
									<?= form_input('ppn',null,array(
																		'id' => 'ppn',
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
        								<label><input type="checkbox" id="konsinyasi" name="konsinyasi" value="1"> Konsinyasi</label>
      								</div>
								</div>
							</div>
							<div class="col-lg-6" style="margin-top: 1.8%;">
								<div class="form-group pull-right">
									<button type="submit" id="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i></button>
									<button type="button" id="Hapus" class="btn btn-xs btn-danger" disabled="true"><i class="fa fa-minus"></i></button>
									<button type="button" id="Unlock" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#C_Unlock"><i class="fa fa-unlock"></i></button>
									<button type="button" id="Lock" class="btn btn-xs btn-primary" disabled="true"><i class="fa fa-lock"></i></button>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<span class="col-lg-6" style="padding-left:0">
								<button type="button" id="Simpan" class="btn btn-sm btn-block btn-primary">Simpan <?= $Title ?></button>
							</span>
							<span class="col-lg-6" style="padding-right:0">
								<button type="button" id="Destroy" class="btn btn-sm btn-block btn-danger">Hapus <?= $Title ?></button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div id="C_Unlock" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-sm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Masukan master password</h4>
					</div>
					<form action="" method="POST" id="verif" role="form">
						<div class="modal-body">
							<div class="form-group">
								<label for="verif_pass_user">Password</label>
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
	</section>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.date.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.inputmask.extensions.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.mask.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var kode = "<?= $this->uri->segment(3) ?>";
			$.ajax({
				type: "GET",
				url: "<?= base_url('pbf/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_pbf').append('<option value="'+d.id_pbf+'">'+d.nm_pbf+'</option>');
					});
				}
			});
			if(kode!="") {
				$("#id_pbf").focus();
				$.ajax({
					type: "GET",
					url: "<?= base_url('pembelian/get_detail/'.$this->uri->segment(3)) ?>",
					success: function(data){
						var val = $.parseJSON(data);
						$("#id_pbf").val(val.id_pbf).change();
						$("#tanggal_jatuh_tempo").val(val.tanggal_jatuh_tempo);
						$("#diskon").val(val.diskon);
						$("#id_faktur").val(val.id_faktur);
					}
				});
				$.ajax({
					type: "GET",
					url: "<?= base_url('pembelian/get_status/'.$this->uri->segment(3)) ?>",
					success: function(data){
						var val = $.parseJSON(data);
						if(val.status) {
							$("#id_faktur").attr("disabled","true");
							$("#id_pbf").attr("disabled","true");
							$("#tanggal_jatuh_tempo").attr("disabled","true");
							$("#diskon").attr("disabled","true");
							$("#id_barang").attr("disabled","true");
							$("#qty").attr("disabled","true");
							$("#batch").attr("disabled","true");
							$("#kadaluarsa").attr("disabled","true");
							$("#subtotal").attr("disabled","true");
							$("#konsinyasi").attr("disabled","true");
							$("#ppn").attr("disabled","true");
							$("#submit").attr("disabled","true");
							$("#Simpan").attr("disabled","true");
						}
					}
				});
			}
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('pembelian/list_detail_data/'.$this->uri->segment(3)) ?>",
					"type": "POST"
				},
				"autoWidth": false,
				"info": true,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			$("#register").submit(function(e) {
			event.preventDefault();
				var id_barang = $("#id_barang").val();
				var diskon = $("#diskon").val();
				var qty = $("#qty").val();
				var subtotal_barang = $("#subtotal").val();
				var tanggal_jatuh_tempo = $("#tanggal_jatuh_tempo").val();
				var batch = $("#batch").val();
				var kadaluarsa = $("#kadaluarsa").val();
				var ppn = $("#ppn").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('pembelian/tambah_data/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								id_barang : id_barang,
								diskon : diskon,
								qty : qty,
								subtotal_barang : subtotal_barang,
								batch : batch,
								kadaluarsa : kadaluarsa,
								ppn : ppn,
								//id_faktur : id_faktur
							},
						complete: function(data) {
							$("#dtTable").DataTable().ajax.reload();
							$("#Hapus").attr("disabled","true");
							$("#id_faktur").attr("disabled","true");
							$("#id_pbf").attr("disabled","true");
							$("#tanggal_jatuh_tempo").attr("disabled","true");
							$("#diskon").val("");
							$("#diskon").focus();
							$("#id_barang").find('option').remove().end();
							$.ajax({
								type: "GET",
								url: "<?= base_url('barang/get_option') ?>",
								success: function(data){
									var opts = $.parseJSON(data);
									$.each(opts, function(i, d) {
										$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+' sisa '+d.stok_tersedia+'</option>');
									});
								}
							});
							$("#batch").val("");
							$("#qty").val("");
							$("#subtotal").val("");
							$(document).ajaxStart(function() { Pace.restart(); });
						}
					});
				});
			})
			$.ajax({
				type: "GET",
				url: "<?= base_url('barang/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+' sisa '+d.stok_tersedia+'</option>');
					});
				}
			});
		});
		$(document).on('click','#getdata',function() {
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/get_data/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#tanggal_jatuh_tempo").focus();
					$("#id_barang").val(data.id_barang).change();
					$("#diskon").val(data.diskon);
					$("#qty").val(data.qty);
					$("#batch").val(data.batch);
					$("#kadaluarsa").val(data.kadaluarsa);
					$("#subtotal").val(data.subtotal_barang);
					$("#ppn").val(data.ppn);
					$("#Hapus").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/hapus_data/') ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#dtTable").DataTable().ajax.reload();
					$("#Hapus").attr("disabled","true");
					$("#id_pbf").attr("readonly","true");
					$("#tanggal_jatuh_tempo").attr("readonly","true");
					$("#diskon").attr("readonly","true");
					$("#id_barang").focus();
					$("#id_barang").find('option').remove().end();
					$.ajax({
						type: "GET",
						url: "<?= base_url('barang/get_option') ?>",
						success: function(data){
							var opts = $.parseJSON(data);
							$.each(opts, function(i, d) {
								$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+' sisa '+d.stok_tersedia+'</option>');
							});
						}
					});
					$("#qty").val("");
					$("#subtotal").val("");
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#Simpan',function() {
			var id_pbf = $("#id_pbf").val();
			var tanggal_jatuh_tempo = $("#tanggal_jatuh_tempo").val();
			var diskon = $("#diskon").val();
			var konsinyasi = $("#konsinyasi").val();
			var id_faktur = $("#id_faktur").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/simpan_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_pbf : id_pbf,
						id_faktur : id_faktur,
						tanggal_jatuh_tempo : tanggal_jatuh_tempo,
						diskon : diskon,
						konsinyasi : konsinyasi
					},
				success: function(data) {
					window.location.href = "<?= base_url('laporan/pembelian') ?>";
				}
			});
		});
		$(document).on('click','#Destroy',function() {
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('pembelian/hapus_semua/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				success: function(data) {
					window.location.href = "<?= base_url('transaksi/pembelian') ?>";
				}
			});
		});
		function reload() {
			$("#dtTable").DataTable().ajax.reload();
			$("#Hapus").attr("disabled","true");
			$("#id_pbf").attr("disabled","true");
			//$("#tanggal_jatuh_tempo").attr("readonly","true");
			//$("#diskon").attr("readonly","true");
			$("#id_barang").focus();
			$("#id_barang").find('option').remove().end();
			$.ajax({
				type: "GET",
				url: "<?= base_url('barang/get_option') ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$.each(opts, function(i, d) {
						$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+' sisa '+d.stok_tersedia+'</option>');
					});
				}
			});
			$("#batch").val("");
			$("#qty").val("");
			$("#subtotal").val("");
			$(document).ajaxStart(function() { Pace.restart(); });
		}
		$("#verif").submit(function(e) {
		event.preventDefault();
			var verif_pass_user = $("#verif_pass_user").val();
			Pace.track(function(){
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('user/unlock/') ?>",
					dataType: 'json',
					data: {verif_pass_user:verif_pass_user},
					success: function(data) {
						if(data.status) {
							$("#id_pbf").removeAttr("disabled");
							$("#tanggal_jatuh_tempo").removeAttr("disabled");
							$("#diskon").removeAttr("disabled");
							$("#id_barang").removeAttr("disabled");
							$("#qty").removeAttr("disabled");
							$("#batch").removeAttr("disabled");
							$("#kadaluarsa").removeAttr("disabled");
							$("#subtotal").removeAttr("disabled");
							$("#konsinyasi").removeAttr("disabled");
							$("#submit").removeAttr("disabled");
							$("#Simpan").removeAttr("disabled");
							$("#Lock").removeAttr("disabled");
							$("#Unlock").attr("disabled","true");
							$('#C_Unlock').modal('hide');
							$(document).ajaxStart(function() { Pace.restart(); });
						} else {
							alert('Peringatan! password lama anda tidak sesuai!');
						}
					}
				});
			});
		})
		$(document).on('click','#Lock',function() {
			$("#id_pbf").attr("disabled","true");
			$("#tanggal_jatuh_tempo").attr("disabled","true");
			$("#diskon").attr("disabled","true");
			$("#id_barang").attr("disabled","true");
			$("#qty").attr("disabled","true");
			$("#batch").attr("disabled","true");
			$("#kadaluarsa").attr("disabled","true");
			$("#subtotal").attr("disabled","true");
			$("#konsinyasi").attr("disabled","true");
			$("#submit").attr("disabled","true");
			$("#Simpan").attr("disabled","true");
			$("#Unlock").removeAttr("disabled");
			$("#Lock").attr("disabled","true");
		});
		$("#id_barang").select2();
		$("#id_pbf").select2();
		$('input[name="kadaluarsa"]').mask('00/0000');
		$('input[name="tanggal_jatuh_tempo"]').mask('00-00-0000');
		//$('#tanggal_jatuh_tempo').inputmask('yyyy-mm-dd', { 'placeholder': 'tahn-bl-hr' })
		//$('#kadaluarsa').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>