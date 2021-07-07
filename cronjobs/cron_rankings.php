<?php
	include("../system/config.php");
	
	$get_power = mysqli_query($mysqli, "SELECT id, power FROM ranking ORDER BY power DESC") or die(mysqli_error($mysqli));
	$i = 1;
	$rank = array();
	while ($power = mysqli_fetch_assoc($get_power)) {
		$rank[$power['id']] = $power['power'];
		mysqli_query($mysqli, "UPDATE ranking SET power='".$power['power']."' WHERE id='".$power['id']."'") or die(mysqli_error($mysqli));
		$i++;
	}
	
	asort($rank);
	$rank2 = array_reverse($rank, true);
	$i = 1;
	foreach($rank2 as $key => $val) {
		mysqli_query($mysqli, "UPDATE ranking SET overall='".$i."' WHERE id='".$key."'") or die(mysqli_error($mysqli));
		$i++;
	}
	
	
?>