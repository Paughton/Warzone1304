<?php
	$page = "units";
	include("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		$cut = 1;
		if($techtree['path'] == 6) {
			$barrack_mod = 2;
			$cut = 2;
		} else {
			$barrack_mod = 1;
		}
		
		if($techtree['path'] == 12) {
			$stable_mod = 2;
			$cut = 2;
		} else {
			$stable_mod = 1;
		}
		
		$barracks_space = $buildings['barrack'] * 50 * $barrack_mod - ($unit['footman'] + $unit['knight'] + $unit['pikeman'] + $unit['general']);
		$stables_space = $buildings['stable'] * 15 * $stable_mod - ($unit['horse']);
		$shootingrange_space =  $buildings['shootingrange'] * 50 * $stable_mod - ($unit['archer'] + $unit['rifleman']);
		
		if(!empty($_POST['train'])) {
			$footman =  protect($_POST['footman']);
			$archer =  protect($_POST['archer']);
			$knight =  protect($_POST['knight']);
			$rifleman =  protect($_POST['rifleman']);
			$horse =  protect($_POST['horse']);
			$pikeman = protect($_POST['pikeman']);
			$general = protect($_POST['general']);
			
			$armor_needed    = $footman + $knight + $rifleman + $pikeman;
			$sword_needed    = $footman;
			$pike_needed     = $pikeman;
			$shield_needed   = $knight;
			$longbow_needed  = $archer;
			$crossbow_needed = $rifleman;
			
			$iron_needed = (($footman * 24) + ($knight * 48) + ($rifleman * 24) + ($pikeman * 12) + ($general * 64)) / $cut;
			$gold_needed = (($footman * 12) + ($archer * 12) + ($knight * 24) + ($rifleman * 48) + ($horse * 12) + ($pikeman * 24) + ($general * 64)) / $cut;
			$lumber_needed = (($archer * 24) + ($rifleman * 24)) / $cut;
			$bread_needed = (($horse * 60)) / $cut;
			
			if ($footman < 0 || $archer < 0 || $knight < 0 || $rifleman < 0 || $horse < 0 || $pikeman < 0 || $general < 0) {
				output("You must train a positive number of units!");
			} elseif ($stats['bread'] < $bread_needed) {
				output("You do not have enough bread!");
			} elseif ($stats['iron'] < $iron_needed) {
				output("You do not have enough iron!");
			} elseif ($stats['gold'] < $gold_needed) {
				output("You do not have enough gold!");
			} elseif ($stats['lumber'] < $lumber_needed) {
				output("You do not have enough lumber!");
			} elseif (($buildings['barrack'] * 50) * $barrack_mod - ($unit['footman'] + $unit['knight'] + $unit['pikeman']) < $footman + $knight + $pikeman) { output("You need more barracks space or more barracks!");
			} elseif (($buildings['shootingrange'] * 50) * $stable_mod - ($unit['archer'] + $unit['rifleman']) < $archer + $rifleman) { output("You need more shooting range space or more shooting ranges!");
			} elseif (($buildings['stable'] * 15) * $stable_mod - ($unit['horse']) < $horse) { output("You need more stable space or more stables!");
			} elseif ($weapon['armor'] < $armor_needed) {
				output("You need more armor!");
			} elseif ($weapon['sword'] < $sword_needed) {
				output("You need more swords!");
			}  elseif ($weapon['pike'] < $pike_needed) {
				output("You need more pikes!");
			}  elseif ($weapon['shield'] < $shield_needed) {
				output("You need more shields!");
			}  elseif ($weapon['longbow'] < $longbow_needed) {
				output("You need more longbows!");
			}  elseif ($weapon['crossbow'] < $crossbow_needed) {
				output("You need more crossbows!");
			}  else {
				if ($unit['worker'] >= $footman + $archer + $rifleman + $pikeman + $general) {
					$unit['footman'] += $footman;
					$unit['archer'] += $archer;
					$unit['rifleman'] += $rifleman;
					$unit['horse'] += $horse;
					$unit['pikeman'] += $pikeman;
					$unit['general'] += $general;
					
					$weapon['armor'] -= $armor_needed;
					$weapon['sword'] -= $sword_needed;
					$weapon['pike'] -= $pike_needed;
					$weapon['longbow'] -= $longbow_needed;
					$weapon['crossbow'] -= $crossbow_needed;
					
					if ($unit['footman'] >= $knight && $unit['horse'] >= $knight) {
						if($knight > 0) {
							$unit['knight'] += $knight;
							$unit['footman'] -= $knight;
							$unit['horse'] -= $knight;
							$weapon['shield'] -= $knight;
						}
					} else {
						output("You don't have enough footman or horses to train that many knights");
					}
					
					$unit['worker'] -= $footman + $archer + $rifleman + $pikeman + $general;
					$update_units = mysqli_query($mysqli, "UPDATE unit SET
															worker='".$unit['worker']."',
															footman='".$unit['footman']."',
															archer='".$unit['archer']."',
															knight='".$unit['knight']."',
															rifleman='".$unit['rifleman']."',
															horse='".$unit['horse']."',
															pikeman='".$unit['pikeman']."',
															general='".$unit['general']."'
															WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					
					$stats['bread'] -= $bread_needed;
					$stats['iron'] -= $iron_needed;
					$stats['gold'] -= $gold_needed;
					$stats['lumber'] -= $lumber_needed;
					$update_stats = mysqli_query($mysqli, "UPDATE stats SET
															bread='".$stats['bread']."',
															gold='".$stats['gold']."',
															iron='".$stats['iron']."',
															lumber='".$stats['lumber']."'
															WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
															
					
					$update_stats = mysqli_query($mysqli, "UPDATE weapon SET
													sword='".$weapon['sword']."',
													pike='".$weapon['pike']."',
													shield='".$weapon['shield']."',
													crossbow='".$weapon['crossbow']."',
													longbow='".$weapon['longbow']."',
													armor='".$weapon['armor']."'
													WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
					
					output("You have trained your units!");
					
				} else {
					output("You do not have enough citizens!");
				}
			}
			
		}
		?>
			<b>Your Units</b>
			<br /><hr />
			<img id="shop" src="./art/citizen.png">Citizens: <i><?php echo number_format($unit['worker'])?></i><br /><br />
			Barracks space remaining : <i><?php echo number_format($barracks_space)?></i><br />
			Shooting Range space remaining: <i><?php echo number_format($shootingrange_space)?></i><br />
			Stables space remaining: <i><?php echo number_format($stables_space)?></i><br /><br />
			<form action="units" method="post">
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<tr>
				<thead>
					<th></th>
					<th><b>Unit Type</b></th>
					<th><b>Number of Unit</b></th>
					<th><b>Unit Cost</b></th>
					<th><b>Train More</b></th>
					<th><b>Unit Requirement</b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><img id="building" src="./art/footman.png"></td>
					<td>Footman</td>
					<td><?php echo number_format($unit['footman']); ?></td>
					<td><?php echo 24/$cut ?> <img id="shop" src="./art/iron.png"> and <?php echo 12/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="footman" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/barrack.png">, <img id="building" src="./art/citizen.png">, <img id="building" src="./art/sword.png">, and a <img id="building" src="./art/armor.png"></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/pikeman.png"></td>
					<td>Pikeman</td>
					<td><?php echo number_format($unit['pikeman']); ?></td>
					<td><?php echo 12/$cut ?> <img id="shop" src="./art/iron.png"> and <?php echo 24/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="pikeman" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/barrack.png">, <img id="building" src="./art/citizen.png">, <img id="building" src="./art/pike.png">, and a <img id="building" src="./art/armor.png"></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/archer.png"></td>
					<td>Archers</td>
					<td><?php echo number_format($unit['archer']); ?></td>
					<td><?php echo 24/$cut ?> <img id="shop" src="./art/lumber.png"> and <?php echo 12/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="archer" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/shootingrange.png">, <img id="building" src="./art/citizen.png">, <img id="building" src="./art/longbow.png"></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/knight.png"></td>
					<td>Knights</td>
					<td><?php echo number_format($unit['knight']); ?></td>
					<td><?php echo 48/$cut ?> <img id="shop" src="./art/iron.png"> and <?php echo 24/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="knight" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/barrack.png">, <img id="building" src="./art/stable.png">, a <img id="building" src="./art/footman.png">, <img id="building" src="./art/horse.png">, <img id="building" src="./art/shield.png">, and a <img id="building" src="./art/armor.png"></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/rifleman.png"></td>
					<td>Crossbowmen</td>
					<td><?php echo number_format($unit['rifleman']); ?></td>
					<td><?php echo 24/$cut ?> <img id="shop" src="./art/lumber.png">, <?php echo 24/$cut ?> <img id="shop" src="./art/iron.png"> and <?php echo 48/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="rifleman" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/shootingrange.png">, <img id="building" src="./art/citizen.png">, <img id="building" src="./art/crossbow.png">, and a <img id="building" src="./art/armor.png"></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/general.png"></td>
					<td>General</td>
					<td><?php echo number_format($unit['general']); ?></td>
					<td><?php echo 64/$cut ?> <img id="shop" src="./art/iron.png"> and <?php echo 64/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="general" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/barrack.png">. Commands <b>10,000</b> units at one time, this unit is required to be able to attack, not defend.</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/horse.png"></td>
					<td>Horses</td>
					<td><?php echo number_format($unit['horse']); ?></td>
					<td><?php echo 60/$cut ?> <img id="shop" src="./art/bread.png"> and <?php echo 12/$cut ?> <img id="shop" src="./art/gold.png"></td>
					<td><input type="text" name="horse" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/stable.png"></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input type="submit" name="train" value="Train"/></td>
					<td></td>
				</tr>
				</tbody>
			</table></div>
			</form>
			<hr />
		<?php
	}
	
	include("./template/footer.php");
?>