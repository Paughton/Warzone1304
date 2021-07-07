<?php
	include("../system/config.php");
	include("../system/functions.php");
	
	mysqli_query($mysqli, "DELETE FROM logs WHERE time<'".(time()-86400)."'") or die(mysqli_error($mysqli));
	
	
?>