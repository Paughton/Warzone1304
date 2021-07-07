<?php
	include ("../system/functions.php");
	include ("../system/config.php");
	
	if(empty($_GET['pass'])) {
		output("You have visited this page incorrectly!");
	} else {
		$pass = protect($_GET['pass']);
		if ($pass == "cheesynachos") {
	
	$get_users = mysqli_query($mysqli, "SELECT * FROM stats") or die(mysqli_error($mysqli));
	$get_user_worker = mysqli_query($mysqli, "SELECT * FROM worker") or die(mysqli_error($mysqli));
	$get_user_unit = mysqli_query($mysqli, "SELECT * FROM unit") or die(mysqli_error($mysqli));
	$get_user_techtree = mysqli_query($mysqli, "SELECT * FROM techtree") or die(mysqli_error($mysqli));
	$get_user_data = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
	$get_user_weapon = mysqli_query($mysqli, "SELECT * FROM weapon") or die(mysqli_error($mysqli));
	
	while($player = mysqli_fetch_assoc($get_users)) {
		$user_worker = mysqli_fetch_assoc($get_user_worker);
		$user_unit = mysqli_fetch_assoc($get_user_unit);
		$user_techtree = mysqli_fetch_assoc($get_user_techtree);
		$user_data = mysqli_fetch_assoc($get_user_data);
		$user_weapon = mysqli_fetch_assoc($get_user_weapon);
		
		$get_user_alliance = mysqli_query($mysqli, "SELECT * FROM alliance WHERE id='".$user_data['allianceid']."'") or die(mysqli_error($mysqli));
		$alliance = mysqli_fetch_assoc($get_user_alliance);
		
		$goldtax = 1 - ($alliance['goldtax']*0.01);
		$irontax = 1 - ($alliance['irontax']*0.01);
		$stonetax = 1 - ($alliance['stonetax']*0.01);
		$lumbertax = 1 - ($alliance['lumbertax']*0.01);
		
		$cheese_mod = 1;
		$lumber_mod = 1;
		$gold_mod   = 1;
		if ($user_data['terrain'] == "grassland") {
			$cheese_mod = 1.4;
		} elseif ($user_data['terrain'] == "woodland") {
			$lumber_mod = 1.4;
		} elseif ($user_data['terrain'] == "mountain") {
			$gold_mod   = 1.25;
		}
		
		if ($user_data['elite'] == 1) {
			$income_mod = 1.25;
		} else {
			$income_mod = 1;
		}
		
		$user_weapon['sword']    += $user_worker['weaponsmithery'];
		$user_weapon['pike']     += $user_worker['weaponsmithery'];
		$user_weapon['shield']   += $user_worker['weaponsmithery'];
		$user_weapon['crossbow'] += $user_worker['bowyerworkshop'];
		$user_weapon['longbow']  += $user_worker['bowyerworkshop'];
		$user_weapon['armor']    += $user_worker['armory'];
		
		$player['gold'] += round($user_worker['mine'] * $income_mod * $gold_mod * $goldtax);
		$player['stone'] += round($user_worker['mine'] * 3 * $income_mod * $stonetax);
		$player['iron'] += round($user_worker['mine'] * 2 * $income_mod * $irontax);
	
		$player['meat'] += $user_worker['hunterpost'] * 3 * $income_mod;
		$player['apples'] += $user_worker['applefarm'] * 5 * $income_mod;
		$player['cheese'] += $user_worker['dairyfarm'] * 6 * $income_mod * $cheese_mod;
	
		$player['lumber'] += round($user_worker['lumbermill'] * 4 * $income_mod * $lumber_mod * $lumbertax);
	
		$alliance['gold'] += round($user_worker['mine'] * $income_mod * ($alliance['goldtax']*0.01));
		$alliance['iron'] += round($user_worker['mine'] * 2 * $income_mod * ($alliance['irontax']*0.01));
		$alliance['stone'] += round($user_worker['mine'] * 3 * $income_mod * ($alliance['stonetax']*0.01));
		$alliance['lumber'] += round($user_worker['lumbermill'] * 4 * $income_mod * ($alliance['lumbertax']*0.01));
	
        $income_counter = "true";

		/* Bread Making Process */
		$player['wheat'] += $user_worker['wheatfarm'] * 3 * $income_mod;
		if ($player['wheat'] >= $user_worker['mill'] * 3 * $income_mod) {
			$player['wheat'] -= $user_worker['mill'] * 3 * $income_mod;
			$player['flour'] += ($user_worker['mill'] * 3)*3 * $income_mod;
		} else {
			$player['flour'] += ($player['wheat'] * 3)*3 * $income_mod;
			$player['wheat'] -= $player['wheat'];
		}
		if ($player['flour'] >= $user_worker['bakery'] * 4 * $income_mod) {
			$player['flour'] -= $user_worker['bakery'] * 4 * $income_mod;
			$player['bread'] += ($user_worker['bakery'] * 4)*9 * $income_mod;
		} else {
			$player['bread'] += ($player['flour'] * 4)*9 * $income_mod;
			$player['flour'] -= $player['flour'];
		}
	
		if ($user_techtree['path'] == 24) {
			$food_mod = 2;
		} else {
			$food_mod = 1;
		}
	
		$player['apples'] -= $user_unit['worker'] + $user_worker['mine'] + $user_worker['hunterpost'] + $user_worker['applefarm'] + $user_worker['dairyfarm'] + $user_worker['lumbermill'] + $user_worker['wheatfarm'] + $user_worker['mill'] + $user_worker['bakery'];
		$player['apples'] -= $user_worker['bowyerworkshop'] + $user_worker['weaponsmithery'] + $user_worker['armory'];
		$player['apples'] -= (($user_unit['footman'] * 2) + ($user_unit['archer'] * 2) + ($user_unit['knight'] * 2) + ($user_unit['rifleman'] * 2) +($user_unit['pikeman'] * 2))/$food_mod;
		if ($player['apples'] < 0) {
			$counter = $player['apples'] * -1;
			$player['apples'] = 0;
			
			$player['cheese'] -= $counter;
			if ($player['cheese'] < 0) {
				$counter = $player['cheese'] * -1;
				$player['cheese'] = 0;
				
				$player['meat'] -= $counter;
				if ($player['meat'] < 0) {
					$counter = $player['meat'] * -1;
					$player['meat'] = 0;
					
						$player['bread'] -= $counter;
						if ($player['bread'] < 0) {
							$player['bread'] = 0;
							$income_counter = "false";
							
						}
				}
			}
		}
	
		if ($income_counter != "false") {
			
			$update_stats = mysqli_query($mysqli, "UPDATE alliance SET
													gold='".$alliance['gold']."',
													iron='".$alliance['iron']."',
													stone='".$alliance['stone']."',
													lumber='".$alliance['lumber']."'
													WHERE id='".$user_data['allianceid']."'") or die(mysqli_error($mysqli));
			
			$update_stats = mysqli_query($mysqli, "UPDATE weapon SET
													sword='".$user_weapon['sword']."',
													pike='".$user_weapon['pike']."',
													shield='".$user_weapon['shield']."',
													crossbow='".$user_weapon['crossbow']."',
													longbow='".$user_weapon['longbow']."',
													armor='".$user_weapon['armor']."'
													WHERE id='".$user_data['id']."'") or die(mysqli_error($mysqli));
			
			$update_stats = mysqli_query($mysqli, "UPDATE stats SET
													gold='".$player['gold']."',
													stone='".$player['stone']."',
													iron='".$player['iron']."',
                                                    lumber='".$player['lumber']."',
													meat='".$player['meat']."',
													apples='".$player['apples']."',
													cheese='".$player['cheese']."',
													wheat='".$player['wheat']."',
													flour='".$player['flour']."',
													bread='".$player['bread']."'
													WHERE id='".$player['id']."'") or die(mysqli_error($mysqli));
		}
	}
	} else {
		output("Nice Try!");
	}
	}
	
?>