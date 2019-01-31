<?php
return array(
  // Frontend
	'_root_'  		                => 'bookstore/index',
	'product'  		                => 'bookstore/index',
	'topbrands'  	                => 'bookstore/index',
	'cart'  			                => 'bookstore/index',
	'contact' 		                => 'bookstore/index',
	'signin'  		                => 'bookstore/index',
	'product/detail/(:any)'       => 'bookstore/index',
  'product-by-category/(:any)'  => 'bookstore/index',
  
  //Auth
  'logout'  => 'auth/logout',
  'login'   => 'auth/login',
  'auth'    => 'auth/login',

  'management'  		                => 'management/index',

	'_404_'   		=> 'welcome/404',    // The main 404 route
	
);
