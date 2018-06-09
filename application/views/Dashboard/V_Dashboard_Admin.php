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
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border"><h3 class="box-title">Rekap Tahunan</h3></div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-8">
								<p class="text-center">
									<strong>Penjualan: Januari, <?= date('Y') ?> - Desember, <?= date('Y') ?></strong>
								</p>
								<div class="chart">
									<canvas id="salesChart" style="height: 180px;"></canvas>
								</div>
							</div>
							<div class="col-md-4">
								<p class="text-center">
									<strong>Rangkuman Data</strong>
								</p>
								<div class="progress-group">
									<span class="progress-text">Stok habis</span>
									<span class="progress-number"><b><?= $Limit ?></b>/<?= $Barang ?></span>
									<div class="progress sm">
										<div class="progress-bar progress-bar-aqua" style="width: <?= $Limit/$Barang*100 ?>%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Barang kadaluarsa</span>
									<span class="progress-number"><b><?= $Kadaluarsa ?></b>/<?= $Detail ?></span>
									<div class="progress sm">
										<div class="progress-bar progress-bar-red" style="width: <?= $Kadaluarsa/$Detail*100 ?>%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Pembelian yang belum di bayar</span>
									<span class="progress-number"><b><?= $Hutang ?></b>/<?= $Pembelian ?></span>
									<div class="progress sm">
										<div class="progress-bar progress-bar-green" style="width: <?= $Hutangs/$Pembelian*100 ?>%"></div>
									</div>
								</div>
								<div class="progress-group">
									<span class="progress-text">Penjualan yang belum lunas</span>
									<span class="progress-number"><b><?= $Piutang ?></b>/<?= $Penjualan ?></span>
									<div class="progress sm">
										<div class="progress-bar progress-bar-yellow" style="width: <?= $Piutang/$Penjualan*100 ?>%"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="row">
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-blue"><i class="fa fa-download"></i></span>
									<h5 class="description-header"><?= "Rp. ".number_format($Pendapatan,2,",",".") ?></h5>
									<span class="description-text">TOTAL PENDAPATAN</span>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-yellow"><i class="fa fa-upload"></i></span>
									<h5 class="description-header"><?= "Rp. ".number_format($Pengeluaran,2,",",".") ?></h5>
									<span class="description-text">TOTAL PENGELUARAN</span>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6">
								<div class="description-block border-right">
									<span class="description-percentage text-green"><i class="fa fa-money"></i></span>
									<h5 class="description-header"><?= "Rp. ".number_format($Pendapatan-$Pengeluaran,2,",",".") ?></h5>
									<span class="description-text">TOTAL KEUNTUNGAN</span>
								</div>
							</div>
							<div class="col-sm-3 col-xs-6">
								<div class="description-block">
									<span class="description-percentage text-red"><i class="fa fa-credit-card"></i></span>
									<h5 class="description-header"><?= "Rp. ".number_format($Hutang,2,",",".") ?></h5>
									<span class="description-text">TOTAL HUTANG</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-cube"></i>
						<h3 class="box-title">Saldo Stok Tersedia</h3>
					</div>
					<div class="box-body">
						<table id="dtStok" class="table table-striped table-bordered">
							<thead>
								<th>Nama Barang</th>
								<th>Stok Minimum</th>
								<th>Sisa Stok</th>
								<th>Status</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-exclamation-triangle"></i>
						<h3 class="box-title">Barang Expired</h3>
					</div>
					<div class="box-body">
						<table id="dtExpire" class="table table-striped table-bordered">
							<thead>
								<th>Nama Barang</th>
								<th>Batch</th>
								<th>Tanggal Kadaluarsa</th>
								<th>Sisa Hari</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<i class="fa fa-credit-card"></i>
						<h3 class="box-title">Hutang Jatuh Tempo</h3>
					</div>
					<div class="box-body">
						<table id="dtHutang" class="table table-striped table-bordered">
							<thead>
								<th>Nomor Faktur</th>
								<th>Nama PBF</th>
								<th>Tanggal Pembelian</th>
								<th>Tanggal Jatuh Tempo</th>
								<th>Sisa Hari</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="scroll">
			<a id="down" href="#up">
				<i class="fa fa-4x fa-angle-up"></i>
			</a>
		</div>
	</section>
	<script src="<?= base_url('assets/Chart/Chart.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.sparkline.min.js') ?>"></script>
	<script>
		$(document).ready(function(){
			var dtStok = $('#dtStok').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('stok/data_stok_tersedia/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			var dtExpire = $('#dtExpire').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('barang/data_barang_expired/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
			var dtHutang = $('#dtHutang').DataTable({
				"processing": true,
				"ajax": {
					"url": "<?php echo site_url('pembelian/data_jatuh_tempo/')?>",
					"type": "POST"
				},
				"autoWidth": true,
				"info": true,
				"ordering": false,
				"paging": false,
				//"pageLength": 5,
				"lengthChange": false,
				"searching": false
			});
		});
		$(function () {
			'use strict';
			var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
			var salesChart       = new Chart(salesChartCanvas);
			var salesChartData = {
									labels  : [
												'Januari', 
												'Februari', 
												'Maret', 
												'April', 
												'Mei', 
												'Juni', 
												'Juli', 
												'Agustus', 
												'September', 
												'Oktober', 
												'November', 
												'Desember'
											  ],
									datasets: [
												{
													label               : 'Digital Goods',
													fillColor           : 'rgba(60,141,188,0.9)',
													strokeColor         : 'rgba(60,141,188,0.8)',
													pointColor          : '#3b8bba',
													pointStrokeColor    : 'rgba(60,141,188,1)',
													pointHighlightFill  : '#fff',
													pointHighlightStroke: 'rgba(60,141,188,1)',
													data                : [
																			<?php 
																				foreach($Statistik as $b) : 
																					echo $b->Jan.',';
																					echo $b->Feb.',';
																					echo $b->Mar.',';
																					echo $b->Apr.',';
																					echo $b->May.',';
																					echo $b->Jun.',';
																					echo $b->Jul.',';
																					echo $b->Aug.',';
																					echo $b->Sep.',';
																					echo $b->Okt.',';
																					echo $b->Nov.',';
																					echo $b->Dec.',';
																				endforeach;
																			?>
																		  ]
												}
											  ]
								 };
			var salesChartOptions = {
				// Boolean - If we should show the scale at all
				showScale               : true,
				// Boolean - Whether grid lines are shown across the chart
				scaleShowGridLines      : false,
				// String - Colour of the grid lines
				scaleGridLineColor      : 'rgba(0,0,0,.05)',
				// Number - Width of the grid lines
				scaleGridLineWidth      : 1,
				// Boolean - Whether to show horizontal lines (except X axis)
				scaleShowHorizontalLines: true,
				// Boolean - Whether to show vertical lines (except Y axis)
				scaleShowVerticalLines  : true,
				// Boolean - Whether the line is curved between points
				bezierCurve             : true,
				// Number - Tension of the bezier curve between points
				bezierCurveTension      : 0.3,
				// Boolean - Whether to show a dot for each point
				pointDot                : false,
				// Number - Radius of each point dot in pixels
				pointDotRadius          : 4,
				// Number - Pixel width of point dot stroke
				pointDotStrokeWidth     : 1,
				// Number - amount extra to add to the radius to cater for hit detection outside the drawn point
				pointHitDetectionRadius : 20,
				// Boolean - Whether to show a stroke for datasets
				datasetStroke           : true,
				// Number - Pixel width of dataset stroke
				datasetStrokeWidth      : 2,
				// Boolean - Whether to fill the dataset with a color
				datasetFill             : true,
				// String - A legend template
				legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
				// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
				maintainAspectRatio     : true,
				// Boolean - whether to make the chart responsive to window resizing
				responsive              : true
			};

				// Create the line chart
				salesChart.Line(salesChartData, salesChartOptions);

				// ---------------------------
				// - END MONTHLY SALES CHART -
				// ---------------------------
			});
	</script>
<?php endif ?>