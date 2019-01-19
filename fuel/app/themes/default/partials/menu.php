<div class="menu">
	<ul id="dc_mega-menu-orange" class="dc_mm-orange">
	  <li ng-style="{'background-color': (app.module === 'Bookstore' && app.controller === 'HomePageCtr' ? '#602D8D' :'')}">
			<a href="<?php echo Uri::base(true); ?>">Home</a>
		</li>
	  <li ng-style="{'background-color': (app.module === 'Bookstore' && app.controller === 'ProductPageCtr' ? '#602D8D' :'')}">
			<a href="<?php echo Uri::base(true).'product'?>">Products</a> 
		</li>
	  <li ng-style="{'background-color': (app.module === 'Bookstore' && app.controller === 'BrandPageCtr' ? '#602D8D' :'')}">
			<a href="<?php echo Uri::base(true).'topbrands'?>">Top Brands</a>
		</li>
	  <li ng-style="{'background-color': (app.module === 'Bookstore' && app.controller === 'CartPageCtr' ? '#602D8D' :'')}">
			<a href="<?php echo Uri::base(true).'cart'?>">Cart</a>
		</li>
	  <li ng-style="{'background-color': (app.module === 'Bookstore' && app.controller === 'ContactPageCtr' ? '#602D8D' :'')}">
			<a href="<?php echo Uri::base(true).'contact'?>">Contact</a> 
		</li>
	  <div class="clear"></div>
	</ul>
</div>
