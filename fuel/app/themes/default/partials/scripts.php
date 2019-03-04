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
      'jquery.js',
      'jquery-ui.min.js',
      'bootstrap.min.js',
      'jquery.scrollUp.min.js',
      'jquery.prettyPhoto.js',
      'main.js',      
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
    function formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }
    $('#shipping').change(function () {
      $('#total').html(formatNumber(parseInt(55000) + parseInt($('#shipping').val())));
    });
  </script>

  <script type="text/javascript">
    function addToCart(id, instance = null) {
      if (instance == null || instance == '') {
        var cart = $('.shopping-cart');
      } else {
        var cart = $('.shopping-' + instance);
      }
      var imgtodrag = $('.product-box-' + id).find("img").eq(0);
      if (imgtodrag) {
        var imgclone = imgtodrag.clone()
          .offset({
            top: imgtodrag.offset().top,
            left: imgtodrag.offset().left
          })
          .css({
            'opacity': '0.5',
            'position': 'absolute',
            'width': '150px',
            'z-index': '99999999'
          })
          .appendTo($('body'))
          .animate({
            'top': cart.offset().top,
            'left': cart.offset().left,
            'width': 75,
            'height': 75
          }, 1000, 'easeInOutExpo');
        // setTimeout(function () {
        //     cart.effect("shake", {times: 2}, 200);
        // }, 1500);

        imgclone.animate({
          'width': 0,
          'height': 0
        }, function () {
          $(this).detach()
        });
      }

      $.ajax({
        url: './public/addToCart',
        type: 'POST',
        dataType: 'json',
        data: { id: id, instance: instance, _token: 'Aw5HkJqwyZkgfGVE0Sm93smCUwyYQhxLPgptOdEX' },
        success: function (data) {
          console.log(data);
          error = parseInt(data.error);
          if (error === 0) {
            setTimeout(function () {
              if (data.instance == 'default') {
                $('#count_cart').html(data.count_cart);
                $('.actions').show();
              } else {
                $('#count_' + data.instance).html(data.count_cart);
              }

            }, 1000);
          } else {
            // alert(data.error);
          }

        }
      });

    }
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
