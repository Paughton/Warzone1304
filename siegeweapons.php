<?php

	$page = "siegeweapons";
	include ("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		$siegecamp_space = $buildings['siegecamp'] - ($unit['trebuchet'] + $unit['catapult'] + $unit['batteringram'] + $unit['siegetower']);
		$lumber_income = round(($worker['lumbermill'] * 4)/2);
		
		$siege_weapons = $unit['trebuchet'] + $unit['catapult'] + $unit['batteringram'] + $unit['siegetower'];
		$lumber_cost = 0.002*($siege_weapons) + pow($user['level'], 5) + ($lumber_income/2);
		
		if(!empty($_POST['train'])) {
			$trebuchet =  protect($_POST['trebuchet']);
			$catapult =  protect($_POST['catapult']);
			$batteringram =  protect($_POST['batteringram']);
			$siegetower =  protect($_POST['siegetower']);
			
			$lumber_needed = ($trebuchet * $lumber_cost) + ($catapult * $lumber_cost) + ($batteringram * $lumber_cost) + ($siegetower * $lumber_cost);
			
			if ($trebuchet < 0 || $catapult < 0 || $batteringram < 0 || $siegetower < 0) {
				output("You must train a positive number of Siege Equipment!");
			} elseif ($stats['lumber'] < $lumber_needed) {
				output("You do not have enough lumber!");
			} elseif (($buildings['siegecamp']) - ($unit['trebuchet'] + $unit['catapult'] + $unit['batteringram'] + $unit['siegetower']) < $trebuchet + $catapult + $batteringram + $siegetower) { output("You need more Siege Camps!");
			} else {
				$unit['trebuchet'] += $trebuchet;
				$unit['catapult'] += $catapult;
				$unit['batteringram'] += $batteringram;
				$unit['siegetower'] += $siegetower;
					
				$update_units = mysqli_query($mysqli, "UPDATE unit SET
														trebuchet='".$unit['trebuchet']."',
														catapult='".$unit['catapult']."',
														batteringram='".$unit['batteringram']."',
														siegetower='".$unit['siegetower']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					
				$stats['lumber'] -= $lumber_needed;
				$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														lumber='".$stats['lumber']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
														
				
				output("You have built your Siege Equipment!");
			}
			
		}
		?>
			<b>Your Siege Equipment</b>
			<br /><hr />
			Siege Camp space remaining : <i><?php echo number_format($siegecamp_space)?></i><br /><br />
			<form action="siegeweapons" method="post">
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th></th>
					<th><b>Siege Equipment</b></th>
					<th><b>Number of Siege Equipment</b></th>
					<th><b>Siege Equipment Cost</b></th>
					<th><b>Train More</b></th>
					<th><b>Siege Equipment Requirement / Details</b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><img id="building" src="./art/trebuchet.png"></td>
					<td>Trebuchet</td>
					<td><?php echo number_format($unit['trebuchet']); ?></td>
					<td><?php echo number_format($lumber_cost);?> <img id="shop" src="./art/lumber.png"></td>
					<td><input type="text" name="trebuchet" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/siegecamp.png">. Lowers the defense of your enemy's by 1% each (max of 20%)</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/catapult.png"></td>
					<td>Catapult</td>
					<td><?php echo number_format($unit['catapult']); ?></td>
					<td><?php echo number_format($lumber_cost);?> <img id="shop" src="./art/lumber.png"></td>
					<td><input type="text" name="catapult" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/siegecamp.png">. Increases your attack effect by 1% each (max of 20%)</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/siegetower.png"></td>
					<td>Siege Tower</td>
					<td><?php echo number_format($unit['siegetower']); ?></td>
					<td><?php echo number_format($lumber_cost);?> <img id="shop" src="./art/lumber.png"></td>
					<td><input type="text" name="siegetower" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/siegecamp.png">. Increases the amount gold you can steal by 1% each (max of 20%)</td>
				</tr>
				<tr>
					<td><img id="building" src="./art/batteringram.png"></td>
					<td>Battering Ram</td>
					<td><?php echo number_format($unit['batteringram']); ?></td>
					<td><?php echo number_format($lumber_cost);?> <img id="shop" src="./art/lumber.png"></td>
					<td><input type="text" name="batteringram" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/siegecamp.png">. Increases the amount iron you can steal by 1% each (max of 20%)</td>
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
	
	include ("./template/footer.php");
?>