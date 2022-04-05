<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php $this->getSiteTitle(); ?> - Error 404</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?php $this->getSiteUrl(); ?>/css/main.css" />
	<link rel="stylesheet" href="<?php $this->getSiteUrl(); ?>/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php $this->getSiteUrl(); ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php $this->getSiteUrl(); ?>/css/dashboard.css" />
	<link rel="stylesheet" href="<?php $this->getSiteUrl(); ?>/css/style.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <link href="../src/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
	<link rel="icon" type="image/png" href="<?php $this->getSiteUrl(); ?>/ajax/get_image.php?i=logo&e=png">
	<style>
		.lg-form {
			width:340px;
			background:#fff;
			border:none;
			margin:75px auto 0 auto;
			-webkit-box-shadow: 5px 4px 10px -5px rgba(0,0,0,0.55);
			-moz-box-shadow: 5px 4px 10px -5px rgba(0,0,0,0.55);
			box-shadow: 5px 4px 10px -5px rgba(0,0,0,0.55);
			border-radius:1px !important;	
			border:1px solid #e1e1e1;
			border-right:none;
		}
		
		.lg-logo {
			background: #fff;
			border-bottom:1px solid #ddd;
			border-radius:1px;
		}
	</style>
</head>
<body>
	<div class="panel panel-primary lg-form">
	  <!-- Default panel contents -->
	  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  <div class="panel-heading lg-logo"><a href="<?php $this->getRoute('login'); ?>" style="text-decoration: none;"><div class="sys-logo"></div></a></div>
	  <div class="panel-body">
		<div id="msg-alert">
			<p class="bg-danger"><center><h4><span class="glyphicon glyphicon-cog"></span> Error 404</h4><hr /> Website is currently on maintenance we'll get you back once done.</center></p>
		</div>	
	  </div>
	</div>
	</div>
	</form>
	<script type="text/javascript" src="<?php $this->getSiteUrl(); ?>/js/custom/helper.js"></script>
	<script type="text/javascript" src="<?php $this->getSiteUrl(); ?>/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php $this->getSiteUrl(); ?>/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php $this->getSiteUrl(); ?>/js/docs.min.js"></script>
</body>
</html>	
