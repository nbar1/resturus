<!doctype html>
<html>
<head>
	<title>Resturus</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	!{asset://stylesheet/global/}
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	!{asset://javascript/global/}

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
<br />
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
</body>
</html>