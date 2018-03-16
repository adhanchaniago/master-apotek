<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
	if($Data->id_pembelian==NULL) :
		$no_surat = 'FK'.date('ymd').str_pad($TotalSurat+1, 5, '0', STR_PAD_LEFT); 
	else : 
		$no_surat = $Data->id_pembelian;
	endif;
	if($this->uri->segment(3)!=NULL) : 
		$stat = "disabled";
	else :
		$stat = "";
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
			<div class="col-lg-4">
				<div class="box box-danger">
					<?= form_open('pembelian/generate-faktur') ?>
					<div class="box-header">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Pembelian</label>
								<?= form_input('tgl_beli',date('Y-m-d'),array('id' => 'tgl_beli','class' => 'form-control','data-provide' => 'datepicker')) ?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Jatuh Tempo</label>
								<?= form_input('tgl_jth_tempo',$Data->tanggal_jatuh_tempo,array('id' => 'tgl_jth_tempo','class' => 'form-control','data-provide' => 'datepicker')) ?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nomor Faktur</label>
								<?= form_input('no_faktur',$no_surat,array('class' => 'form-control',$stat => '')) ?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>PBF</label>
								<select name="pbf" id="select" class="form-control">
									<option value="">== Pilih PBF ==</option>
									<?php foreach($PBF as $p) : ?>
										<option value="<?= $p->id_pbf ?>" <?php if($Data->id_pbf==$p->id_pbf) : echo "selected"; endif ?>><?= $p->nm_pbf ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<span class="pull-right">
							<button type="submit" class="btn btn-primary" <?= $stat ?>>Simpan</button>
							<a href="<?= base_url() ?>" class="btn btn-danger">Selesai</a>
							<button type="button" class="btn btn-success" onclick="prints()">Print</button>
						</span>
					</div>
					<?= form_close() ?>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="box box-danger">
					<div class="box-header">
						<div class="col-lg-12" style="margin-bottom: 1%">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add">Tambah Barang</button>
						</div>
					</div>
					<div class="box-body">
						<div class="col-lg-12">
							<table id="TableData" class="table table-responsive table-bordered">
								<thead>
									<th>ID Detail</th>
									<th>Nama Barang</th>
									<th>Qty</th>
									<th>Batch</th>
									<th>Kadaluwarsa</th>
									<th>Harga Satuan</th>
									<th>Diskon</th>
									<th>Subtotal</th>
									<th></th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div id="add" class="modal fade" role="dialog" style="width: auto;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-blue">
					<h4 class="modal-title">Tambah Barang</h4>
				</div>
				<?= form_open('pembelian/add-items/'.$no_surat) ?>
				<div class="modal-body row">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Barang</label>
							<select name="id_barang" class="form-control">
								<option value="">== Pilih Barang ==</option>
								<?php foreach($Barang as $b) : ?>
									<option value="<?= $b->id_barang ?>"><?= $b->nm_barang ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Qty</label>
							<?= form_input('qty',null,array('class' => 'form-control','required' => 'true')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Batch</label>
							<?= form_input('batch',null,array('class' => 'form-control')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Kadaluwarsa</label>
							<?= form_input('kadaluwarsa',null,array('id' => 'kadaluwarsa','class' => 'form-control','required' => 'true')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Harga</label>
							<?= form_input('hrg_satuan',null,array('class' => 'form-control')) ?>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Diskon</label>
							<?= form_input('diskon',null,array('class' => 'form-control')) ?>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				<?= form_close() ?>
			</div>
		</div>
	</div>
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
			$('#select, #select2, #select3').select2();
		});
		$(document).ready(function(){
			var table = $('#TableData').DataTable({
				"processing": true, //Feature control the processing indicator.
				"ajax": {
					"url": "<?php echo site_url('pembelian/ajax_list/'.$no_surat)?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": false,
				"ordering": true,
				"paging": false,
				"searching": true,
				"columnDefs": [
					{
					"targets": -1,
					"data": null,
					"defaultContent": 
						"<center><div class='btn-group'><button type='button' id='edit' class='btn btn-warning btn-xs'>Edit</button><button type='button' id='delete' class='btn btn-danger btn-xs'>Hapus</button></div></center>"

					}
				]
			});
			$('#dtTable tbody').on('click', '#delete', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var confirmation = confirm("Apakah anda yakin ingin menghapus barang ini?");
				if (confirmation) {
					window.location.href = "<?= base_url('pabrik/del-data/') ?>"+id;
				}
			});
			$('#dtTable tbody').on('click', '#edit', function(){
				var data = table.row($(this).parents('tr')).data();
				var id = data[0];
				var url = "<?= base_url('pabrik/edit-data/') ?>"+id
				popupWindow = window.open(
					url,
					'popUpWindow','height=300,width=450,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes'
				)
			});
		});
	</script>
<?php endif ?>