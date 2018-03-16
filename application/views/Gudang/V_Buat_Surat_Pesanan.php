<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
	if($this->uri->segment(3)==null) : 
			$no_surat = 'SP'.date('ymd').str_pad($TotalSurat+1, 5, '0', STR_PAD_LEFT);
			$status = "disabled";
			$status_alt = "";
	else :
			$no_surat = $this->uri->segment(3);
			$status = "";
			$status_alt = "disabled";
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
			<div class="col-lg-12">
				<div class="box box-danger">
					<?= form_open('pesanan/tambah-data') ?>
					<div class="box-header">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Pesan</label>
								<?= form_input('tgl_pesan',date("Y-m-d"),array('class' => 'form-control',$status_alt => '')) ?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>No. Surat</label>
								<?= form_input('no_surat',$no_surat,array('class' => 'form-control',$status_alt => '')) ?>
							</div>
						</div>
						<div class="col-lg-11">
							<div class="form-group">
								<label>PBF</label>
								<select name="pbf" class="form-control select2" style="width: 100%;" <?= $status_alt ?>>
									<?php foreach($PBF as $b) : ?>
										<option value="<?= $b->id_pbf ?>" <?php if($DetBarang->id_pbf==$d->id_pbf) : echo "selected"; endif; ?>><?= $b->nm_pbf ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-lg-1">
							<label style="margin-bottom: 2%"> </label>
							<button type="submit" style="margin-top:5%" class="btn btn-block btn-primary" <?= $status_alt ?>>Simpan</button>
						</div>
					</div>
					<?= form_close() ?>
					<div class="box-body">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nama Barang</label>
								<select id="barang" name="barang" class="form-control select2" style="width: 100%;" <?= $status ?>>
									<?php foreach($Barang as $b) : ?>
										<option value="<?= $b->id_barang ?>"><?= $b->nm_barang ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group">
								<label>Qty</label>
								<input id="qty" name="qty" placeholder="Qty" class="form-control" <?= $status ?>>
							</div>
						</div>
						<div class="col-lg-1">
							<label style="margin-bottom: 2%"> </label>
							<button type="button" id="submit" style="margin-top:5%" class="btn btn-block btn-danger" <?= $status ?>><i class="fa fa-plus"></i></button>
						</div>
						<div class="col-lg-12">
							<table id="TableData" class="table table-bordered">
								<thead>
									<th>Nama Barang</th>
									<th>Qty</th>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
					<div class="box-footer">
						<span class="pull-right">
							<a href="<?= base_url('dashboard') ?>" class="btn btn-danger">Kembali</a>
							<button type="button" class="btn btn-primary" onclick="goPrint('<?= $no_surat ?>')">Print</button>
							<a href="<?= base_url('pembelian/checkout/'.$no_surat) ?>" class="btn btn-success">Proses</a>
						</span>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script>
		$(function () {
			$('.select2').select2();
		})
		function goPrint(res) {
			window.open("<?= base_url('pesanan/prints/') ?>" + res,null,
			"height=550,width=800,status=yes,toolbar=no,menubar=no,location=no");
			window.location.href = "<?= base_url('pesanan') ?>";
		}
		$(document).ready(function(){
			var table = $('#TableData').DataTable({
				"processing": true, //Feature control the processing indicator.
				"ajax": {
		            "url": "<?php echo site_url('pesanan/ajax_list/'.$no_surat)?>",
		            "type": "POST"
		        },
				"autoWidth": true,
				"info": false,
				"ordering": false,
				"paging": false,
				"pageChange": false,
				"searching": false
			});
			$('#submit').on('click', function() {
				event.preventDefault();
				var barang = $("#barang").val();
				var qty = $("#qty").val();
				jQuery.ajax({
					type: "POST",
					url: "<?= base_url('pesanan/tambah-barang/'.$no_surat) ?>",
					dataType: 'json',
					data: {barang:barang, qty:qty},
					success: function(res) {
						//alert(res);
						if(res) {
							table.ajax.reload();
						}
					}
				});
				table.ajax.reload();
			})
			$('#TableData tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("are you sure you want to remove the item?");
				if (confirmation) {
					window.location.href = "<?= base_url('pesanan/del-data/') ?>"+id;
				}
			});
			$('#TableData tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("are you sure you want to edit the item?");
				if (confirmation) {
					window.location.href = "<?= base_url('pesanan/buat-surat-pesanan/') ?>"+id;
				}
			});
		});
</script>
<?php endif ?>	