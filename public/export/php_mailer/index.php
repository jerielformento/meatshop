<?php
$othFunc = new oFunction();
$othFunc->setUserPage();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>Getty | Page Error</title>
		
		<link rel="stylesheet" href="/css/gumby.css">
		<link rel="stylesheet" href="/css/gumby-add.css">
		<link rel="icon" type="image/png" href="/img/favicon.png">
	</head>
	<body class="plain-body">
		<div class="container">
			<div class="navbar" id="nav1">
				<div class="row">
					<div class="two columns text-center">
						<a href="<?php echo $log->setPage; ?>"><img src="/img/logo_main.png" class="image-banner" /></a>
					</div>
					<div class="three columns push_two">

					</div>
					<div class="five columns">
						
					</div>
				</div>
			</div>
			<div class="row">
				<div class="push_four four columns panel panel-bottom text-center">
					<h3><i class="icon-cancel-circled"></i><strong>Page Error</strong></h3>
					<div class="panel-content">
						<h3><small>There is either an error in your request or the page was not found on the server.<br />Please click <a href="<?php echo $othFunc->setPage; ?>">HERE</a> to go back to the main page.</small></h3>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="twelve columns text-center">
					<img src="/img/it_team.gif" />
				</div>
			</div>
		</div>
		<script type="text/javascript" src="/js/global-variables.js"></script>
		<script type="text/javascript" src="/js/jquery-1.8.2.js"></script>
		<script type="text/javascript" src="/js/libs/modernizr-2.6.2.min.js"></script>
		<script type="text/javascript" src="/js/helper.js"></script>
		<script type="text/javascript" src="/js/forms.js"></script>
		<script type="text/javascript" src="/js/request.js"></script>
		<script type="text/javascript" src="/js/preloader.js"></script>
		<script type="text/javascript" src="/js/datehelper.js"></script>
		<script type="text/javascript" src="/js/pagination.js"></script>
		<script type="text/javascript" src="/js/url-params.js"></script>
		<?php if($othFunc->setPage == '/login.php') { ?>
		<script type="text/javascript">
			/* (function poll(){
				setTimeout(function(){
					window.location.replace("/login.php");
				}, 5000);
			})(); */
		</script>
		<?php } ?>
	</body>
</html>
