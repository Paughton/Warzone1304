<?php

	$page = "buildings";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		$lumber_income = $worker['lumbermill'] * 4;
		$stone_income = $worker['mine'] * 3;
		$gold_income = $worker['mine'];
		$land_amount = (($user['level']-1) * 2000);
		
		$total_land = ($buildings['lumbermill'] * 500) + ($buildings['mine'] * 500) + ($buildings['hunterpost'] * 250) + ($buildings['barrack'] * 1000) + ($buildings['stable'] * 750) + ($buildings['shootingrange'] * 1000);
		$total_land += ($buildings['house'] * 250) + ($buildings['applefarm'] * 750) + ($buildings['dairyfarm'] * 850) + ($buildings['wheatfarm'] * 950) + ($buildings['mill'] * 500) + ($buildings['bakery'] * 250);
		$total_land += ($buildings['marketplace'] * 1500) + ($buildings['siegecamp'] * 750) + ($buildings['engineerworkshop'] * 750) + $user['land'];
		$total_land += ($buildings['bowyerworkshop'] * 250) + ($buildings['armory'] * 250) + ($buildings['weaponsmithery'] * 250);
		
		$lumber_cost = 0.002*($user['land']) + pow($user['level'], 5) + ($lumber_income/2);
		$gold_cost = 0.002*($user['land']) + pow($user['level'], 5) + ($gold_income/2);
		$stone_cost = 0.002*($user['land']) + pow($user['level'], 5) + ($stone_income/2);
		
		if ($techtree['techid'] == 19) {
			$mod_house = 2;
		} else {
			$mod_house = 1;
		}
		
		if (!empty($_POST['buyland'])) {
			if ($stats['gold'] <= $gold_cost) {
				output("You do not have enough gold!");
			} elseif ($stats['stone'] <= $stone_cost) {
				output("You do not have enough stone!");
			} elseif ($stats['lumber'] <= $lumber_cost) {
				output("You do not have enough lumber!");
			} else {
				$stats['gold'] -= $gold_cost;
				$stats['stone'] -= $stone_cost;
				$stats['lumber'] -= $lumber_cost;
				$user['land'] += $land_amount;
				
				$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														gold='".$stats['gold']."',
														stone='".$stats['stone']."',
														lumber='".$stats['lumber']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));

				$update_user = mysqli_query($mysqli, "UPDATE user SET land='".$user['land']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				output("You have successfully bought some land!");
			}
		}
		
		if(!empty($_GET['building']) && !empty($_GET['x']) && !empty($_GET['y'])) {
			$building = protect($_GET['building']);
			$x = protect($_GET['x']);
			$y = protect($_GET['y']);
			
			if ($x > $total_land * 0.05)  {
				output("The building must be on the grid!");
			} elseif ($y > $total_land * 0.025) {
				output("The building must be on the grid!");
			} else {
				
				$proceed = true;
				$house = 0;
				
				if ($building == "lumbermill") {
					$lumber_needed = 10;
					$stone_needed = 10;
					$gold_needed = 0;
					$land_needed = 500;
				} elseif ($building == "mine") {
					$lumber_needed = 10;
					$stone_needed = 10;
					$gold_needed = 0;
					$land_needed = 500;
				} elseif ($building == "hunterpost") {
					$lumber_needed = 20;
					$stone_needed = 0;
					$gold_needed = 0;
					$land_needed = 250;
				} elseif ($building == "barrack" && $user['level'] >= 3) {
					$lumber_needed = 40;
					$stone_needed = 70;
					$gold_needed = 0;
					$land_needed = 1000;
				} elseif ($building == "stable" && $user['level'] >= 3) {
					$lumber_needed = 65;
					$stone_needed = 30;
					$gold_needed = 0;
					$land_needed = 750;
				} elseif ($building == "shootingrange" && $user['level'] >= 3) {
					$lumber_needed = 70;
					$stone_needed = 20;
					$gold_needed = 0;
					$land_needed = 1000;
				} elseif ($building == "bowyerworkshop" && $user['level'] >= 3) {
					$lumber_needed = 35;
					$stone_needed = 35;
					$gold_needed = 0;
					$land_needed = 500;
				}  elseif ($building == "weaponsmithery" && $user['level'] >= 3) {
					$lumber_needed = 35;
					$stone_needed = 35;
					$gold_needed = 0;
					$land_needed = 500;
				}  elseif ($building == "armory" && $user['level'] >= 3) {
					$lumber_needed = 35;
					$stone_needed = 35;
					$gold_needed = 0;
					$land_needed = 500;
				}  elseif ($building == "house") {
					$lumber_needed = 30;
					$stone_needed = 10;
					$gold_needed = 0;
					$land_needed = 250;
					$house = 1;
				} elseif ($building == "applefarm" && $user['level'] >= 2) {
					$lumber_needed = 30;
					$stone_needed = 10;
					$gold_needed = 0;
					$land_needed = 750;
				} elseif ($building == "dairyfarm" && $user['level'] >= 4) {
					$lumber_needed = 60;
					$stone_needed = 30;
					$gold_needed = 0;
					$land_needed = 850;
				} elseif ($building == "wheatfarm" && $user['level'] >= 6) {
					$lumber_needed = 70;
					$stone_needed = 40;
					$gold_needed = 0;
					$land_needed = 950;
				} elseif ($building == "mill" && $user['level'] >= 6) {
					$lumber_needed = 30;
					$stone_needed = 50;
					$gold_needed = 0;
					$land_needed = 500;
				} elseif ($building == "bakery" && $user['level'] >= 6) {
					$lumber_needed = 20;
					$stone_needed = 0;
					$gold_needed = 0;
					$land_needed = 250;
				} elseif ($building == "marketplace" && $user['level'] >= 5) {
					$lumber_needed = 10;
					$stone_needed = 10;
					$gold_needed = 0;
					$land_needed = 500;
				} elseif ($building == "wall" && $user['level'] >= 8) {
					$lumber_needed = 80;
					$stone_needed = 100;
					$gold_needed = 200;
					$land_needed = 0;
				} elseif ($building == "siegecamp" && $user['level'] >= 10) {
					$lumber_needed = $lumber_cost;
					$stone_needed = $stone_cost;
					$gold_needed = 0;
					$land_needed = 750;
				} elseif ($building == "engineerworkshop" && $user['level'] >= 10) {
					$lumber_needed = $lumber_cost;
					$stone_needed = $stone_cost;
					$gold_needed = 0;
					$land_needed = 750;
				} else {
					output("Invalid Building <b>OR</b> you do not have a high enough level!");
					$proceed = false;
				}
				
				if($proceed) {
					if ($lumber_needed > $stats['lumber']) {
						output("You do not have enough lumber!");
					} elseif ($stone_needed > $stats['stone']) {
						output("You do not have enough stone!");
					} elseif ($gold_needed > $stats['gold']) {
						output("You do not have enough gold!");
					} elseif ($land_needed > $user['land']) {
						output("You do not have enough land!");
					} else {
						
						if($building == "lumbermill"){$buildings['lumbermill']+=1;}
						if($building == "mine"){$buildings['mine']+=1;}
						if($building == "hunterpost"){$buildings['hunterpost']+=1;}
						if($building == "barrack"){$buildings['barrack']+=1;}
						if($building == "stable"){$buildings['stable']+=1;}
						if($building == "shootingrange"){$buildings['shootingrange']+=1;}
						if($building == "bowyerworkshop"){$buildings['bowyerworkshop']+=1;}
						if($building == "weaponsmithery"){$buildings['weaponsmithery']+=1;}
						if($building == "armory"){$buildings['armory']+=1;}
						if($building == "house"){$buildings['house']+=1;}
						if($building == "applefarm"){$buildings['applefarm']+=1;}
						if($building == "dairyfarm"){$buildings['dairyfarm']+=1;}
						if($building == "wheatfarm"){$buildings['wheatfarm']+=1;}
						if($building == "mill"){$buildings['mill']+=1;}
						if($building == "bakery"){$buildings['bakery']+=1;}
						if($building == "marketplace"){$buildings['marketplace']+=1;}
						if($building == "wall"){$buildings['wall']+=1;}
						if($building == "siegecamp"){$buildings['siegecamp']+=1;}
						if($building == "engineerworkshop"){$buildings['engineerworkshop']+=1;}
						
						$unit['worker'] += ($house * 8)*$mod_house;
						$update_units = mysqli_query($mysqli, "UPDATE unit SET worker='".$unit['worker']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
						
						$update_buildings = mysqli_query($mysqli, "UPDATE buildings SET
														lumbermill='".$buildings['lumbermill']."',
														mine='".$buildings['mine']."',
														hunterpost='".$buildings['hunterpost']."',
														barrack='".$buildings['barrack']."',
														stable='".$buildings['stable']."',
														shootingrange='".$buildings['shootingrange']."',
														house='".$buildings['house']."',
														applefarm='".$buildings['applefarm']."',
														dairyfarm='".$buildings['dairyfarm']."',
														wheatfarm='".$buildings['wheatfarm']."',
														mill='".$buildings['mill']."',
														bakery='".$buildings['bakery']."',
														marketplace='".$buildings['marketplace']."',
														wall='".$buildings['wall']."',
														siegecamp='".$buildings['siegecamp']."',
														engineerworkshop='".$buildings['engineerworkshop']."',
														bowyerworkshop='".$buildings['bowyerworkshop']."',
														weaponsmithery='".$buildings['weaponsmithery']."',
														armory='".$buildings['armory']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
														
						$ins1 = mysqli_query($mysqli, "INSERT INTO playermap (userid, x, y, building) VALUES ('".$user['id']."', '".$x."', '".$y."', '".$building."')") or output(mysqli_error($mysqli));
														
						$stats['lumber'] -= $lumber_needed;
						$stats['stone'] -= $stone_needed;
						$stats['gold'] -= $gold_needed;
						$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														gold='".$stats['gold']."',
														stone='".$stats['stone']."',
														lumber='".$stats['lumber']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				
						$user['land'] -= $land_needed;
						$update_user = mysqli_query($mysqli, "UPDATE user SET land='".$user['land']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
						
					}
				}
				
			}
			
		}
		
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
		
		<?php include("./javascript/buildingsjs.php"); ?>
		
		<br />
		<span id="land_amount" style="display: none"><?php echo $total_land; ?></span>
		
		<div id="canvas_content">
		</div>
		
		<br />
		
		<!-- Fake Form -->
		<select name="building" id="building_value"><br />
			<option value="none">None Selected</option>
			<option value="lumbermill">Lumbermill</option>
			<option value="mine">Mine</option>
			<option value="hunterpost">Hunter's Post</option>
			<option value="barrack">Barracks</option>
			<option value="stable">Stables</option>
			<option value="shootingrange">Archery Range</option>
			<option value="bowyerworkshop">Bowyer's Workshop</option>
			<option value="weaponsmithery">Weapon Smithery</option>
			<option value="armory">Armory</option>
			<option value="house">House</option>
			<option value="applefarm">Apple Farm</option>
			<option value="dairyfarm">Dairy Farm</option>
			<option value="wheatfarm">Wheat Farm</option>
			<option value="mill">Mill</option>
			<option value="bakery">Bakery</option>
			<option value="marketplace">Marketplace</option>
			<option value="wall">Wall</option>
			<option value="siegecamp">Siege Camp</option>
			<option value="engineerworkshop">Engineer Workshop</option>
		</select>
		<button onclick="select_building();">Select</button><br />
		<?php if ($user['level'] >= 2) { ?>
			<form action="buildings" method="post">
				Land
				<input type="submit" name="buyland" value="Buy"/>
			</form><br /><br />
		<?php } else { echo "<br /><br />"; } ?>
		<!-- Fake Form -->
		
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<th></th>
				<th>Building</th>
				<th>Amount</th>
				<th>Cost</th>
				<th>Description</th>
			</thead>
			<tbody>
				<?php
						if ($user['level'] >= 2) {
					?>
							<tr>
							<td><img id="building" src="./art/land.png"></td>
							<td>Land</td>
							<td><?php echo number_format($user['land']); ?> sq ft.</td>
							<td><?php echo number_format($lumber_cost) ?> <img id="shop" src="./art/lumber.png">, <?php echo number_format($stone_cost) ?> <img id="shop" src="./art/stone.png"> and, <?php echo number_format($gold_cost) ?> <img id="shop" src="./art/gold.png"></td>
							<td>Allows buildings to be placed. Each bulk of land consists of <?php echo number_format($land_amount) ?> square feet.</td>
							</tr>
					<?php 
						}
					?>
				<tr>
					<td><img id="building" src="./art/lumbermill.png"></td>
					<td>Lumber Mill</td>
					<td><?php echo number_format($buildings['lumbermill']); ?></td>
					<td>10 <img id="shop" src="./art/lumber.png"> 10 <img id="shop" src="./art/stone.png"> and 500 <img id="shop" src="./art/land.png"></td>
					<td>Harvests <img id="shop" src="./art/lumber.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per lumber mill.</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/mine.png"></td>
					<td>Mine</td>
					<td><?php echo number_format($buildings['mine']); ?></td>
					<td>10 <img id="shop" src="./art/lumber.png"> 10 <img id="shop" src="./art/stone.png"> and 500 <img id="shop" src="./art/land.png"></td>
					<td>Harvests <img id="shop" src="./art/stone.png">, <img id="shop" src="./art/iron.png">, and <img id="shop" src="./art/gold.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per mine.</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/hunterpost.png"></td>
					<td>Hunter's Post</td>
					<td><?php echo number_format($buildings['hunterpost']); ?></td>
					<td>20 <img id="shop" src="./art/lumber.png"> and 250 <img id="shop" src="./art/land.png"></td>
					<td>Harvests <img id="shop" src="./art/meat.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per hunter's post.</td>
				</tr>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/barrack.png"></td>
						<td>Barracks</td>
						<td><?php echo number_format($buildings['barrack']); ?></td>
						<td>40 <img id="shop" src="./art/lumber.png"> 70 <img id="shop" src="./art/stone.png"> and 1,000 <img id="shop" src="./art/land.png"></td>
						<td>Allows production of <img id="shop" src="./art/footman.png"> and <img id="shop" src="./art/knight.png">. Quarters 50 <img id="shop" src="./art/footman.png"> or <img id="shop" src="./art/knight.png">.</td>
						</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/stable.png"></td>
						<td>Stables</td>
						<td><?php echo number_format($buildings['stable']); ?></td>
						<td>65 <img id="shop" src="./art/lumber.png"> 30 <img id="shop" src="./art/stone.png"> and 750 <img id="shop" src="./art/land.png"></td>
						<td>Allows breeding of <img id="shop" src="./art/horse.png">, and houses 15 <img id="shop" src="./art/horse.png">.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/shootingrange.png"></td>
						<td>Archery Range</td>
						<td><?php echo number_format($buildings['shootingrange']); ?></td>
						<td>70 <img id="shop" src="./art/lumber.png"> 20 <img id="shop" src="./art/stone.png"> and 1,000 <img id="shop" src="./art/land.png"></td>
						<td>Allows production of <img id="shop" src="./art/archer.png"> and <img id="shop" src="./art/rifleman.png">. Quarters 50 <img id="shop" src="./art/archer.png"> or <img id="shop" src="./art/rifleman.png">.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/bowyerworkshop.png"></td>
						<td>Bowyer's Workshop</td>
						<td><?php echo number_format($buildings['bowyerworkshop']); ?></td>
						<td>35 <img id="shop" src="./art/lumber.png"> 35 <img id="shop" src="./art/stone.png"> and 250 <img id="shop" src="./art/land.png"></td>
						<td>Creates <img id="shop" src="./art/crossbow.png"> and <img id="shop" src="./art/longbow.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per Boywer's Workshop.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/weaponsmithery.png"></td>
						<td>Weaponsmithery</td>
						<td><?php echo number_format($buildings['weaponsmithery']); ?></td>
						<td>35 <img id="shop" src="./art/lumber.png"> 35 <img id="shop" src="./art/stone.png"> and 500 <img id="shop" src="./art/land.png"></td>
						<td>Creates <img id="shop" src="./art/sword.png">, <img id="shop" src="./art/pike.png">, and <img id="shop" src="./art/shield.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per Weaponsmithery.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 3) {
					?>
						<tr>
						<td><img id="building" src="./art/armory.png"></td>
						<td>Armory</td>
						<td><?php echo number_format($buildings['armory']); ?></td>
						<td>35 <img id="shop" src="./art/lumber.png"> 35 <img id="shop" src="./art/stone.png"> and 500 <img id="shop" src="./art/land.png"></td>
						<td>Creates <img id="shop" src="./art/armor.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per armory.</td>
					</tr>
					<?php
						}
					?>
				<tr>
					<td><img id="building" src="./art/house.png"></td>
					<td>House</td>
					<td><?php echo number_format($buildings['house']); ?></td>
					<td>30 <img id="shop" src="./art/lumber.png"> 10 <img id="shop" src="./art/stone.png"> and 250 <img id="shop" src="./art/land.png"></td>
					<td>Adds to your population, each house adds <?php echo 8 * $mod_house; ?> <img id="shop" src="./art/citizen.png"> to your population.</td>
				</tr>
				<?php
						if ($user['level'] >= 2) {
					?>
						<tr>
						<td><img id="building" src="./art/applefarm.png"></td>
						<td>Apple Farm</td>
						<td><?php echo number_format($buildings['applefarm']); ?></td>
						<td>30 <img id="shop" src="./art/lumber.png"> 10 <img id="shop" src="./art/stone.png"> and 750 <img id="shop" src="./art/land.png"></td>
						<td>Harvets <img id="shop" src="./art/apple.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per apple farm.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 4) {
					?>
						<tr>
						<td><img id="building" src="./art/dairyfarm.png"></td>
						<td>Dairy Farm</td>
						<td><?php echo number_format($buildings['dairyfarm']); ?></td>
						<td>60 <img id="shop" src="./art/lumber.png"> 30 <img id="shop" src="./art/stone.png"> and 850 <img id="shop" src="./art/land.png"></td>
						<td>Makes <img id="shop" src="./art/cheese.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per dairy farm.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 6) {
					?>
						<tr>
						<td><img id="building" src="./art/wheatfarm.png"></td>
						<td>Wheat Farm</td>
						<td><?php echo number_format($buildings['wheatfarm']); ?></td>
						<td>70 <img id="shop" src="./art/lumber.png"> 40 <img id="shop" src="./art/stone.png"> and 950 <img id="shop" src="./art/land.png"></td>
						<td>Harvests <img id="shop" src="./art/wheat.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per wheat farm.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 6) {
					?>
						<tr>
						<td><img id="building" src="./art/mill.png"></td>
						<td>Mill</td>
						<td><?php echo number_format($buildings['mill']); ?></td>
						<td>30 <img id="shop" src="./art/lumber.png"> 50 <img id="shop" src="./art/stone.png"> and 500 <img id="shop" src="./art/land.png"></td>
						<td>Grinds <img id="shop" src="./art/wheat.png"> into <img id="shop" src="./art/flour.png"> depending on the amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per mill.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 6) {
					?>
						<tr>
						<td><img id="building" src="./art/bakery.png"></td>
						<td>Bakery</td>
						<td><?php echo number_format($buildings['bakery']); ?></td>
						<td>20 <img id="shop" src="./art/lumber.png"> and 250 <img id="shop" src="./art/land.png"></td>
						<td>Bakes <img id="shop" src="./art/bread.png"> using <img id="shop" src="./art/flour.png">, efficiency depends on amount of <img id="shop" src="./art/citizen.png">. There is a max of 10 <img id="shop" src="./art/citizen.png"> per bakery.</td>
					</tr>
					<?php
						}
					?>
					<?php
						if ($user['level'] >= 5) {
					?>
						<tr>
						<td><img id="building" src="./art/marketplace.png"></td>
						<td>Marketplace</td>
						<td><?php echo number_format($buildings['marketplace']); ?></td>
						<td>80 <img id="shop" src="./art/lumber.png"> 100 <img id="shop" src="./art/stone.png"> 200 <img id="shop" src="./art/gold.png"> and 1,500 <img id="shop" src="./art/land.png"></td>
						<td>Allows you to put items up on the marketplace. Each marketplace will allow you to put up 1 item each at one time.</td>
						</tr>
					<?php
						}
					?>
					
					<?php
						if ($user['level'] >= 8) {
					?>
						<tr>
						<td><img id="building" src="./art/wall.png"></td>
						<td>Wall</td>
						<td><?php echo number_format($buildings['wall']); ?></td>
						<td>512 <img id="shop" src="./art/stone.png"></td>
						<td>Every wall increases your defense by 1.</td>
						</tr>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 10) {
					?>
						<tr>
						<td><img id="building" src="./art/siegecamp.png"></td>
						<td>Siege Camp</td>
						<td><?php echo number_format($buildings['siegecamp']); ?></td>
						<td><?php echo number_format($lumber_cost); ?> <img id="shop" src="./art/lumber.png"> <?php echo number_format($stone_cost); ?> <img id="shop" src="./art/stone.png"> and 750 <img id="shop" src="./art/land.png"></td>
						<td>Allows you to build 1 Siege Equipment.</td>
					</tr>
					<?php
						}
					?>
				<?php
						if ($user['level'] >= 10) {
					?>
						<tr>
						<td><img id="building" src="./art/engineerworkshop.png"></td>
						<td>Engineer Workshop</td>
						<td><?php echo number_format($buildings['engineerworkshop']); ?></td>
						<td><?php echo number_format($lumber_cost); ?> <img id="shop" src="./art/lumber.png"> <?php echo number_format($stone_cost); ?> <img id="shop" src="./art/stone.png"> and 750 <img id="shop" src="./art/land.png"></td>
						<td>Allows you to build 1 Defense Equipment.</td>
					</tr>
					<?php
						}
					?>
			</tbody>
		</table>
		</div><br />
		
		<?php
		
	}
	
	include("./template/footer.php");
	
?>