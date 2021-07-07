<?php
	// DATABASE CONNECTION
	define("DB_SERVER", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB", "warzone1304");
	
	// CREATE CONNECTION
	$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB) or die(mysqli_error($mysqli));
	
	// GENERAL SETTINGS
	$title 				= "Warzone 1304";
	$seperator 			= " - ";
	$description 		= "Create your own Kingdom";
	$cssversion			= "1.6";
	
	// ADMIN SETTINGS
	$admin              = array();
	
	// ADMIN USER SETTINGS
	$admin['name']      = "Banglesway Citadel";
	$admin['id']        = 1;
	
	// ADMIN INBOX SETTINGS
	$admin['namelist']  = array("Warzone 1304 CS", "Warzone 1304");
	
	// TECHNICAL SETTINGS
	$maintenance 		= false;
	
	if (!empty($_SESSION['uid'])) {
		$stats_get = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$stats = mysqli_fetch_assoc($stats_get);

		$unit_get = mysqli_query($mysqli, "SELECT * FROM unit WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$unit = mysqli_fetch_assoc($unit_get);

		$user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$user = mysqli_fetch_assoc($user_get);

		$worker_get = mysqli_query($mysqli, "SELECT * FROM worker WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$worker = mysqli_fetch_assoc($worker_get);

		$buildings_get = mysqli_query($mysqli, "SELECT * FROM buildings WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$buildings = mysqli_fetch_assoc($buildings_get);

		$current_ranking_get = mysqli_query($mysqli, "SELECT * FROM ranking WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$current_ranking = mysqli_fetch_assoc($current_ranking_get);

		$techtree_get = mysqli_query($mysqli, "SELECT * FROM techtree WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$techtree = mysqli_fetch_assoc($techtree_get);

		$alliance_get = mysqli_query($mysqli, "SELECT * FROM alliance WHERE id='".$user['allianceid']."'") or die(mysqli_error($mysqli));
		$alliance = mysqli_fetch_assoc($alliance_get);
		
		$weapon_get = mysqli_query($mysqli, "SELECT * FROM weapon WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
		$weapon = mysqli_fetch_assoc($weapon_get);
	}
	
?>