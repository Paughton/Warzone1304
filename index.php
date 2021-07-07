<?php

	$page = "index";
	include("./template/header.php");
	
	$user_count = 0;
	$user_login = 0;
	$get_users = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
	while($user = mysqli_fetch_assoc($get_users)) {
		if ($user['account'] == 0 && $user['active'] == 1) {
			$user_count += 1;
			if ($user['lastlogin'] == 1) {
				$user_login += 1;
			}
		}
	}
	
		?>
	
		<center>
			<!-- Header -->
			<div class="logo">Warzone 1304 Beta</div>
		</center>
		<h1><?php echo $title ?></h1>
		<p><?php echo $title ?> is a browser based free to play multi-player game where you can create your own stronghold to rule. You get to decide what happens in your stronghold and 
		interact with other players through trading and war. You can play with anyone, friend or foe, on <?php echo $title ?> you can make new friends or foes. In <?php echo $title ?> you can do anything your heart 
		desires, you can be prosperous through war, trade or diplomacy. You can create treaties for the benefit of doubt. Here you are the commander.</p>
		<button id="big-button" onclick="location.href='register';">Start Playing Today</button><hr />
	
		<p>Currently <?php echo $user_count ?> users are registered into the game!</p>
		<p>Currently <?php echo $user_login ?> users have logged into the game today!</p><hr />
	
		<b>Images from <?php echo $title ?></b>
		<div id="slideshow">
			<div><img src="./art/bakery.png"></div>
			<div><img src="./art/barrack.png"></div>
			<div><img src="./art/house.png"></div>
			<div><img src="./art/hunterpost.png"></div>
			<div><img src="./art/lumbermill.png"></div>
			<div><img src="./art/marketplace.png"></div>
			<div><img src="./art/mill.png"></div>
			<div><img src="./art/mine.png"></div>
			<div><img src="./art/shootingrange.png"></div>
			<div><img src="./art/stable.png"></div>
		</div><br />
	
		<?php
	
	include("./template/footer.php");
	
?>