<!DOCTYPE HTML>
<head>
<title>Store Website</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Bookstore">
<meta name="author" content="Bookstore">
<meta name="keywords" content="Bookstore">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php echo $partials['head_tag']; ?>

</head>
<body>
  <?php //echo $partials['header'] ?>
	<?php echo $partials['header_top'] ?>
	<?php echo $partials['menu'] ?>
  <?php echo $partials['header_bottom'] ?>
	

	<!-- Main content -->
	<div class="main">
			<div class="content">
				<div class="content_top">
					<div class="heading">
					<h3>Feature Products</h3>
					</div>
					<div class="clear"></div>
				</div>
					<div class="section group">
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/feature-pic1.png" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						<p><span class="price">$505.22</span></p>
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/feature-pic2.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						<p><span class="price">$620.87</span></p>   
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/feature-pic3.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						<p><span class="price">$220.97</span></p> 
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<img src="<?php echo \Uri::base(false); ?>assets/images/feature-pic4.png" alt="" />
						<h2>Lorem Ipsum is simply </h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit</p>
						<p><span class="price">$415.54</span></p>  
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
				</div>
				<div class="content_bottom">
					<div class="heading">
					<h3>New Products</h3>
					</div>
					<div class="clear"></div>
				</div>
				<div class="section group">
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/new-pic1.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p><span class="price">$403.66</span></p>
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/new-pic2.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p><span class="price">$621.75</span></p> 
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
						<a href="preview.html"><img src="<?php echo \Uri::base(false); ?>assets/images/feature-pic2.jpg" alt="" /></a>
						<h2>Lorem Ipsum is simply </h2>
						<p><span class="price">$428.02</span></p>
							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
					<div class="grid_1_of_4 images_1_of_4">
					<img src="<?php echo \Uri::base(false); ?>assets/images/new-pic3.jpg" alt="" />
						<h2>Lorem Ipsum is simply </h2>					 
						<p><span class="price">$457.88</span></p>

							<div class="button"><span><a href="preview.html" class="details">Details</a></span></div>
					</div>
				</div>
			</div>
	</div>
	<!-- End Main content -->

  <?php echo $partials['footer'] ?>
	<?php echo $partials['scripts'] ?>
</body>
</html>
