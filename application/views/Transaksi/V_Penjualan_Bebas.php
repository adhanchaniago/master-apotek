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
									<label for="id_nota">Nomor Nota</label>
									<?= form_input('id_nota',null,array(
																			'id' => 'id_nota',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
								</div>
							</div>
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
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="id_barang" id="id_barang" class="form-control"></select>
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
																			'disabled' => 'true'
																			//'placeholder' => 'Nama Pasien',
																			//'required' => 'true'
																		));
									?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<div class="checkbox">
										<label><input type="checkbox" id="kasbon" name="kasbon" value="0"> Kasbon</label>
									</div>
								</div>
							</div>
							<div class="col-lg-6" style="margin-top: 1.8%;">
								<div class="form-group pull-right	">
								<?php if($this->uri->segment(3)==NULL) : ?>
									<button type="submit" id="tambah" class="btn btn-success"><i class="fa fa-plus"></i></button>
									<button type="button" id="Hapus" class="btn btn-danger"><i class="fa fa-minus"></i></button>
									<button type="button" id="Unlock" class="btn btn-warning" data-toggle="modal" data-target="#C_Unlock"><i class="fa fa-unlock"></i></button>
									<button type="button" id="Lock" class="btn btn-primary" disabled="true"><i class="fa fa-lock"></i></button>
								<?php else : ?>
									<button type="button" id="Return" class="btn btn-danger">Retur</button>
									<button type="button" id="Unlock" class="btn btn-warning" data-toggle="modal" data-target="#C_Unlock"><i class="fa fa-unlock"></i></button>
								<?php endif ?>
								</div>
							</div>
							<div class="col-lg-12" style="margin-top: 1.8%;">
								<span class="pull-left label label-default" style="font-size: 1.5em;display: inline;">SUBTOTAL</span>
								<span class="pull-right label label-danger" style="font-size: 1.5em;display: inline;"><span id="subtotal">0 -,</span></span>
							</div>
						</div>
						<div class="box-footer">
							<?php if($this->uri->segment(3)==NULL) : ?>
								<button type="button" id="bayar" class="btn btn-block btn-primary" data-toggle="modal" data-target="#formByr">Bayar</button>
							<?php else : ?>
								<a href=<?= base_url() ?> class="btn btn-block btn-primary">Selesai</a>
							<?php endif ?>
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
									<label for="mon_bayar">Bayar</label>
									<?= form_input('mon_bayar',null,array(
																			'id' => 'mon_bayar',
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
	<script>
		$(document).ready(function(){
			$.ajax({
				type: "GET",
				url: "<?= base_url('penjualan/get_data_penjualan/'.$this->uri->segment(3)) ?>",
				success: function(data){
					var opts = $.parseJSON(data);
					$('#nm_pasien').val(opts.nm_pasien);
					$('#id_nota').val(opts.id_nota);
					if(opts.status==0) {
						$('#kasbon').attr("checked","true");
					}
					if(opts.nm_pasien!=null) {
						$("#nm_pasien").attr("disabled","true");	
					} if(opts.id_nota!=null) {
						$("#id_nota").attr("disabled","true");
					}					
				}
			});
			var kode = "<?= $this->uri->segment(3) ?>";
			if(kode!="") {
				// $("#id_barang").attr("disabled","true");
				// $("#qty").attr("disabled","true");
				$("#bayar").attr("disabled","true");
				$("#tambah").attr("disabled","true");
				$("#Hapus").attr("disabled","true");
				$("#Lock").attr("disabled","true");
				$('#kasbon').attr("disabled","true");
				// $.ajax({
				// 	type: "POST",
				// 	url: "<?= base_url('pembelian/get_detail/'.$this->uri->segment(3)) ?>",
				// 	success: function(data){
				// 		var val = $.parseJSON(data);
				// 		$("#tanggal_jatuh_tempo").val(val.tanggal_jatuh_tempo);
				// 		$("#diskon").val(val.diskon);
				// 	}
				// });
			}
			$.ajax({
				type: "GET",
				url: "<?= base_url('penjualan/get_subtotal/'.$this->uri->segment(3)) ?>",
				success: function(data){
					if(data){
						var opts = $.parseJSON(data);
						$('#subtotal').text(opts.subtotal);
					} else {
						$('#subtotal').text("0");
					}
				}
			});
			$("#id_barang").focus();
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('penjualan/list_shopping/'.$this->uri->segment(3)) ?>",
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
				var id_nota = $("#id_nota").val();
				var nm_pasien = $("#nm_pasien").val();
				var id_barang = $("#id_barang").val();
				var qty = $("#qty").val();
				var diskon = $("#diskon").val();
				//var kasbon = $("#kasbon").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/tambah_data/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								id_nota : id_nota,
								nm_pasien : nm_pasien,
								id_barang : id_barang,
								qty : qty,
								diskon : diskon
								//kasbon : kasbon
							},
						success: function(data) {
							if(data.status) {
								$("#dtTable").DataTable().ajax.reload();
								$("#Hapus").attr("disabled","true");
								//$("#nm_pasien").attr("disabled","true");
								$.ajax({
									type: "GET",
									url: "<?= base_url('penjualan/get_subtotal/'.$this->uri->segment(3)) ?>",
									success: function(data){
										var opts = $.parseJSON(data);
										$('#subtotal').text(opts.subtotal);
									}
								});
								$.ajax({
									type: "GET",
									url: "<?= base_url('penjualan/get_data_penjualan/'.$this->uri->segment(3)) ?>",
									success: function(data){
										var opts = $.parseJSON(data);
										//$('#nm_pasien').val(opts.nm_pasien);
										//$('#id_nota').val(opts.id_nota);
										if(opts.status==0) {
											$('#kasbon').attr("checked","true");
										}
										$("#nm_pasien").attr("disabled","true");	
										$("#id_nota").attr("disabled","true");					
									}
								});
								//$("#register")[0].reset();
								$("#id_barang").removeAttr("disabled");
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
						$('#id_barang').append('<option value="'+d.id_barang+'">'+d.nm_barang+' sisa '+d.stok_tersedia+'</option>');
					});
				}
			});
		});
		$(document).on('click','#getdata',function() {
			var id_barang = $(this).attr("data");
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('penjualan/get_data_barang/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$("#id_barang").val(data.id_barang).change();
					$("#qty").val(data.qty);
					$("#diskon").val(data.diskon);
					$("#id_barang").attr("disabled","true");
					$("#Hapus").removeAttr("disabled");
				}
			});
		});
		$(document).on('click','#Hapus',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('penjualan/hapus_data/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					var kode = "<?= $this->uri->segment(3) ?>";
					if(kode=="") {
						$("#Hapus").attr("disabled","true");
					}
					//$("#id_barang").attr("disabled","true");
					$("#qty").val("");
					$("#diskon").val("");
					$.ajax({
								type: "GET",
								url: "<?= base_url('penjualan/get_subtotal/'.$this->uri->segment(3)) ?>",
								success: function(data){
									var opts = $.parseJSON(data);
									$('#subtotal').text(opts.subtotal);
								}
							});
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#Retur',function() {
			var id_barang = $("#id_barang").val();
			jQuery.ajax({
				type: "POST",
				url: "<?= base_url('penjualan/retur_data/'.$this->uri->segment(3)) ?>",
				dataType: 'json',
				data: {
						id_barang : id_barang
					},
				success: function(data) {
					$('#dtTable').DataTable().ajax.reload();
					var kode = "<?= $this->uri->segment(3) ?>";
					if(kode=="") {
						$("#Hapus").attr("disabled","true");
					}
					//$("#id_barang").attr("disabled","true");
					$("#qty").val("");
					$("#diskon").val("");
					$.ajax({
								type: "GET",
								url: "<?= base_url('penjualan/get_subtotal/'.$this->uri->segment(3)) ?>",
								success: function(data){
									var opts = $.parseJSON(data);
									$('#subtotal').text(opts.subtotal);
								}
							});
					$(document).ajaxStart(function() { Pace.restart(); });
				}
			});
		});
		$(document).on('click','#pay',function() {
			var id_nota = $("#id_nota").val();
			var nm_pasien = $("#nm_pasien").val();
			var bayar = $("#mon_bayar").val();
			var check = $('#kasbon').is(':checked');
			if(check) {
				var kasbon = 1;
			} else {
				var kasbon = 0;
			}
			Pace.track(function(){
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('penjualan/pembayaran/'.$this->uri->segment(3)) ?>",
					dataType: 'json',
					data: {
							id_nota : id_nota,
							nm_pasien : nm_pasien,
							bayar : bayar,
							kasbon : kasbon
						},
					success: function(data) {
						alert(data.kembalian);
						location.reload();
					}
				});
			});
		});
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
							$("#id_barang").removeAttr("disabled");
							$("#qty").removeAttr("disabled");
							$("#diskon").removeAttr("disabled");
							$("#submit").removeAttr("disabled");
							$("#Simpan").removeAttr("disabled");
							$("#Lock").removeAttr("disabled");
							$("#Unlock").attr("disabled","true");
							$("#bayar").removeAttr("disabled");
							$("#tambah").removeAttr("disabled");
							$("#Hapus").removeAttr("disabled");
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
			// $("#id_barang").attr("disabled","true");
			// $("#qty").attr("disabled","true");
			$("#diskon").attr("disabled","true");
			$("#submit").attr("disabled","true");
			$("#Simpan").attr("disabled","true");
			$("#Unlock").removeAttr("disabled");
			$("#Lock").attr("disabled","true");
		});
		function reload() {
			$("#dtTable").DataTable().ajax.reload();
			$("#Hapus").attr("disabled","true");
			//$("#id_pbf").attr("disabled","true");
			//$("#tanggal_jatuh_tempo").attr("readonly","true");
			//$("#diskon").attr("readonly","true");
			$("#id_barang").removeAttr("disabled");
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
			//$("#batch").val("");
			$("#qty").val("");
			//$("#subtotal").val("");
			$(document).ajaxStart(function() { Pace.restart(); });
		}
		$("#id_barang").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		//$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>