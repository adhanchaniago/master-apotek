<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level=="Master" OR $Level=="Pemilik") : ?>
<section class="content-header">
	<h1><?= $Title ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li> <?= $Nav ?></li>
			<li class="active"> <?= $Title ?></li>
		</ol>
</section>
<section class="content">
	<div class="error-page">
		<h2 class="headline text-yellow"> 404</h2>

		<div class="error-content">
			<h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>

			<p>
				Halaman sedang dalam pemulihan atau belum tersedia...
				Anda dapat kembali ke <a href="../../index.html">dashboard</a> atau hubungi team teknis. Terima kasih, mohon maaf atas ketidaknyamanan anda :(
			</p>
		</div>
	</div>
</section>
<?php endif ?>