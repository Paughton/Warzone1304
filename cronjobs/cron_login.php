<?php

	include("../system/config.php");
	include("../system/functions.php");
	
	if(empty($_GET['pass'])) {
		output("You have visited this page incorrectly!");
	} else {
		$pass = protect($_GET['pass']);
		if ($pass == "cheesynachos") {
	
			$get_users = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
	
			while($user = mysqli_fetch_assoc($get_users)) {
				$update_login = mysqli_query($mysqli, "UPDATE user SET lastlogin=0 WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
				$update_blockade = mysqli_query($mysqli, "UPDATE user SET blockade=0 WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
			}
		} else {
			output("Nice Try!");
		}
	}
	
?>