<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support") : ?>
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
	<section class="content-header">
		<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard Admin</a></li>
			<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
			<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-lg-8">
				<div class="box box-headernger">
					<?= form_open('penjualan/simpan-data-resep') ?>
						<div class="box-header row">
							<div class="col-lg-4">
								<label>No. Transaksi</label>
								<?= form_input('id_penjualan',$no_surat,array('class' => 'form-control','placeholder' => 'ID Transaksi',$status_alt => '')) ?>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Nama Dokter</label>
									<?= form_input('nama_dokter',$Data->row()->nm_dokter,array('class' => 'form-control','placeholder' => 'Nama Dokter',$status_alt => '')) ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Nama Pasien</label>
									<?= form_input('nama_pasien',$Data->row()->nm_pasien,array('class' => 'form-control','placeholder' => 'Nama Pasien',$status_alt => '')) ?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Kontak</label>
									<?= form_input('kontak',$Data->row()->kontak_pasien,array('class' => 'form-control','placeholder' => 'Kontak Pasien',$status_alt => '')) ?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Alamat</label>
									<?= form_input('alamat',$Data->row()->alamat,array('class' => 'form-control','placeholder' => 'Alamat',$status_alt => '')) ?>
								</div>
							</div>
							<div class="col-lg-12">
								<button type="submit" class="btn btn-md btn-danger btn-block" <?= $status_alt ?>>
									Simpan Data Pasien
								</button>
							</div>
						</div>
					<?= form_close() ?>
					<div class="box-body row">
						<div class="col-lg-12">
							<table id="example" class="table table-bordered table-responsive">
								<thead>
									<th>Nama Barang</th>
									<th>Harga Satuan</th>
									<th>Qty</th>
									<th>Etiket</th>
									<th>Diskon</th>
									<th>Total</th>
									<th></th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box box-danger">
					<div class="box-header">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Nama Barang</label>
									<select name="barang" id="barang" class="select2 form-control">
										<?php foreach($Barang as $b) : ?>
											<option value="<?= $b->id_barang ?>" <?php if($b->stok_tersedia<=0) : echo 'disabled="true"'; endif ?>><?= $b->nm_barang ?> | <?= $b->stok_tersedia ?> <?= $b->nm_satuan ?></option>
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
							<div class="col-lg-4">
								<div class="form-group">
									<label>Diskon</label>
									<?= form_input('diskon',null,array('id' => 'diskon','class' => 'form-control','disabled' => 'true')) ?>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Tus+Embl</label>
									<?= form_input('emblage',null,array('id' => 'emblage','class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Etiket</label>
									<?= form_input('etiket',null,array('id' => 'etiket','class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-lg-12" style="margin-top: 1.8%;">
								<div class="form-group">
									<button type="button" id="submit" class="btn btn-danger" <?php if($this->uri->segment(3)==null) : echo "disabled"; endif; ?>><i class="fa fa-plus"></i></button>
									<button type="button" class="btn btn-danger"><i class="fa fa-unlock"></i></button>
									<?php if($Title=="Penjualan Resep") : ?>
										<button type="button" class="btn btn-danger">Racikan</button>
									<?php endif ?>
								</div>
							</div>
					</div>
					<div class="box-body">
						<div class="col-lg-6">
							<span class="pull-left label label-default" style="font-size: 1.5em;display: inline;">SUBTOTAL</span>
						</div>
						<div class="col-lg-6" style="margin-bottom: 2%">
							<span class="label label-danger pull-right" style="font-size: 1.5em;display: inline;"><?= 'Rp. '.number_format($Subtotal->subtotal,2,",",".") ?></span>
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
	<div id="Edit" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-yellow">
					<h4 class="modal-title">Form Edit</h4>
				</div>
				<div class="modal-body row">
					<div class="col-lg-12">
						<div class="form-group">
							<label>Nama Barang</label>
							<select name="barang" id="e_barang" class="select3 form-control">
								<?php foreach($Barang as $b) : ?>
									<option value="<?= $b->id_barang ?>"><?= $b->nm_barang ?> | <?= $b->stok_tersedia ?> <?= $b->nm_satuan ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Qty</label>
							<?= form_input('qty',null,array('class' => 'form-control','id' => 'e_qty')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Etiket</label>
							<?= form_input('etiket',null,array('class' => 'form-control','id' => 'e_etiket')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Diskon</label>
							<?= form_input('diskon',null,array('class' => 'form-control','id' => 'e_diskon','disabled' => 'true')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label style="margin-bottom: 5%;"">Paramedis</label><br />
							<label class="radio-inline"><input type="radio" name="paramedis" value="1">Ya</label>
							<label class="radio-inline"><input type="radio" name="paramedis" value="0">Tidak</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			var table = $('#example').DataTable({
				"processing": true, //Feature control the processing indicator.
				"ajax": {
								"url": "<?php echo site_url('penjualan/ajax_list/'.$no_surat)?>",
								"type": "POST"
						},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": false,
				"pageChange": false,
				"searching": false,
				"columnDefs": [{
					"targets": -1,
					"data": null,
					"defaultContent": 
						"<div class='btn-group'><button type='button' id='edit' class='btn btn-warning btn-xs'>Edit</button><button type='button' id='delete' class='btn btn-danger btn-xs'>Hapus</button></div>"

				}]
			});
			$('#submit').on('click', function() {
				event.preventDefault();
					var id_penjualan = $("#id_penjualan").val();
					var barang = $("#barang").val();
					var qty = $("#qty").val();
					var diskon = $("#diskon").val();
					var etiket = $("#etiket").val();
					var paramedis = $("#paramedis").val();
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/tambah-data/'.$no_surat) ?>",
						dataType: 'json',
						data: {id_penjualan:id_penjualan, barang:barang, qty:qty, diskon:diskon, etiket:etiket, paramedis:paramedis},
						success: function(res) {
						if(res) {
							table.ajax.reload();
						}
					}
				});
				table.ajax.reload();
				location.reload();
			})
			$('#example tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[6];
				var confirmation = confirm("are you sure you want to remove the item?");
				if (confirmation) {
					window.location.href = "<?= base_url('penjualan/del-data/'.$no_surat.'/') ?>"+id;
				}
			});
			$('#example tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				table.ajax.reload( null, false );
				$('#Edit').modal('show');
				$("#e_qty").val(data[2]);
				$("#e_etiket").val(data[3]);
				$("#e_diskon").val(data[4]);
			});
		});
		function addBrg() {
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
					var id_penjualan = $("#id_penjualan").val();
					var barang = $("#barang").val();
					var qty = $("#qty").val();
					var diskon = $("#diskon").val();
					var etiket = $("#etiket").val();
					var paramedis = $("#paramedis").val();
					jQuery.ajax({
						type: "POST",
						url: "<?= base_url('penjualan/tambah-data/'.$no_surat) ?>",
						dataType: 'json',
						data: {id_penjualan:id_penjualan, barang:barang, qty:qty, diskon:diskon, etiket:etiket, paramedis:paramedis},
						success: function(res) {
							if (res)
							{
								$('#example').DataTable().ajax.reload();
							} else {
								swal("Qty Melebihi batas");
							}
						}
					});
				} else {
					swal("Your imaginary file is safe!");
				}
			});
		}
		$(function () {
			$('.select2, .select3').select2();
		})
		function cancel() {
			swal({
				title: "Are you sure?",
				text: "Once deleted, you will not be able to recover this imaginary file!",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					window.location.href = "<?= base_url() ?>";
				} else {
					swal("Your imaginary file is safe!");
				}
			});
		}
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