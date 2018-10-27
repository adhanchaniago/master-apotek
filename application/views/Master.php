<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
	<?php $this->load->view('template/v_header') ?>
	<body class="hold-transition skin-purple layout-top-nav">
		<a id="up" href="#down"></a>
		<div class="up"></div>
		<div class="wrapper">
			<div class="cover"></div>
			<header class="main-header">
				<?php $this->load->view('template/v_navbar') ?>
			</header>
			<main class="content-wrapper">
				<?php $this->load->view($Konten) ?>
			</main>
			<footer class="main-footer">
				<?php $this->load->view('template/v_footer') ?>
			</footer>
		</div>
		<script>
			$(document).ready(function () {
				$('.sidebar-menu').tree();
				$('#up, #down').on('click', function(e){
		    		e.preventDefault();
		    		var target= $(this).get(0).id == 'up' ? $('#down') : $('#up');
		    		$('html, body').stop().animate({
		       			scrollTop: target.offset().top
		    		}, 1000);
				});
				$('.dropdown-submenu a.test').on("click", function(e){
					$(this).next('ul').toggle();
					e.stopPropagation();
					e.preventDefault();
				});
			})
		</script>
	</body>
</html>