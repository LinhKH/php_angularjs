<?php
return array(
  // Frontend
	'_root_'  		                => 'scart/index',
	'products'  		              => 'scart/index',
	'news'  	                    => 'scart/index',
	'contact' 		                => 'scart/index',
	'about' 		                  => 'scart/index',
	// 'cart'  			                => 'scart/index',
	// 'signin'  		                => 'scart/index',
	// 'product/detail/(:any)'       => 'scart/index',
  // 'product-by-category/(:any)'  => 'scart/index',
  
  //Auth
  'logout'  => 'auth/logout',
  'login'   => 'auth/login',
  'auth'    => 'auth/login',

  'management'  		                => 'management/index',

	'_404_'   		=> 'welcome/404',    // The main 404 route
	
);
