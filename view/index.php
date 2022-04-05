<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="<?php echo __URL__; ?>css/main.css" />
	<link rel="stylesheet" href="<?php echo __URL__; ?>css/style.css" />
	<link rel="stylesheet" href="<?php echo __URL__; ?>css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo __URL__; ?>css/bootstrap.min.css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <link href="src/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
	<link rel="icon" type="image/png" href="images/getty_icon.png">
	<style>
		body {
			background:#F1F1F1;
		}
		
		.lg-form {
			width:340px;
			background:#fff;
			border:1px solid #eee;
			margin: 75px auto 0 auto;
		}
		
		.lg-logo {
			background: rgba(108,138,83,1);
			background: -moz-linear-gradient(top, rgba(108,138,83,1) 0%, rgba(58,92,27,1) 100%);
			background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(108,138,83,1)), color-stop(100%, rgba(58,92,27,1)));
			background: -webkit-linear-gradient(top, rgba(108,138,83,1) 0%, rgba(58,92,27,1) 100%);
			background: -o-linear-gradient(top, rgba(108,138,83,1) 0%, rgba(58,92,27,1) 100%);
			background: -ms-linear-gradient(top, rgba(108,138,83,1) 0%, rgba(58,92,27,1) 100%);
			background: linear-gradient(to bottom, rgba(108,138,83,1) 0%, rgba(58,92,27,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6c8a53', endColorstr='#3a5c1b', GradientType=0 );

		}
	</style>
</head>
<body>
	<div class="panel panel-primary lg-form">
	  <!-- Default panel contents -->
	  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  <div class="panel-heading lg-logo" style="color:#eee;"><img src="http://172.16.0.93/images/vtap_logo.png" height=20 width=70/></div>
	  <div class="panel-body">
		<div id="msg-alert">
			<p class="bg-danger"><center><strong><span class="glyphicon glyphicon-remove"></span> Page Error</strong><hr /> There is either an error in your request or the page was not found on the server.</center></p>
			<a class="btn btn-default btn-sm pull-right" href="/" role="button"><span class="glyphicon glyphicon-hand-left"></span> &nbsp;Back to Login</a>
		</div>	
	  </div>
	</div>
	</div>
	</form>
</body>
</html>	
