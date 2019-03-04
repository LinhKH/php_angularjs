
<!DOCTYPE html>
<html lang="en" ng-app="bookApp" ng-controller="bookAppController">

<head>
  <base href="<?php echo \Uri::base(false); ?>" />
  <title>{{app.title}}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Free website shopping cart for business">
  <meta name="keyword" content="">
  <meta property="fb:app_id" content="" />
  <title>{{app.title}}</title>
  <meta property="og:image" content="./public/images/org.jpg" />
  <meta property="og:url" content="https://demo.s-cart.org" />
  <meta property="og:type" content="Website" />
  <meta property="og:description" content="Free website shopping cart for business" />

  <?php echo $partials['head_tag']; ?>

  <link rel="shortcut icon" href="<?php echo \Uri::base(false); ?>images/ico/favicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo \Uri::base(false); ?>assets/images/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo \Uri::base(false); ?>assets/images/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo \Uri::base(false); ?>assets/images/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo \Uri::base(false); ?>assets/images/ico/apple-touch-icon-57-precomposed.png">
  <style type="text/css">
    .new-price {
      color: #FE980F;
      font-size: 20px;
      padding: 10px;
      font-weight: bold;
    }

    .old-price {
      text-decoration: line-through;
      color: #a95d5d;
      font-size: 17px;
      padding: 10px;
    }

    .locale .dropdown-menu {
      min-width: auto !important;
    }

    .locale button {
      min-height: 30px !important;
    }
  </style>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-128658138-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'UA-128658138-1');
  </script>
</head>
<!--/head-->

<body>
  <div id="fb-root"></div>
  <script>(function (d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = '//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=934208239994473';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
  </script>
  
  <!--/header-->
  <?php echo $partials['header_top'] ?>

  <section id="slider" ng-if="app.controller == 'HomePageCtr'">
    <!--slider-->
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div id="slider-carousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
              <li data-target="#slider-carousel" data-slide-to="1" class=""></li>
              <li data-target="#slider-carousel" data-slide-to="2" class=""></li>
            </ol>
            <div class="carousel-inner">
              <div class="item active">
                <div class="col-sm-6">
                  <h1>S-CART</h1>
                  <h2>Free E-Commerce Template</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                    labore et dolore magna aliqua. </p>
                  <button type="button" class="btn btn-default get">Get it now</button>
                </div>
                <div class="col-sm-6">
                  <img src="{{app.baseUrl}}documents/website/banner/36e662803f744d4f9df2cecc2e17b87b.jpg" class="girl img-responsive"
                    alt="" />
                </div>
              </div>
              <div class="item ">
                <div class="col-sm-6">
                  <h1>S-CART</h1>
                  <h2>Free E-Commerce Template</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                    labore et dolore magna aliqua. </p>
                  <button type="button" class="btn btn-default get">Get it now</button>
                </div>
                <div class="col-sm-6">
                  <img src="{{app.baseUrl}}documents/website/banner/7b16dd5b838439ddbe58c3ea506f5db1.jpg" class="girl img-responsive"
                    alt="" />
                </div>
              </div>
              <div class="item ">
                <div class="col-sm-6">
                  <h1>S-CART</h1>
                  <h2>Free E-Commerce Template</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                    labore et dolore magna aliqua. </p>
                  <button type="button" class="btn btn-default get">Get it now</button>
                </div>
                <div class="col-sm-6">
                  <img src="{{app.baseUrl}}documents/website/banner/6122cfae7fdb5fff1a4e7406dcab10ef.jpg" class="girl img-responsive"
                    alt="" />
                </div>
              </div>
            </div>
            <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
              <i class="fa fa-angle-left"></i>
            </a>
            <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
              <i class="fa fa-angle-right"></i>
            </a>
          </div>

        </div>
      </div>
    </div>
  </section>
  <!--/slider-->

  <section>
    <div class="container">
      <div class="row">
        <div class="breadcrumbs" ng-if="app.controller != 'HomePageCtr'">
          <ol class="breadcrumb">
            <li><a href="{{app.baseUrl}}">Home</a></li>
            <li class="active">{{ app.breadcrumbs }}</li>
          </ol>
        </div>        
        
        <div ng-view=""></div>
      </div>
    </div>
  </section>

  <?php echo $partials['footer'] ?> 
  <!--/Footer-->
  <?php echo $partials['scripts'] ?>
  
</body>

</html>
