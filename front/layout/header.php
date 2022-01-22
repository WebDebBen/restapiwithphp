<?php
    include "config.php" ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Rest API </title>
	<link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="./assets/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="./assets/css/toastr.css">
	
	<script type="text/javascript" src="./assets/js/jquery-2.2.4.js"></script>
	<script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="./assets/js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="./assets/js/Sortable.js"></script>
</head>
<body>
    <input type="hidden" id="base_url" value="<?php echo BASE_URL; ?>"/>
    <input type="hidden" id="base_api_url" value="<?php echo BASE_API_URL; ?>"/>
    
	<div class="header">
		<div class="container">
			<div class="row desktop-menu mt-1r">
				<div class="col-md-12 menu-wrap">
					<ul class="nav nav-pills pull-left">
						<li class="logo_li">
							<h3>Rest API</h3>
						</li>
					</ul>
					<ul class="nav pull-right">
						<li class="active">
							<a href="/front">Dashboard</a>
						</li>
						<li class="active ml-1r">
							<a href="/front/datatable.php">Generator</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="main">