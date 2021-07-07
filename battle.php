<?php
	$page = "battle";
	include ("./template/header.php");
	
	if(empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		if(!empty($_POST['attack'])) {
			$id = protect($_POST['id']);
			
			$defender_get = mysqli_query($mysqli, "SELECT * FROM unit WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defenders = mysqli_fetch_assoc($defender_get);
			
			$defender_building_get = mysqli_query($mysqli, "SELECT * FROM buildings WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_building = mysqli_fetch_assoc($defender_building_get);
			
			$defender_ranking_get = mysqli_query($mysqli, "SELECT * FROM ranking WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_ranking = mysqli_fetch_assoc($defender_ranking_get);
			
			$defender_user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_user = mysqli_fetch_assoc($defender_user_get);
			
			$user_check = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$id."'") or die(mysqli_error($mysqli));
			
			$attacking_tof = "true";
			$get_treaties = mysqli_query($mysqli, "SELECT * FROM treaties") or die(mysqli_error($mysqli));
			while($treaty = mysqli_fetch_assoc($get_treaties)) {
				if ($user['username'] == $treaty['user1'] || $user['username'] == $treaty['user2']) {
					if ($defender_user['username'] == $treaty['user1'] || $defender_user['username'] == $treaty['user2']) {
						$attacking_tof = "false";
					}
				}
			}
			
			$attack = "true";
			$stats['apples'] -= ($unit['footman'] * 2) + ($unit['archer'] * 2) + ($unit['knight'] * 2) + ($unit['rifleman'] * 2) + ($unit['pikeman'] * 2);
			if ($stats['apples'] < 0) {
				$counter = $stats['apples'] * -1;
				$stats['apples'] = 0;
				
				$stats['cheese'] -= $counter;
				if ($stats['cheese'] < 0) {
					$counter = $stats['cheese'] * -1;
					$stats['cheese'] = 0;
				
					$stats['meat'] -= $counter;
					if ($stats['meat'] < 0) {
						$counter = $stats['meat'] * -1;
						$stats['meat'] = 0;
						
							$stats['bread'] -= $counter;
							if ($stats['bread'] < 0) {
								$stats['bread'] = 0;
								$attack = "false";
							
							}
					}
				}
			}
			
			if (mysqli_num_rows($user_check) == 0)  {
				output("There is no user with that ID!");
			} elseif (ceil($current_ranking['power'] / 2) > $defender_ranking['power']) {
				output("Your power is too high to battle this player!");
			} elseif ($defender_user['username'] == "AdaVille") {
				output($defender_user['username'] . " is invulnerable, if this is an issue please contact Warzone 1304 CS!");
			} elseif ($defender_user['allianceid'] == $user['allianceid']) {
				output("You are in the same Alliance with that stronghold!");
			} elseif ($defender_user['level'] < 4 || $user['level'] < 4) {
				output("You or this player doesn't have the required Stronghold Level to attack yet (must be level: IV)");
			} elseif ($attack == "false") { 
				output("You need to have more food in order to attack (2 per unit)");
			} elseif (($unit['general']*10000) < $unit['footman'] + $unit['knight'] + $unit['rifleman'] + $unit['archer'] + $unit['pikeman'] + $unit['general']) {
				output("You need more Generals to command that size of an army!");
			} else { // Battle Sequence
				$enemy_stats = mysqli_fetch_assoc($user_check);
				
				$d_pikeman = $defenders['pikeman'];
				$d_rifleman = $defenders['rifleman'];
				$d_footman = $defenders['footman'];
				$d_archer = $defenders['archer'];
				$d_knight = $defenders['knight'];
				
				$pikeman = $unit['pikeman'];
				$rifleman = $unit['rifleman'];
				$footman = $unit['footman'];
				$archer = $unit['archer'];
				$knight = $unit['knight'];
				
				if ($defender_user['terrain'] == "hill") {
					$knight = $knight / 2;
				} else if ($defender_user['terrain'] == "desert") {
					$footman = $footman / 3;
				}
				
				$d_knight -= $unit['pikeman'] * 2;
				$d_footman -= $unit['footman'] * 2;
				$d_pikeman -= $unit['rifleman'] * 2;
				$d_archer -= $unit['footman'] * 2;
				$d_rifleman -= $unit['archer'] * 2;
				
				$knight -= $defenders['pikeman'] * 2;
				$footman -= $defenders['footman'] * 2;
				$pikeman -= $defenders['rifleman'] * 2;
				$archer -= $defenders['footman'] * 2;
				$rifleman -= $defenders['archer'] * 2;
				
				if ($d_knight < 0) {$d_knight = 0;}
				if ($d_footman < 0) {$d_footman = 0;}
				if ($d_pikeman < 0) {$d_pikeman = 0;}
				if ($d_archer < 0) {$d_archer = 0;}
				if ($d_rifleman < 0) {$d_rifleman = 0;}
				
				if ($knight < 0) {$knight = 0;}
				if ($footman < 0) {$footman = 0;}
				if ($pikeman < 0) {$pikeman = 0;}
				if ($archer < 0) {$archer = 0;}
				if ($rifleman < 0) {$rifleman = 0;}
				
				$steal_gold = 0.10;
				$steal_iron = 0.10;
				$defense_modifier = 1;
				$attack_modifier = 1;
				$wall_modifier = 1;
				$causality_modifier = 0.85;
				$causality_modifier2 = 0.15;
				
				if ($unit['siegetower'] >= 20) { $steal_gold = 0.30; } else { $steal_gold += $unit['siegetower'] * 0.01; }
				if ($unit['batteringram'] >= 20) { $steal_iron = 0.30; } else { $steal_iron += $unit['batteringram'] * 0.01; }
				if ($unit['trebuchet'] >= 20) { $defense_modifier = 0.80; } else { $defense_modifier -= $unit['trebuchet'] * 0.01; }
				if ($unit['catapult'] >= 20) { $attack_effect = 1.20; } else { $attack_modifier += $unit['catapult'] * 0.01; }
				
				if ($defenders['battlement'] >= 25) { $wall_modifier = 1.50; } else { $wall_modifier += $defenders['battlement'] * 0.02; }
				if ($defenders['moat'] >= 10) { $causality_modifier = 0.75; } else { $causality_modifier -= $defenders['moat'] * 0.01; }
				if ($defenders['murderhole'] >= 20) { $defense_modifier = 1.20; } else { $defense_modifier += $defenders['murderhole'] * 0.01; }
				
				$defense_effect = (($d_knight * 2) + ($d_footman * 2) + ($d_pikeman * 2) + ($d_archer * 2) + ($d_rifleman * 2) + ($defender_building['wall'] * $wall_modifier)) * $defense_modifier;
				$defense_effect += ($defenders['battlement'] * 35) + ($defenders['moat'] * 35) + ($defenders['murderhole'] * 35);
				
				$attack_effect = (($knight * 2) + ($footman * 2) + ($pikeman * 2) + ($archer * 2) + ($rifleman * 2)) * $attack_modifier;
				$attack_effect += ($unit['siegetower'] * 30) + ($unit['batteringram'] * 30) + ($unit['trebuchet'] * 30) + ($unit['catapult'] * 30);
				
				$unit['siegetower'] = 0;
				$unit['batteringram'] = 0;
				$unit['trebuchet'] = 0;
				$unit['catapult'] = 0;
				
				$defenders['battlement'] = round($defenders['battlement'] * 0.70);
				$defenders['moat'] = round($defenders['moat'] * 0.70);
				$defenders['murderhole'] = round($defenders['murderhole'] * 0.70);
				$defenders['machicolation'] = round($defenders['machicolation'] * 0.70);
				
				$defense_effect = round($defense_effect * rand(1.2, 1.6));
				$attack_effect = round($attack_effect * rand(1.1, 1.5));
				
				if ($attack_effect < $defense_effect) {
					$causality_modifier -= 0.05;
					$causality_modifier2 += 0.05;
				}
				
				if ($defenders['moat'] >= 10) { $causality_modifier2 += 0.10; } else { $causality_modifier2 += $defenders['moat'] * 0.01; }
				$lost_knight = round($unit['knight'] * $causality_modifier2);
				$lost_footman = round($unit['footman'] * $causality_modifier2);
				$lost_pikeman = round($unit['pikeman'] * $causality_modifier2);
				$lost_archer = round($unit['archer'] * $causality_modifier2);
				$lost_rifleman = round($unit['rifleman'] * $causality_modifier2);
				
				$killed_knight = round($defenders['knight'] * 0.15);
				$killed_footman = round($defenders['footman'] * 0.15);
				$killed_pikeman = round($defenders['pikeman'] * 0.15);
				$killed_archer = round($defenders['archer'] * 0.15);
				$killed_rifleman = round($defenders['rifleman'] * 0.15);
				
				if ($attack_effect > $defense_effect) {
					$iron_stolen = round($enemy_stats['iron'] * $steal_iron);
					$gold_stolen = round($enemy_stats['gold'] * $steal_gold);
					
					$unit['knight'] = round($unit['knight'] * $causality_modifier);
					$unit['footman'] = round($unit['footman'] * $causality_modifier);
					$unit['pikeman'] = round($unit['pikeman'] * $causality_modifier);
					$unit['archer'] = round($unit['archer'] * $causality_modifier);
					$unit['rifleman'] = round($unit['rifleman'] * $causality_modifier);
					
					$defenders['knight'] = round($defenders['knight'] * 0.85);
					$defenders['footman'] = round($defenders['footman'] * 0.85);
					$defenders['pikeman'] = round($defenders['pikeman'] * 0.85);
					$defenders['archer'] = round($defenders['archer'] * 0.85);
					$defenders['rifleman'] = round($defenders['rifleman'] * 0.85);
					
					echo "You won the battle! You stole <b>" . number_format($iron_stolen) . " iron</b> and <b>" . number_format($gold_stolen) . " gold!</b> <br />";
					$message = $user['username'] . " has attacked and succeeded. They stole " . $iron_stolen . " iron and " . $gold_stolen . " gold.
					You killed ".$lost_footman." footman, ".$lost_knight." knights, ".$lost_pikeman." pikeman, ".$lost_archer." archers, and ".$lost_rifleman." rifleman.
					You lost ".$killed_footman." footman, ".$killed_knight." knights, ".$killed_pikeman." pikeman, ".$killed_archer." archers, and ".$killed_rifleman." rifleman.";
					$insert1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('Battle Logs', '".$defender_user['username']."', '".$message."')") or die(mysqli_error($mysqli));
				} else {
					
					$unit['knight'] = round($unit['knight'] * $causality_modifier);
					$unit['footman'] = round($unit['footman'] * $causality_modifier);
					$unit['pikeman'] = round($unit['pikeman'] * $causality_modifier);
					$unit['archer'] = round($unit['archer'] * $causality_modifier);
					$unit['rifleman'] = round($unit['rifleman'] * $causality_modifier);
					
					$defenders['knight'] = round($defenders['knight'] * 0.85);
					$defenders['footman'] = round($defenders['footman'] * 0.85);
					$defenders['pikeman'] = round($defenders['pikeman'] * 0.85);
					$defenders['archer'] = round($defenders['archer'] * 0.85);
					$defenders['rifleman'] = round($defenders['rifleman'] * 0.85);
					
					echo "You lost the Battle!<br /><br />";
					$message = $user['username'] . " has attacked you and failed. You have successfully repealed their attack!
					You killed ".$lost_footman." footman, ".$lost_knight." knights, ".$lost_pikeman." pikeman, ".$lost_archer." archers, and ".$lost_rifleman." rifleman.
					You lost ".$killed_footman." footman, ".$killed_knight." knights, ".$killed_pikeman." pikeman, ".$killed_archer." archers, and ".$killed_rifleman." rifleman.";
					$insert1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('Battle Logs', '".$defender_user['username']."', '".$message."')") or die(mysqli_error($mysqli));
					$iron_stolen = 0;
					$gold_stolen = 0;
				}
				
				
				?>
						
					<br /><br />You lost
					<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<tbody>
						<tr>
							<td><img id="building" src="./art/footman.png">
							<td>Footman: <i></td>
							<td><?php echo number_format($lost_footman); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/pikeman.png">
							<td>Pikeman: <i></td>
							<td><?php echo number_format($lost_pikeman); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/knight.png">
							<td>Knight: <i></td>
							<td><?php echo number_format($lost_knight); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/archer.png">
							<td>Archer: <i></td>
							<td><?php echo number_format($lost_archer); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/rifleman.png">
							<td>Rifleman: <i></td>
							<td><?php echo number_format($lost_rifleman); ?></i></td>
						</tr>
						</tbody>
					</table></div><br /><br />
						
					You killed
					<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<tbody>
						<tr>
							<td><img id="building" src="./art/footman.png">
							<td>Footman: <i></td>
							<td><?php echo number_format($killed_footman); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/pikeman.png">
							<td>Pikeman: <i></td>
							<td><?php echo number_format($killed_pikeman); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/knight.png">
							<td>Knight: <i></td>
							<td><?php echo number_format($killed_knight); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/archer.png">
							<td>Archer: <i></td>
							<td><?php echo number_format($killed_archer); ?></i></td>
						</tr>
						<tr>
							<td><img id="building" src="./art/rifleman.png">
							<td>Rifleman: <i></td>
							<td><?php echo number_format($killed_rifleman); ?></i></td>
						</tr>
						</tbody>
					</table></div>
					
				<?php
				
				$stats['gold'] += $gold_stolen;
				$stats['iron'] += $iron_stolen;
				
				$battle1 = mysqli_query($mysqli, "UPDATE stats SET gold=gold-'".$gold_stolen."' WHERE id='".$id."'") or die(mysqli_error($mysqli));
				$battle2 = mysqli_query($mysqli, "UPDATE stats SET iron=iron-'".$iron_stolen."' WHERE id='".$id."'") or die(mysqli_error($mysqli));
				$battle3 = mysqli_query($mysqli, "UPDATE stats SET gold=gold+'".$iron_stolen."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				$battle4 = mysqli_query($mysqli, "UPDATE stats SET iron=iron+'".$iron_stolen."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				$battle5 = mysqli_query($mysqli, "INSERT INTO logs (attacker, defender, gold, iron, time) VALUES ('".$_SESSION['uid']."', '".$id."', '".$gold_stolen."', '".$iron_stolen."', '".time()."')") or die(mysqli_error($mysqli));
				$battle6 = mysqli_query($mysqli, "UPDATE unit SET 
													footman='".$unit['footman']."',
													archer='".$unit['archer']."',
													knight='".$unit['knight']."',
													rifleman='".$unit['rifleman']."',
													trebuchet='".$unit['trebuchet']."',
													catapult='".$unit['catapult']."',
													siegetower='".$unit['siegetower']."',
													batteringram='".$unit['batteringram']."'
													WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				$battle7 = mysqli_query($mysqli, "UPDATE unit SET 
													footman='".$defenders['footman']."',
													archer='".$defenders['archer']."',
													knight='".$defenders['knight']."',
													rifleman='".$defenders['rifleman']."',
													battlement='".$defenders['battlement']."',
													moat='".$defenders['moat']."',
													murderhole='".$defenders['murderhole']."',
													machicolation='".$defenders['machicolation']."'
													WHERE id='".$id."'") or die(mysqli_error($mysqli));
				$battle8 = mysqli_query($mysqli, "UPDATE stats SET
													meat='".$stats['meat']."',
													apples='".$stats['apples']."',
													cheese='".$stats['cheese']."',
													bread='".$stats['bread']."'
													WHERE id='".$stats['id']."'") or die(mysqli_error($mysqli));
				
				
			}
		} else {
			output("You have visited this page incorrectly");
		}
	}
	
	include ("./template/footer.php");
?>