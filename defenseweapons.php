<?php

	$page = "defenseweapons";
	include("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		$engineerworkshop_space = $buildings['engineerworkshop'] - ($unit['moat'] + $unit['murderhole'] + $unit['battlement'] + $unit['machicolation']);
		$stone_income = round(($worker['mine'] * 3)/2);
		
		$defense_weapons = $unit['moat'] + $unit['murderhole'] + $unit['battlement'] + $unit['machicolation'];
		$stone_cost = 0.002*($defense_weapons) + pow($user['level'], 5) + ($stone_income/2);
		
		if(!empty($_POST['train'])) {
			$moat =  protect($_POST['moat']);
			$murderhole =  protect($_POST['murderhole']);
			$battlement =  protect($_POST['battlement']);
			$machicolation =  protect($_POST['machicolation']);
			
			$stone_needed = ($moat * $stone_cost) + ($murderhole * $stone_cost) + ($battlement * $stone_cost) + ($machicolation * $stone_cost);
			
			if ($moat < 0 || $murderhole < 0 || $battlement < 0 || $machicolation < 0) {
				output("You must train a positive number of Defense Equipment!");
			} elseif ($stats['stone'] < $stone_needed) {
				output("You do not have enough stone!");
			} elseif (($buildings['siegecamp']) - ($unit['moat'] + $unit['murderhole'] + $unit['battlement'] + $unit['machicolation']) < $moat + $murderhole + $battlement + $machicolation) { output("You need more Engineer Workshops!");
			} else {
				$unit['moat'] += $moat;
				$unit['murderhole'] += $murderhole;
				$unit['battlement'] += $battlement;
				$unit['machicolation'] += $machicolation;
					
				$update_units = mysqli_query($mysqli, "UPDATE unit SET
														moat='".$unit['moat']."',
														murderhole='".$unit['murderhole']."',
														battlement='".$unit['battlement']."',
														machicolation='".$unit['machicolation']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					
				$stats['stone'] -= $stone_needed;
				$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														stone='".$stats['stone']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
														
				
				output("You have built your Defense Equipment!");
			}
			
		}
		?>
			<b>Your Siege Equipment</b>
			<br /><hr />
			Engineer Workshop space remaining : <i><?php echo number_format($engineerworkshop_space)?></i><br /><br />
			<form action="defenseweapons" method="post">
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th></th>
					<th><b>Defense Equipment</b></th>
					<th><b>Number of Defense Equipment</b></th>
					<th><b>Defense Equipment Cost</b></th>
					<th><b>Train More</b></th>
					<th><b>Defense Equipment Requirement / Details</b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><img id="building" src="./art/moat.png"></td>
					<td>Moat</td>
					<td><?php echo number_format($unit['moat']); ?></td>
					<td><?php echo number_format($stone_cost);?> <img id="shop" src="./art/stone.png"></td>
					<td><input type="text" name="moat" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/engineerworkshop.png">. Increases the enemy's causalities by 1% each (max 10%).<td>
				</tr>
				<tr>
					<td><img id="building" src="./art/murderhole.png"></td>
					<td>Murder Hole</td>
					<td><?php echo number_format($unit['murderhole']); ?></td>
					<td><?php echo number_format($stone_cost);?> <img id="shop" src="./art/stone.png"></td>
					<td><input type="text" name="murderhole" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/engineerworkshop.png">. Increases the efficiency of your units by 1% each (max 20%).<td>
				</tr>
				<tr>
					<td><img id="building" src="./art/battlment.png"></td>
					<td>Battlement</td>
					<td><?php echo number_format($unit['battlement']); ?></td>
					<td><?php echo number_format($stone_cost);?> <img id="shop" src="./art/stone.png"></td>
					<td><input type="text" name="battlement" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/engineerworkshop.png">. Increases the efficiency of walls by 2% each (max 50%).<td>
				</tr>
				<tr>
					<td><img id="building" src="./art/machicolation.png"></td>
					<td>Machicolation</td>
					<td><?php echo number_format($unit['machicolation']); ?></td>
					<td><?php echo number_format($stone_cost);?> <img id="shop" src="./art/stone.png"></td>
					<td><input type="text" name="machicolation" autocomplete="off"/></td>
					<td>Requires a <img id="building" src="./art/engineerworkshop.png">. Reduces the chance of a successful espionage by 2% each (max 40%).<td>
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