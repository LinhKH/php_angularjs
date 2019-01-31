<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
    <?php
      echo \Asset::css(['stylelogin.css']);
      echo \Asset::js([
        'admin/plugins/jquery-3.3.1.min.js',
        'admin/common.js',
      ]);
    ?>
</head>
<body>
<div class="container">
	<section id="content">
		
    <?php echo $content; ?>

		<div class="button">
			<a href="#">Training with live project</a>
		</div>
	</section>
</div>

<script type="text/javascript">
    var baseURL = '<?php echo \Uri::base(false); ?>';
</script>
</body>
</html>