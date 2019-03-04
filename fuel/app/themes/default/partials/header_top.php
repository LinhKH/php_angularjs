
 <header id="header">
    <!--header-->
    <div class="header_top">
      <!--header_top-->
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="contactinfo">
              <ul class="nav nav-pills">
                <li><a href="#"><i class="fa fa-phone"></i> 0123456789</a></li>
                <li><a href="#"><i class="fa fa-envelope"></i> admin@admin.com</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="btn-group pull-right">
              <div class="btn-group locale">
                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown"><img src="<?php echo \Uri::base(false); ?>documents/website/language/flag_uk.png"
                    style="height: 25px;">
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo \Uri::base(false); ?>locale/en"><img src="<?php echo \Uri::base(false); ?>documents/website/language/flag_uk.png" style="height: 25px;"></a></li>
                  <li><a href="<?php echo \Uri::base(false); ?>locale/vi"><img src="<?php echo \Uri::base(false); ?>documents/website/language/flag_vn.png" style="height: 25px;"></a></li>
                </ul>
              </div>
              <div class="btn-group locale">
                <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                  USD Dola
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo \Uri::base(false); ?>currency/USD">USD Dola</a></li>
                  <li><a href="<?php echo \Uri::base(false); ?>currency/VND">VietNam Dong</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/header_top-->

    <div class="header-middle">
      <!--header-middle-->
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <div class="logo pull-left">
              <a href="https://demo.s-cart.org"><img style="width: 150px;" src="<?php echo \Uri::base(false); ?>documents/website/images/scart-mid.png"
                  alt="" /></a>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="shop-menu pull-right">
              <ul class="nav navbar-nav">
                <li><a href="<?php echo \Uri::base(false); ?>member/profile.html"><i class="fa fa-user"></i> Account</a></li>
                <li><a href="<?php echo \Uri::base(false); ?>wishlist.html"><span style="border-radius: 3px;padding: 5px;" class="label_top label-warning shopping-wishlist"
                      id="count_wishlist">0</span><i class="fa fa-star"></i> Wishlist</a></li>
                <li><a href="<?php echo \Uri::base(false); ?>compare.html"><span style="border-radius: 3px;padding: 5px;" class="label_top label-warning shopping-compare"
                      id="count_compare">0</span><i class="fa fa-crosshairs"></i> Compare</a></li>
                <li><a href="<?php echo \Uri::base(false); ?>cart.html"><span style="border-radius: 3px;padding: 5px;" class="label_top label-warning shopping-cart"
                      id="count_cart">5</span><i class="fa fa-shopping-cart"></i> Shopping cart</a></li>
                <li><a href="<?php echo \Uri::base(false); ?>member/login.html"><i class="fa fa-lock"></i> Login</a></li>

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/header-middle-->

    <div class="header-bottom">
      <!--header-bottom-->
      <div class="container">
        <div class="row">
          <div class="col-sm-9">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
            <div class="mainmenu pull-left">
              <ul class="nav navbar-nav collapse navbar-collapse">
                <li><a href="{{app.baseUrl}}" ng-class="{'active': app.module === 'Scart' && app.controller === 'HomePageCtr'}">Home</a></li>
                <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                  <ul role="menu" class="sub-menu">
                    <li><a href="{{app.baseUrl}}products" ng-class="{'active': app.module === 'Scart' && app.controller === 'ProductPageCtr'}">All products</a></li>
                    <li><a href="<?php echo \Uri::base(false); ?>compare.html">Compare</a></li>
                    <li><a href="<?php echo \Uri::base(false); ?>cart.html">Shopping cart</a></li>
                    <li><a href="<?php echo \Uri::base(false); ?>member/login.html">Login</a></li>
                  </ul>
                </li>
                <li><a href="{{app.baseUrl}}news" ng-class="{'active': app.module === 'Scart' && app.controller === 'NewsPageCtr'}">Blog</a></li>


                <li><a href="{{app.baseUrl}}about" ng-class="{'active': app.module === 'Scart' && app.controller === 'AboutPageCtr'}">About us</a></li>
                <li><a href="{{app.baseUrl}}contact" ng-class="{'active': app.module === 'Scart' && app.controller === 'ContactPageCtr'}">Contact us</a></li>
              </ul>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="search_box pull-right">
              <form id="searchbox" method="get" action="<?php echo \Uri::base(false); ?>search.html">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Your keyword..." name="keyword">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/header-bottom-->
  </header>