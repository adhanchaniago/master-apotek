<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<?php
		if($this->uri->segment(3)==null) : 
				$no_surat = 'TR'.date('ymd').str_pad($TotalTransaksi+1, 5, '0', STR_PAD_LEFT);
				$status = "readonly";
				$status_alt = "";
		else :
				$no_surat = $this->uri->segment(3);
				$status = "";
				$status_alt = "readonly";
		endif;
	?>
<?php if($Level="IT Support") : ?>
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
			<div class="col-lg-8">
				<div class="box box-danger">
					<div class="box-body">
						<table id="TableData" class="table table-responsive table-bordered">
							<thead>
								<th>Nama Barang</th>
								<th>Harga Satuan</th>
								<th>Qty</th>
								<th>Diskon</th>
								<th>Total</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-danger">
					<?= form_open('penjualan/tambah-data-pj') ?>
					<div class="box-header">
						<div class="col-lg-12">
							<label>Nama Pasien</label>
							<?= form_input('nm_pasien','Tanpa Nama',array('class' => 'form-control','placeholder' => 'Nama Pasien')) ?>
						</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="barang" id="barang" class="select2 form-control">
										<?php foreach($Barang as $b) : ?>
											<option value="<?= $b->id_barang ?>" <?php if($b->stok<=0) : echo 'disabled="true"'; endif ?>><?= $b->nm_barang ?> | <?= $b->stok ?> <?= $b->nm_satuan ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Qty</label>
									<?= form_input('qty',null,array('id' => 'qty','class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-lg-8">
								<div class="form-group">
									<label>Diskon</label>
									<?= form_input('diskon',null,array('id' => 'diskon','class' => 'form-control','disabled' => 'true')) ?>
								</div>
							</div>
							<div class="col-lg-12" style="margin-top: 1.8%;">
								<div class="form-group">
									<button type="submit" id="submit" class="btn btn-danger"><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-danger"><i class="fa fa-unlock"></i></button>
								</div>
							</div>
					</div>
					<?= form_close() ?>
					<div class="box-body">
						<div class="col-lg-6">
							<span class="pull-left label label-default" style="font-size: 1.5em;display: inline;">SUBTOTAL</span>
						</div>
						<div class="col-lg-6" style="margin-bottom: 2%">
							<span class="label label-danger pull-right" style="font-size: 1.5em;display: inline;"><?= 'Rp. '.number_format($Subtotal,2,",",".") ?></span>
						</div>
						<div class="col-lg-8">
							<div class="form-group">
								<label>Bayar</label>
								<input type="number" name="bayar" id="bayar" class="form-control">
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>Kasbon</label>
								<select name="kasbon" class="form-control">
									<option value="0">Tidak</option>
									<option value="1">Ya</option>
								</select>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="col-lg-4">
							<button type="button" onclick="simpan()" class="btn btn-danger btn-block" <?php if($this->uri->segment(3)==null) : echo "disabled"; endif; ?>>
								Bayar
							</button>
						</div>
						<div class="col-lg-4">
							<button type="button" onclick="cancel()" class="btn btn-md btn-danger btn-block">
								Batal
							</button>
						</div>
						<div class="col-lg-4">
							<button type="button" onclick="cancel()" class="btn btn-md btn-danger btn-block">
								Close
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		$('#tgl_beli').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
    $('#tgl_jth_tempo').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
    $('#kadaluwarsa').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
    $(function () {
			$('#select, .select2, #select3').select2();
		});
		$(document).ready(function(){
			var table = $('#TableData').DataTable({
				//"processing": true,
				"ajax": {
					"url": "<?php echo site_url('penjualan/data-pj-non-resep/'.$no_surat)?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": false,
				"ordering": false,
				"paging": false,
				"searching": false
			});
			$('#submit').on('click', function() {
			event.preventDefault();
				var nm_pasien = $("#nm_pasien").val();
				var barang = $("#barang").val();
				var qty = $("#qty").val();
				var diskon = $("#diskon").val();
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('penjualan/tambah_data_pj/') ?>",
					dataType: 'json',
					data: {nm_pasien:nm_pasien, barang:barang, qty:qty, diskon:diskon}
				});
				$('#TableData').DataTable().ajax.reload();
			//location.reload();
			})
		});
		function simpan() {
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
				var bayar = $("#bayar").val();
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('penjualan/simpan-tr/'.$no_surat) ?>",
					dataType: 'json',
					data: {bayar: bayar},
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
	</script>
<?php endif ?>