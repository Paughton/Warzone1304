<?php

	$page = "kingdom";
	include("./template/header.php");

	if (!empty($_SESSION['uid'])) {
		
		$lumber_income = $worker['lumbermill'] * 4;
		$stone_income = $worker['mine'] * 3;
		$gold_income = $worker['mine'];
		$iron_income = $worker['mine'] * 2;
		
		if ($user['level'] > 1) {
			$lumber_price = 0.002*($user['land']) + pow($user['level'], 5) + ($lumber_income*10);
			$gold_price = 0.002*($user['land']) + pow($user['level'], 5) + ($gold_income*10);
			$stone_price = 0.002*($user['land']) + pow($user['level'], 5) + ($stone_income*10);
			$iron_price = 0.002*($user['land']) + pow($user['level'], 5) + ($iron_income*10);
			$land_requirement = (10000 * $user['level']) + (1000 * ($user['level']+1)) + pow($user['level'], ceil($user['level']/2));
		} else {
			$gold_price = 50;
			$iron_price = 25;
			$lumber_price = 200;
			$stone_price = 100;
			$land_requirement = 0;
		}
		
		if (!empty($_POST['upgrade_stronghold'])) {
			if ($stats['gold'] < $gold_price) {
				output("You do not have enough Gold!");
			} else if ($stats['iron'] < $iron_price) {
				output("You do not have enough Iron!");
			} else if ($stats['lumber'] < $lumber_price) {
				output("You do not have enough Lumber!");
			} else if ($stats['stone'] < $stone_price) {
				output("You do not have enough Stone!");
			} else if ($user['land'] < $land_requirement) {
				output("You do not have enough Land!");
			} else {
				$stats['gold'] -= $gold_price;
				$stats['iron'] -= $iron_price;
				$stats['lumber'] -= $lumber_price;
				$stats['stone'] -= $stone_price;
				$user['level'] += 1;
				
				$update_stats = mysqli_query($mysqli, "UPDATE stats SET 
															  gold='".$stats['gold']."',
															  iron='".$stats['iron']."',
															  lumber='".$stats['lumber']."',
															  stone='".$stats['stone']."'
															  WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
																	
				$update_user = mysqli_query($mysqli, "UPDATE user SET level='".$user['level']."' WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
				output("Stronghold Upgraded!");
				
				
			}
		}
		
		if(!empty($_GET['id'])) {
			$id = protect($_GET['id']);
			
			$unit_power = ($unit['footman'] * 0.5) + ($unit['archer'] * 0.5) + ($unit['knight'] * 0.5) + ($unit['rifleman'] * 0.5) + ($unit['pikeman'] * 0.5) + ($unit['general'] * 0.5);
			$building_power = ($buildings['applefarm'] * 0.5) + ($buildings['lumbermill'] * 0.5) + ($buildings['mine'] * 0.5) + ($buildings['hunterpost'] * 0.5) + ($buildings['barrack'] * 0.5) + ($buildings['stable'] * 0.5) + ($buildings['shootingrange'] * 0.5);
			$building_power += ($buildings['dairyfarm'] * 0.5) + ($buildings['wheatfarm'] * 0.5) + ($buildings['mill'] * 0.5) + ($buildings['bakery'] * 0.5) + ($buildings['marketplace'] * 0.5) + ($buildings['wall']) + ($buildings['engineerworkshop']*0.5) + ($buildings['siegecamp']*0.5);
			$land_power = ($user['land'] * 0.0005);
			$stronghold_power = pow($user['level'], 3);
			
			?>
			
			<img src="./art/lumbermill.png" style="display: none;" id="lumbermill">
			<img src="./art/mine.png" style="display: none;" id="mine">
			<img src="./art/hunterpost.png" style="display: none;" id="hunterpost">
			<img src="./art/barrack.png" style="display: none;" id="barrack">
			<img src="./art/stable.png" style="display: none;" id="stable">
			<img src="./art/shootingrange.png" style="display: none;" id="shootingrange">
			<img src="./art/bowyerworkshop.png" style="display: none;" id="bowyerworkshop">
			<img src="./art/weaponsmithery.png" style="display: none;" id="weaponsmithery">
			<img src="./art/armory.png" style="display: none;" id="armory">
			<img src="./art/house.png" style="display: none;" id="house">
			<img src="./art/applefarm.png" style="display: none;" id="applefarm">
			<img src="./art/dairyfarm.png" style="display: none;" id="dairyfarm">
			<img src="./art/wheatfarm.png" style="display: none;" id="wheatfarm">
			<img src="./art/mill.png" style="display: none;" id="mill">
			<img src="./art/bakery.png" style="display: none;" id="bakery">
			<img src="./art/marketplace.png" style="display: none;" id="marketplace">
			<img src="./art/wall.png" style="display: none;" id="wall">
			<img src="./art/siegecamp.png" style="display: none;" id="siegecamp">
			<img src="./art/engineerworkshop.png" style="display: none;" id="engineerworkshop">
			
			<?php
			
			if ($id == $user['id']) { // If this is the user logged in
				?>
				
					<div class="jumbotron">
						<div class="row">
							<div class="col-sm-6">
								<img class="flag" src="./flag/<?php echo $user['flag'] ?>" onclick="location.href='changeflag';">
							</div>
							<div class="col-sm-6">
								<h1><?php echo $user['username']?></h1>
								<p><?php if ($user['elite']) { ?>Elite Stronghold<?php } ?>
								<p><?php if ($user['allianceid'] != 0) { ?><a href="alliance?id=<?php echo $user['allianceid']?>"><?php echo $alliance['name']; } ?></a></p>
							</div>
						</div>
					</div>
					
					<!-- Description of Kingdom -->
					
					<?php echo $user['username'] ?> is ruled by, <?php echo $user['leader'] ?>. The Kingdom of <?php echo $user['username'] ?> is currently on the livid terrain
					of <?php echo $user['terrain'] ?>.<br /><br />
					
					<!-- Description of Kingdom -->
					
					<script>
						document.title = "<?php echo $user['username'] ?>";
					</script>
					
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<tbody>
								<tr>
									<td>Username</td>
									<td><?php echo $user['username'] ?></td>
								</tr>
								<tr>
									<td>E-mail</td>
									<td><?php echo $user['email'] ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/gold.png">Gold</td>
									<td><?php echo number_format($stats['gold']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/iron.png">Iron</td>
									<td><?php echo number_format($stats['iron']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/stone.png">Stone</td>
									<td><?php echo number_format($stats['stone']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/lumber.png">Lumber</td>
									<td><?php echo number_format($stats['lumber']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/wheat.png">Wheat</td>
									<td><?php echo number_format($stats['wheat']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/flour.png">Flour</td>
									<td><?php echo number_format($stats['flour']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/apple.png">Apples</td>
									<td><?php echo number_format($stats['apples']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/bread.png">Bread</td>
									<td><?php echo number_format($stats['bread']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/cheese.png">Cheese</td>
									<td><?php echo number_format($stats['cheese']) ?></td>
								</tr>
								<tr>
									<td><img id="shop" src="./art/meat.png">Meat</td>
									<td><?php echo number_format($stats['meat']) ?></td>
								</tr>
								<tr>
									Cost for Upgrading your stronghold to <?php echo numeral_format($user['level']+1); ?> is <?php echo number_format($gold_price) ?> <img id="shop" src="./art/gold.png"> <?php echo number_format($iron_price) ?> <img id="shop" src="./art/iron.png"> 
									<?php echo number_format($stone_price) ?> <img id="shop" src="./art/stone.png"> <?php echo number_format($lumber_price) ?> <img id="shop" src="./art/lumber.png"> and you need
									to currently have <?php echo number_format($land_requirement) ?> or more <img id="shop" src="./art/land.png">
									<form action="kingdom?id=<?php echo $user['id'] ?>" method="POST">
										<input type="submit" name="upgrade_stronghold" value="Upgrade Stronghold"/>
									</form>
								</tr>
							</tbody>
						</table>
					</div>
				
					<center>
						<div id="piechart"></div>
					</center>
					
					<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
						// Load google charts
						google.charts.load('current', {'packages':['corechart']});
						google.charts.setOnLoadCallback(drawChart);

						// Draw the chart and set the chart values
						function drawChart() {
						  var data = google.visualization.arrayToDataTable([
						  ['Power Soruce', 'Power'],
						  ['Buildings', <?php echo $building_power; ?>],
						  ['Military', <?php echo $unit_power; ?>],
						  ['Land', <?php echo $land_power; ?>],
						  ['Stronghold', <?php echo $stronghold_power; ?>]
						]);

						var options = {
							'title': 'Power Distrubution', 
							'width': 550, 
							'height': 400,
							is3D: true
						};
						  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
						  chart.draw(data, options);
						}
					</script>
				
				<?php
			} else { // If this an other user
			
				$id = protect($_GET['id']);
				$user_check = mysqli_query($mysqli, "SELECT * FROM user WHERE id='".$id."'") or die(mysqli_error($mysqli));
				if (mysqli_num_rows($user_check) == 0) {
					output("There is no user with that ID!");
				} else {
					$s_user = mysqli_fetch_assoc($user_check);
					$stats_stats = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$id."'") or die(mysqli_error($mysqli));
					$s_stats = mysqli_fetch_assoc($stats_stats);
					$buildings_get = mysqli_query($mysqli, "SELECT * FROM buildings WHERE id='".$id."'") or die(mysqli_error($mysqli));
					$s_buildings = mysqli_fetch_assoc($buildings_get);
					$unit_get = mysqli_query($mysqli, "SELECT * FROM unit WHERE id='".$id."'") or die(mysqli_error($mysqli));
					$s_unit = mysqli_fetch_assoc($buildings_get);
					$alliance_get = mysqli_query($mysqli, "SELECT * FROM alliance WHERE id='".$s_user['allianceid']."'") or die(mysqli_error($mysqli));
					$s_alliance = mysqli_fetch_assoc($alliance_get);
					$attacks_check = mysqli_query($mysqli, "SELECT id FROM logs WHERE attacker='".$_SESSION['uid']."' AND defender ='".$id."' AND time>'".(time() - 86400)."'") or die(mysqli_error($mysqli));
					
					$total_land = ($s_buildings['lumbermill'] * 500) + ($s_buildings['mine'] * 500) + ($s_buildings['hunterpost'] * 250) + ($s_buildings['barrack'] * 1000) + ($s_buildings['stable'] * 750) + ($s_buildings['shootingrange'] * 1000);
					$total_land += ($s_buildings['house'] * 250) + ($s_buildings['applefarm'] * 750) + ($s_buildings['dairyfarm'] * 850) + ($s_buildings['wheatfarm'] * 950) + ($s_buildings['mill'] * 500) + ($s_buildings['bakery'] * 250);
					$total_land += ($s_buildings['marketplace'] * 1500) + ($s_buildings['siegecamp'] * 750) + ($s_buildings['engineerworkshop'] * 750) + $s_user['land'];
					$total_land += ($s_buildings['bowyerworkshop'] * 250) + ($s_buildings['armory'] * 250) + ($s_buildings['weaponsmithery'] * 250);
					
					$unit_power = ($s_unit['footman'] * 0.5) + ($s_unit['archer'] * 0.5) + ($s_unit['knight'] * 0.5) + ($s_unit['rifleman'] * 0.5) + ($s_unit['pikeman'] * 0.5) + ($s_unit['general'] * 0.5);
					$building_power = ($s_buildings['applefarm'] * 0.5) + ($s_buildings['lumbermill'] * 0.5) + ($s_buildings['mine'] * 0.5) + ($s_buildings['hunterpost'] * 0.5) + ($s_buildings['barrack'] * 0.5) + ($s_buildings['stable'] * 0.5) + ($s_buildings['shootingrange'] * 0.5);
					$building_power += ($s_buildings['dairyfarm'] * 0.5) + ($s_buildings['wheatfarm'] * 0.5) + ($s_buildings['mill'] * 0.5) + ($s_buildings['bakery'] * 0.5) + ($s_buildings['marketplace'] * 0.5) + ($s_buildings['wall']) + ($s_buildings['engineerworkshop']*0.5) + ($s_buildings['siegecamp']*0.5);
					$land_power = ($s_user['land'] * 0.0005);
					$stronghold_power = pow($s_user['level'], 3);
					
					?>
					
					<script>
						document.title = "<?php echo $s_user['username'] ?>";
					</script>
					
					<div class="jumbotron">
						<div class="row">
							<div class="col-sm-6">
								<img class="flag" src="./flag/<?php echo $s_user['flag'] ?>" >
							</div>
							<div class="col-sm-6">
								<h1><?php echo $s_user['username']?></h1>
								<p><?php if ($s_user['elite']) { ?>Elite Stronghold<?php } ?>
								<p><?php if ($s_user['allianceid'] != 0) { ?><a href="alliance?id=<?php echo $s_user['allianceid']?>"><?php echo $s_alliance['name']; } ?></a></p>
							</div>
						</div>
					</div>
					
					<!-- Description of Kingdom --><p style="background: lightgray;">
					
					<?php echo $s_user['username'] ?> is ruled by, <?php echo $s_user['leader'] ?>. The Kingdom of <?php echo $s_user['username'] ?> is currently on the livid terrain
					of <?php echo $s_user['terrain'] ?>.
					
					<!-- Description of Kingdom --></p><br />
					
					<?php include("./javascript/kingdomjs.php"); ?>
		
					<br />
					<span id="land_amount" style="display: none"><?php echo $total_land; ?></span>
		
					<div id="canvas_content">
					</div><br /><br />
					
					<form action="battle" method="post">
						<i>Attacks on <?php echo $s_user['username']; ?> in the last 24 hours: (<?php echo "<b>" . mysqli_num_rows($attacks_check) . "</b>"; ?>/<b>5</b>)</i><br />
						<?php
							if (mysqli_num_rows($attacks_check) < 5) {
						?>
						<input type="submit" name="attack" value="Attack" />
						<input type="hidden" name="id" value="<?php echo $id; ?>"/>
						<?php
							}
						?>
					</form>
					<form action="espionage" method="post">
						<input type="submit" name="espionage" value="Espionage" />
						<input type="hidden" name="id" value="<?php echo $id; ?>"/>
					</form>
					
					<center>
						<div id="piechart"></div>
					</center>
					
					<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
					<script type="text/javascript">
						// Load google charts
						google.charts.load('current', {'packages':['corechart']});
						google.charts.setOnLoadCallback(drawChart);

						// Draw the chart and set the chart values
						function drawChart() {
						  var data = google.visualization.arrayToDataTable([
						  ['Power Soruce', 'Power'],
						  ['Buildings', <?php echo $building_power; ?>],
						  ['Military', <?php echo $unit_power; ?>],
						  ['Land', <?php echo $land_power; ?>],
						  ['Stronghold', <?php echo $stronghold_power; ?>]
						]);

						var options = {
							'title': 'Power Distrubution', 
							'width': 550, 
							'height': 400,
							is3D: true
						};
						  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
						  chart.draw(data, options);
						}
					</script>
					
					<?php
					
				}
			
			}
			
		} else {
			echo "You have visited this page incorrectly!";
		}
	} else {
		echo "You must be logged in to visit this page!";
	}

	include("./template/footer.php");
	
?>