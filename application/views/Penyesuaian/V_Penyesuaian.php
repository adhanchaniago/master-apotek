<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support") : ?>
	<div class="container-fluid">
		<section class="content-header">
			<h1><?= $Title ?></h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
				<li><a><i class="fa fa-dashboard"></i> <?= $Nav ?></a></li>
				<li class="active"><i class="fa fa-building"></i> <?= $Title ?></li>
			</ol>
		</section>
		<section class="content">
			<div class="row">
				<div class="col-lg-12">
					<div class="box box-danger">
						<div class="box-body">
							<table id="dtTable" class="table table-responsive table-bordered">
								<thead>
									<th>Nama Barang</th>
									<th>Stok Sistem</th>
									<th>Stok Real</th>
									<th>Penyesuaian</th>
									<th></th>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php endif ?>