<?php
	$page = "espionage";
	include ("./template/header.php");
	
	if(empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		if(!empty($_POST['espionage'])) {
			$id = protect($_POST['id']);
			
			$defender_get = mysqli_query($mysqli, "SELECT * FROM unit WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defenders = mysqli_fetch_assoc($defender_get);
			
			$defender_user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_user = mysqli_fetch_assoc($defender_user_get);
			
			$defender_building_get = mysqli_query($mysqli, "SELECT * FROM buildings WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_building = mysqli_fetch_assoc($defender_building_get);
			
			$defender_ranking_get = mysqli_query($mysqli, "SELECT * FROM ranking WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$defender_ranking = mysqli_fetch_assoc($defender_ranking_get);
			
			$user_check = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$id."'") or die(mysqli_error($mysqli));
			
			if (mysqli_num_rows($user_check) == 0)  {
				output("There is no user with that ID!");
			} elseif (ceil($current_ranking['power'] / 2) > $defender_ranking['power']) {
				output("Your power is too high to Espionage this player!");
			} elseif ($defender_user['username'] == $admin) {
				output($defender_user['username'] . " is invulnerable, if this is an issue please contact Warzone 1503 CS!");
			} elseif ($defender_user['level'] < 4 || $user['level'] < 4) {
				output("You or this player doesn't have the required Stronghold Level to Espionage yet (must be level: IV)");
			} else { // Espionage Sequence
			
				$chance = rand(1, 100);
				$chance_modifier = 0;
				
				if ($defenders['machicolation'] >= 20) { $chance_modifier = 40; } else { $chance_modifier += $defenders['machicolation'] * 2; }
				
				if ($chance <= round(75-$chance_modifier)) {
					
					echo "You had successfully gained information on " .$defender_user['username']. " <br />";
					?>
						<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<tbody>
							<tr><td><img src="./art/footman.png" id="building"></td><td><b>Footman: </td></b><i><td><?php echo number_format($defenders['footman']); ?></i></td></tr><br />
							<tr><td><img src="./art/archer.png" id="building"></td><td><b>Archers: </td></b><i><td><?php echo number_format($defenders['archer']); ?></i></td></tr><br />
							<tr><td><img src="./art/knight.png" id="building"></td><td><b>Knights: </td></b><i><td><?php echo number_format($defenders['knight']); ?></i></td></tr><br />
							<tr><td><img src="./art/rifleman.png" id="building"></td><td><b>Rifleman: </td></b><i><td><?php echo number_format($defenders['rifleman']); ?></i></td></tr><br />
							<tr><td><img src="./art/pikeman.png" id="building"></td><td><b>Pikeman: </td></b><i><td><?php echo number_format($defenders['pikeman']); ?></i></td></tr><br />
							<tr><td><img src="./art/general.png" id="building"></td><td><b>General: </td></b><i><td><?php echo number_format($defenders['general']); ?></i></td></tr><br />
							</tbody>
						</table></div>
					
					<?php
					
				} else {
					
					echo "The Espionage has failed and the monarch of " . $defender_user['username'] . " has been notified<br />";
					$message = $user['username'] . " has attempted to espionage you! You have caught the traitor, and no information was leaked.";
					$insert1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('Espionage Logs', '".$defender_user['username']."', '".$message."')") or die(mysqli_error($mysqli));
					
				}
				
				
			}
		} else {
			output("You have visited this page incorrectly");
		}
	}
	
	include ("./template/footer.php");
?>