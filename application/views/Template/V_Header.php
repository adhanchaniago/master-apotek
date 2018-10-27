<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>SISTEM RETAIL - <?= $Title ?></title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/daterangepicker.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap-datepicker.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/font-awesome/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/select2/select2.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/AdminLTE/css/AdminLTE.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/AdminLTE/css/skins/skin-purple.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/sweet-alert/sweetalert.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/datatables/datatables.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('assets/pace/themes/purple/pace-theme-center-atom.css'); ?>">
	<style>
		.ui-autocomplete {
			max-height: 100px;
			overflow-y: auto;
			/* prevent horizontal scrollbar */
			overflow-x: hidden;
		}
		* html .ui-autocomplete {
			height: 100px;
		}
		.cover{
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 1999;
			background: #34495e;
		}
		/* .dataTables_wrapper { min-height: 300px; } */
		.scroll {
			position:fixed;
			right:20px;
			bottom:20px;
			background:#b2b2b2;
			background:rgba(178,178,178,0.7);
			padding:15px;
			border-radius: 5px;
			text-align: center;
			margin: 0 0 0 0;
			cursor:pointer;
			transition: 0.5s;
			-moz-transition: 0.5s;
			-webkit-transition: 0.5s;
			-o-transition: 0.5s; 		
		}
		.scroll .fa {
			font-size:30px;
			margin-top:-5px;
			margin-left:1px;
			transition: 0.5s;
			-moz-transition: 0.5s;
			-webkit-transition: 0.5s;
			-o-transition: 0.5s; 	
		}
		.dropdown-submenu {
			position: relative;
		}

		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-top: -1px;
		}
	</style>
	<script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/jquery/jquery.slimscroll.min.js') ?>"></script>
	<script src="<?= base_url('assets/dist/js/fastclick.js') ?>"></script>
	<script src="<?= base_url('assets/AdminLTE/js/adminlte.min.js') ?>"></script>
	<script src="<?= base_url('assets/select2/select2.full.min.js') ?>"></script>
	<script src="<?= base_url('assets/datatables/datatables.min.js') ?>"></script>
	<script src="<?= base_url('assets/sweet-alert/sweetalert.min.js') ?>"></script>
	<script src="<?= base_url('assets/pace/pace.min.js') ?>"></script>
	<script>Pace.on("done", function(){ $(".cover").fadeOut(1000); });</script>
</head>