<!doctype html>
<html>
<head>
	<title>Resturus CMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="favicon.ico" />
	!{asset://stylesheet/global/}
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	!{asset://javascript/global/}

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
<div id="wrapper" class="container-fluid">
<div id="content" class="row-fluid">
	!{component://Cms/Navigation/}
	<div class="span9 well">
		<div class="page-header"><h1>Dashboard</h1></div>
		!{component://pageaction/}
	</div>
</div>
</div>
</body>
</html>