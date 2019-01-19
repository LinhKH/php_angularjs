<!DOCTYPE html >
<html lang="en" ng-app="bookApp" ng-controller="bookAppController">
<head>
	<base href="<?php echo \Uri::base(false); ?>" />
	<title>{{app.title}}</title>
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
	<div ng-view=""></div>
	<!-- End Main content -->

  <?php echo $partials['footer'] ?>
	<?php echo $partials['scripts'] ?>
</body>

</html>
