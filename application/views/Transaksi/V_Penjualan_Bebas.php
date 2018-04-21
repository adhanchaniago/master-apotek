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
							<div class="col-lg-12">
								<div class="form-group">
									<label for="nm_pasien">Nama Pasien</label>
									<?= form_input('nm_pasien',null,array(
																			'id' => 'nm_pasien',
																			'class' => 'form-control',
																			//'placeholder' => 'Nama Pasien',
																			'required' => 'true'
																		));
									?>
									<input type="hidden" id="id_penjualan_bebas" />
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
								<button type="button" class="btn btn-block btn-primary" onclick="bayar()">Bayar</button>
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
			$("#nm_pasien").focus();
			var dtTable = $('#dtTable').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?= base_url('penjualan/list_detail_data/'.$this->uri->segment(3)) ?>",
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
				var id_barang = $("#id_barang").val();
				var qty = $("#qty").val();
				var diskon = $("#diskon").val();
				var kasbon = $("#kasbon").val();
				Pace.track(function(){
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/tambah_data/'.$this->uri->segment(3)) ?>",
						dataType: 'json',
						data: {
								nm_pasien : nm_pasien,
								id_barang : id_barang,
								qty : qty,
								diskon : diskon,
								kasbon : kasbon
							},
						success: function(data) {
							if(data.status) {
								$("#dtTable").DataTable().ajax.reload();
								$("#Hapus").attr("disabled","true");
								$("#nm_pasien").attr("disabled","true");
								//$("#register")[0].reset();
								$("#id_barang").focus();
								$("#qty").val("");
								$("#diskon").val("");
								$(document).ajaxStart(function() { Pace.restart(); });
							} else {
								alert('Barang sudah di tambahkan!');
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
				url: "<?= base_url('penjualan/get_data/'.$this->uri->segment(3)) ?>",
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
				url: "<?= base_url('penjualan/hapus_data/'.$this->uri->segment(3)) ?>",
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
		function bayar() {
			swal({
					text: 'Uang yang harus di bayar',
					content: "input",
					button: {
						text: "Proses",
						closeModal: false,
					},
				})
			.then(bayar => {
				if (!bayar) throw null;
				return fetch(`<?= base_url('penjualan/simpan_semua/') ?>${bayar}`);
			})
			.then(results => {
				return results.json();
			})
			.then(json => {
				const movie = json.results[0];
				if (!movie) {
					return swal("No movie was found!");
			}
			const name = movie.trackName;
			const imageURL = movie.artworkUrl100;
			swal({
					title: "Top result:",
					text: name,
					icon: imageURL,
				});
			})
			.catch(err => {
							if (err) {
								swal("Oh noes!", "The AJAX request failed!", "error");
							} else {
								swal.stopLoading();
								swal.close();
							}
			});
		}
		$("#id_barang").select2();
		//$('#tgl_lahir_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
		//$('#tanggal_kunjungan_pasien').inputmask('yyyy-mm-dd', { 'placeholder': 'yyyy-mm-dd' })
	</script>
<?php endif ?>