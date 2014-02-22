<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>RocReport</title>

    <!-- Bootstrap core CSS -->
    <link href="/rocreport/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="/rocreport/css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- Customized  CSS -->
    <link href="/rocreport/css/common.css" rel="stylesheet">

    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

    <style>
		#map-canvas {
		    border: solid 1px #000000;
		    min-height:600px;
		}
		#navbar {
			position: fixed; bottom: 0; left: 0; z-index: 1000; background: black; width: 25%;
			color: white;
			padding-left: 10px;
			padding-bottom: 5px;
			border-radius: 0 5px 0 0;
		}
		#navbar a {
			color: white;
			text-decoration: underline;
		}
    </style>

</head>

<body>

<div id="navbar">
	<?php if ($user_status == 1) { ?> 
		Hi, <?=$_COOKIE['dmtabs_loggedin_username']?>						
		&nbsp;|&nbsp;
		<a href="/rocreport">Home</a>			
		&nbsp;|&nbsp;
		<a style="font-size: 18px;" href="/rocreport/addupdate"><b>+ Report</b></a>			
		&nbsp;|&nbsp;		
		<a href="/rocreport/account?action=logout">Logout</a>					
		&nbsp;|&nbsp;
		<a href="/rocreport/about"><img style="width: 16px;" src="/rocreport/img/android-logo.png" /></a>								
	<?php } else { ?> 
		<a href="/rocreport/account">Login</a>
		&nbsp;|&nbsp;
		<a href="/rocreport/account">Register</a>	
		&nbsp;|&nbsp;
		<a href="/rocreport">Home</a>	
		&nbsp;|&nbsp;
		<a href="/rocreport/about"><img style="width: 16px;" src="/rocreport/img/android-logo.png" /></a>						
	<?php } ?>
	<br />	
	<em>
	<span style="font-size: 12px;">
	<a href="/rocreport/about">More about this project</a>
	</span>
	</em>
</div>			