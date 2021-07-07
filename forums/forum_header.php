<HTML>
	
	<?php
		session_start();
		include("../system/config.php");
		include("../system/functions.php");
	?>
	
	<head>
		<title><?php echo $title ?> Forums</title>
		<link href="style.css" rel="stylesheet" type="text/css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="../index"><?php echo $title ?></a>
					</div>
					<ul class="nav navbar-nav">
						<li <?php if ($page == "forum") { echo 'class="active"'; } ?> ><a href="forum">Home</a></li>
						<li <?php if ($page == "forum_list") { echo 'class="active"'; } ?> ><a href="forum_list">Thread List</a></li>
						<li <?php if ($page == "create_thread") { echo 'class="active"'; } ?> ><a href="create_thread">Create a Thread</a></li>
					</ul>
				</div>
		</nav>
		
		
		<!--<div class="navbar">
			<a href="../index.php">Warzone 1503</a>
			<a href="forum.php">Homepage</a>
			<a href="forum_list.php">Thread List</a>
			<a href="create_thread.php">Create a Thread</a>
		</div><br />-->
		
		<div class="container">