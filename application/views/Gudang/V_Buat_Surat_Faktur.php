<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php 
	if($Data->id_pembelian==NULL) :
		$no_surat = 'FK'.date('ymd').str_pad($TotalSurat+1, 5, '0', STR_PAD_LEFT); 
	else : 
		$no_surat = $Data->id_pembelian;
	endif;
?>
<script>
	$(function () {
		$('.select2').select2();
	})
</script>
<script>
		function goPrint(res) {
			window.open("<?= base_url('pembelian/prints/') ?>" + res,null,
			"height=550,width=800,status=yes,toolbar=no,menubar=no,location=no");
			window.location.href = "<?= base_url('pesanan') ?>";
		}
	</script>
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
		<!---<div class="container-fluid">-->
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-success">
						<div class="box-header">
							<span class="text-center">
								<h3>
									<?= $Instansi->nm_instansi ?><br />
									<small>
										<?= $Instansi->alamat_instansi ?><br />
										Apoteker : Ratna Marwah, S.Farm.,Apt<br />
										No. SIPA : 503/IV.I/KPTS.21.SIPA/2016
									</small>
								</h3>
							</span>
						</div>
						<div class="box-body">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Tanggal Pesan</label>
									<?= form_input('tgl_pesan',date("Y-m-d"),array('class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>No. Surat</label>
									<?= form_input('no_surat',$no_surat,array('class' => 'form-control')) ?>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>PBF</label>
									<select name="pbf" class="form-control select2" style="width: 100%;">
										<?php foreach($PBF as $b) : ?>
											<option value="<?= $b->id_pbf ?>" <?php if($Data->id_pbf==$b->id_pbf) : echo "selected"; endif ?>><?= $b->nm_pbf ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<table id="TableData" class="table table-bordered">
									<thead>
										<th>Nama Barang</th>
										<th>Qty</th>
										<th>No. Batch</th>
										<th>Jatuh Tempo</th>
										<th>Kadaluwarsa</th>
										<th>Harga Total (Rp)</th>
										<th>Diskon (%)</th>
										<th></th>
									</thead>
									<tbody>
										<?php foreach($DetBarang as $d) : ?>
											<tr>
												<?= form_open('pembelian/simpan-pembelian',array('id' => 'pembelian')) ?>
												<input type="text" style="visibility: hidden;position:fixed" name="id_barang" value="<?= $d->id_barang ?>">
												<input type="text" style="visibility: hidden;position:fixed" name="id_sp" value="<?= $d->id_pesanan ?>">
												<td><?= $d->nm_barang ?></td>
												<td><input type="text" name="qty" value="<?= $d->qty_raw ?>" class="form-control" value="<?= $d->qty ?> " <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<td><input type="text" name="batch" value="<?= $d->batch ?>" class="form-control" <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<td><input type="text" name="jatuh_tempo" value="<?= $d->tanggal_jatuh_tempo ?>" class="form-control" <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<td><input type="text" name="kadaluwarsa" value="<?= $d->kadaluarsa ?>" class="form-control" <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<td><input type="text" name="hrg_satuan" class="form-control" value="<?= $d->subtotal_barang ?>" <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<td><input type="text" name="diskon" class="form-control" value="<?= $d->diskon ?>" <?php if($d->checked) : echo "disabled"; endif ?>></td>
												<th>
													<button type="submit" class="btn btn-primary btn-block" <?php if($d->checked) : echo "disabled"; endif ?>>Save</button>
												</th>
												<?= form_close() ?>
											</tr>
										<?php endforeach ?>
									</tbody>
								</table>
							</div>
						<div class="box-footer">
							<span class="pull-right">
								<a href="<?= base_url('dashboard') ?>" class="btn btn-danger">Kembali</a>
								<button type="button" class="btn btn-primary" onclick="goPrint('<?= $no_surat ?>')">Print / Proses</button>
							</span>
						</div>
						
					</div>
				</div>
			<!--</div>-->
		</div>
	</section>
<?php endif ?>	