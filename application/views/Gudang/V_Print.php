<script>window.onload = function() { window.print(); }</script>
<center onload="print();">
	<h3>
		<?= $Instansi->nm_instansi ?><br />
		<small>
			<?= $Instansi->alamat_instansi ?><br />
			Apoteker : Ratna Marwah, S.Farm.,Apt<br />
			No. SIPA : 503/IV.I/KPTS.21.SIPA/2016
		</small>
	</h3>
	<hr />
</center>
<div class="col-lg-6">
	<table style="width: 100%;max-width: 100%;margin-bottom: 20px;">
		<tr>
			<td>Tanggal Pesan</td>
			<td>:</td>
			<td>
				<?= date("Y-m-d") ?>
			</td>
		</tr>
		<tr>
			<td>No. Surat</td>
			<td>:</td>
			<td>
				<?php
						if($this->uri->segment(3)==null) : 
								$no_surat = 'SP'.date('ymd').str_pad($TotalSurat+1, 5, '0', STR_PAD_LEFT);
						else :
								$no_surat = $this->uri->segment(3);
						endif;
				?>
				<?= $no_surat ?>
			</td>
		</tr>
		<tr>
			<td>PBF</td>
			<td>:</td>
			<td>
				<?= $Data->nm_pbf ?>
			</td>
		</tr>
	</table>
</div>
<div class="col-lg-12">
	<table width=100%>
		<thead>
			<th>Nama Barang</th>
			<th>Qty</th>
		</thead>
		<tbody>
			<?php foreach($DetBarang as $d) : ?>
				<tr>
					<td style="text-align: left"><?= $d->nm_barang ?></td>
					<td style="text-align: center"><?= $d->qty ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>
<br />
	<br />
	<br>
	<br>
<table width="100%">
	<tr>
			<td style="height:150px">Mengetahui/Menyetujui<br />
					Pemilik Sarana Apotek
			</td>
			<td>Hormat Kami</td>
	</tr>
	<tr>
		<td>Drs. Toto Suharto, S.Farm.,Apt
		<td>Ratna Marwah, S.Farm.,Apt</td>
	<tr>
</table>