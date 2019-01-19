<?php
		echo \Asset::js([
			'angular/angular.min.js',
			'angular/angular-route.min.js',
			'angular/angular-sanitize.min.js',
			'angular/angular-gettext.js',
			'angular/angular-multi-select.js',
			'angular/xeditable.min.js',
			'angular/dirPagination.js',
			'angular/angular-block-ui.min.js',
			'jquerymain.js',
			'script.js',
			'jquery-1.7.2.min.js',
			'nav.js',
			'move-top.js',
			'easing.js',
			'nav-hover.js',			
		]);
	?>
	<script type="text/javascript">
		$(document).ready(function($){
			$('#dc_mega-menu-orange').dcMegaMenu({rowItems:'4',speed:'fast',effect:'fade'});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
		
		$().UItoTop({ easingType: 'easeOutQuart' });
		
	});
	</script>
    <a href="#" id="toTop" style="display: block;"><span id="toTopHover" style="opacity: 1;"></span></a>
    
	  <script defer src="<?php echo \Uri::base(false); ?>assets/js/jquery.flexslider.js"></script>
	  <script type="text/javascript">
		$(function(){
		  SyntaxHighlighter.all();
		});
		$(window).load(function(){
		  $('.flexslider').flexslider({
				animation: "slide",
				start: function(slider){
					$('body').removeClass('loading');
				}
		  });
		});
	  </script>