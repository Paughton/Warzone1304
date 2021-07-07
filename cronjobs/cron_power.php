<?php
	include ("../system/config.php");
	include ("../system/functions.php");
	
	$get_users = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
	
	while($user_data = mysqli_fetch_assoc($get_users)) {
		
		$get_user_unit = mysqli_query($mysqli, "SELECT * FROM unit WHERE id='".$user_data['id']."'") or die(mysqli_error($mysqli));
		$get_user_building = mysqli_query($mysqli, "SELECT * FROM buildings WHERE id='".$user_data['id']."'") or die(mysqli_error($mysqli));
		
		$user_building = mysqli_fetch_assoc($get_user_building);
		$user_unit = mysqli_fetch_assoc($get_user_unit);
		
		$unit_power = ($user_unit['footman'] * 0.5) + ($user_unit['archer'] * 0.5) + ($user_unit['knight'] * 0.5) + ($user_unit['rifleman'] * 0.5) + ($user_unit['pikeman'] * 0.5) + ($user_unit['general'] * 0.5);
		$building_power = ($user_building['applefarm'] * 0.5) + ($user_building['lumbermill'] * 0.5) + ($user_building['mine'] * 0.5) + ($user_building['hunterpost'] * 0.5) + ($user_building['barrack'] * 0.5) + ($user_building['stable'] * 0.5) + ($user_building['shootingrange'] * 0.5);
		$building_power += ($user_building['dairyfarm'] * 0.5) + ($user_building['wheatfarm'] * 0.5) + ($user_building['mill'] * 0.5) + ($user_building['bakery'] * 0.5) + ($user_building['marketplace'] * 0.5) + ($user_building['wall']) + ($user_building['engineerworkshop']*0.5) + ($user_building['siegecamp']*0.5);
		$land_power = ($user_data['land'] * 0.0005);
		$stronghold_power = pow($user_data['level'], 3);
		$total_power = $building_power + $unit_power + $land_power + $stronghold_power;
		
		$update_stats = mysqli_query($mysqli, "UPDATE ranking SET power='".$total_power."' WHERE id='".$user_data['id']."'") or die(mysqli_error($mysqli));
	}
	
?>