<!doctype html>
<html>
<head>
	<title>Resturus</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet" />
	<link href="config/default/assets/stylesheets/global/global.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Prata' rel='stylesheet' type='text/css'>
	!{asset://stylesheets}
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	!{asset://javascripts}

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
!{component://Nav}
<div class="clearfix"></div>
<div id="content" class="container-fluid page_!{token://page}">
	!{template://internal}
</div>

<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
</body>
</html>