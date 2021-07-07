<?php
	include("./system/config.php");
	include("./system/functions.php");
	
	session_start();
?>
	<html>
		<head>
			<title><?php echo $title; echo $seperator; echo $description; ?></title>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" href="./style/main.css?v=<?php echo $cssversion ?>">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
			<link rel="icon" href="../favicon.ico" />
		</head>
				
		<body style="background: url('../images/wall.png') center fixed; background-size: cover;">
			<nav class="navbar navbar-inverse">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span> 
						</button>
						<a class="navbar-brand" href="index"><?php echo $title ?></a>
					</div>
					<div class="collapse navbar-collapse" id="myNavbar">
						<ul class="nav navbar-nav">
							<li <?php if ($page == "index") { echo 'class="active"'; } ?> ><a href="index">Home</a></li>
							<?php if (!empty($_SESSION['uid'])) { ?> <!-- If the user is logged in -->
								<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Stronghold<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li <?php if ($page == "kingdom") { echo 'class="active"'; } ?>><a href="kingdom?id=<?php include("./system/config.php"); echo $user['id'] ?>">Kingdom</a></li>
										<li <?php if ($page == "buildings") { echo 'class="active"'; } ?>><a href="buildings">Buildings</a></li>
										<li <?php if ($page == "workers") { echo 'class="active"'; } ?>><a href="workers">Citizens</a></li>
										<?php if ($user['level'] >= 3) { ?><li <?php if ($page == "units") { echo 'class="active"'; } ?>><a href="units">Military</a></li><?php } ?>
										<?php if ($user['level'] >= 5) { ?><li <?php if ($page == "research") { echo 'class="active"'; } ?>><a href="research">Research</a></li><?php } ?>
										<?php if ($user['level'] >= 10) { ?><li <?php if ($page == "siegeweapons") { echo 'class="active"'; } ?>><a href="siegeweapons">Siege Equipment</a></li><?php } ?>
										<?php if ($user['level'] >= 10) { ?><li <?php if ($page == "defenseweapons") { echo 'class="active"'; } ?>><a href="defenseweapons">Defense Equipment</a></li><?php } ?>
										<li <?php if ($page == "inbox") { echo 'class="active"'; } ?>><a href="inbox">Inbox</a></li>
										<li <?php if ($page == "account") { echo 'class="active"'; } ?>><a href="account">Account</a></li>
										<!--<li><a href="#">Page 1-2</a></li>
										<li><a href="#">Page 1-3</a></li>-->
									</ul>
								</li>
								<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">World<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li <?php if ($page == "rankings") { echo 'class="active"'; } ?>><a href="rankings">Rankings</a></li>
										<li <?php if ($page == "marketplace") { echo 'class="active"'; } ?>><a href="marketplace">Marketplace</a></li>
										<!--<li><a href="#">Page 1-3</a></li>-->
									</ul>
								</li>
								<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Alliance<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<?php if ($user['allianceid'] != 0) { ?>
											<li <?php if ($page == "alliance") { echo 'class="active"'; } ?>><a href="alliance?id=<?php echo $user['allianceid'] ?>"><?php echo $alliance['name'] ?></a></li>
											<?php if ($alliance['president'] == $user['username']) { ?><li <?php if ($page == "alliancecontrol") { echo 'class="active"'; } ?>><a href="alliancecontrol">Alliance Control Panel</a></li><?php } ?>
										<?php } else { ?>
											<li <?php if ($page == "createalliance") { echo 'class="active"'; } ?>><a href="createalliance">Create an Alliance</a></li>
										<?php } ?>
										<li <?php if ($page == "alliancelist") { echo 'class="active"'; } ?>><a href="alliancelist">Alliances</a></li>
										<!--<li><a href="#">Page 1-3</a></li>-->
									</ul>
								</li>
								<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Community<span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a href="./forums/forum">Forums</a></li>
										<li <?php if ($page == "tutorial") { echo 'class="active"'; } ?>><a href="tutorial">Tutorial</a></li>
										<li <?php if ($page == "changelog") { echo 'class="active"'; } ?>><a href="changelog">Changelog</a></li>
										<li><a href="https://discord.gg/jU53gAJ">Discord Server</a></li>
										<!--<li><a href="#">Page 1-3</a></li>-->
									</ul>
								</li>
								</ul>
								<ul class="nav navbar-nav navbar-right">
									<li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
								</ul>
							<?php } else { ?> <!-- If the user is not logged in -->
								</ul>
								<ul class="nav navbar-nav navbar-right">
									<li <?php if ($page == "register") { echo 'class="active"'; } ?> ><a href="register"><span class="glyphicon glyphicon-user"></span> Register</a></li>
									<li <?php if ($page == "login") { echo 'class="active"'; } ?> ><a href="login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
								</ul>
							<?php } ?>
					</div>
				</div>
			</nav>
			
			<?php
			if (!empty($_SESSION['uid']) && $page != "index") {
				include("./system/config.php");
				?>
					<div class="navbar">
						<img src="./art/gold.png" height="20" width="20"><i><?php echo number_format($stats['gold']);?></i>
						<img src="./art/iron.png" height="20" width="20"><i><?php echo number_format($stats['iron']);?></i>
						<img src="./art/stone.png" height="20" width="20"><i><?php echo number_format($stats['stone']);?></i>
						<img src="./art/lumber.png" height="20" width="20"><i><?php echo number_format($stats['lumber']);?></i>
						<img src="./art/wheat.png" height="20" width="20"><i><?php echo number_format($stats['wheat']);?></i>
						<img src="./art/flour.png" height="20" width="20"><i><?php echo number_format($stats['flour']);?></i>
						<img src="./art/apple.png" height="20" width="20"><i><?php echo number_format($stats['apples']);?></i>
						<img src="./art/bread.png" height="20" width="20"><i><?php echo number_format($stats['bread']);?></i>
						<img src="./art/cheese.png" height="20" width="20"><i><?php echo number_format($stats['cheese']);?></i>
						<img src="./art/meat.png" height="20" width="20"><i><?php echo number_format($stats['meat']);?></i>
						<img src="./art/land.png" height="20" width="20"><i><?php echo number_format($user['land']);?></i>
						<img src="./art/sword.png" height="20" width="20"><i><?php echo number_format($weapon['sword']);?></i>
						<img src="./art/pike.png" height="20" width="20"><i><?php echo number_format($weapon['pike']);?></i>
						<img src="./art/armor.png" height="20" width="20"><i><?php echo number_format($weapon['armor']);?></i>
						<img src="./art/crossbow.png" height="20" width="20"><i><?php echo number_format($weapon['crossbow']);?></i>
						<img src="./art/longbow.png" height="20" width="20"><i><?php echo number_format($weapon['longbow']);?></i>
						<img src="./art/shield.png" height="20" width="20"><i><?php echo number_format($weapon['shield']);?></i>
					</div><br />
				<?php
			}
			?>
			
			<div class="container">
				<div class="row">		
					<div class="col-sm-12 column">
						<br /><br />
		
		<?php
		
			if (!empty($_SESSION['uid'])) {
				$get_notification = mysqli_query($mysqli, "SELECT * FROM notification") or die(mysqli_error($mysqli));
				while($notification = mysqli_fetch_assoc($get_notification)) {
					if ($user['id'] == $notification['userid']) {
						if ($notification['page'] == $page) {
							?>
						
								<div class="notification"><?php echo $notification['message']; ?></div><br />
						
							<?php
						}
						$delete1 = mysqli_query($mysqli, "DELETE FROM notification WHERE id='".$notification['id']."'") or die(mysqli_error($mysqli));
					}
				}
			}
		
		?>
		