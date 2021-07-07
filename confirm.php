<?php

	$page = "confirm";
	include("./template/header.php");
	
	if(empty($_GET['key'])) {
		output("You have visited this page incorrectly!");
	} else {
	
		$key = protect($_GET['key']);
	
		$confirm_check = mysqli_query($mysqli, "SELECT * FROM confirm WHERE confirmkey='".$key."'") or die (mysqli_error($mysqli));
		if (mysqli_num_rows($confirm_check) == 0) {
			output("Invalid Key!");
		} else {
			$confirm = mysqli_fetch_assoc($confirm_check);
			$update = mysqli_query($mysqli, "UPDATE user SET active=1 WHERE id='".$confirm['userid']."'") or die(mysqli_error($mysqli));
			$delete = mysqli_query($mysqli, "DELETE FROM confirm WHERE id='".$confirm['id']."'") or die(mysqli_error($mysqli));
			output("Your account has been activated!");
		}
		
	}
	
	include("./template/footer.php");
	
?>