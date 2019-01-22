<script type="text/javascript">
    var baseURL = '<?php echo \Uri::base(false); ?>';
    var userInfo = {
        emp_id: '<?php echo empty($user['emp_id']) ? '' : $user['emp_id']; ?>',
        emp_nm: '<?php echo empty($user['emp_nm']) ? '' : $user['emp_nm']; ?>',
        system_role_cd: '<?php echo empty($user['system_role_cd']) ? '' : $user['system_role_cd']; ?>',
        agency_cd: '<?php echo empty($user['agency_cd']) ? '' : $user['agency_cd']; ?>',
    };
</script>
<?php
		echo \Asset::js([
			'jquerymain.js',
			'script.js',
      'jquery-1.7.2.min.js',
      'bootstrap.min.js',
			'nav.js',
			'move-top.js',
			'easing.js',
			'nav-hover.js',
			'jquery.flexslider.js',
			'angular/angular.min.js',
			'angular/angular-route.min.js',
			'angular/angular-sanitize.min.js',
			'angular/angular-gettext.js',
			'angular/angular-multi-select.js',
			'angular/xeditable.min.js',
			'angular/dirPagination.js',
			'angular/angular-block-ui.min.js',
			
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
    	  
	  <script type="text/javascript">
		$(function(){
		  // SyntaxHighlighter.all();
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
		<?php
		$arrFile = [
				'script.js',
				'bookApp.js'
		];

		// require route
		foreach (glob('themes/default/js/route/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// require config
		foreach (glob('themes/default/js/config/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// // require service
		foreach (glob('themes/default/js/service/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// // require filter
		foreach (glob('themes/default/js/filter/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// // require directive
		foreach (glob('themes/default/js/directive/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// require controller
		foreach (glob('themes/default/js/controller/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}

		// require controller
		foreach (glob('themes/default/js/controller/*/*.js') as $file) {
				$arrFile[] = str_replace('themes/default/js/', '', $file);
		}
		echo \Asset::js($arrFile);
?>
