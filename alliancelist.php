<?php

	$page = "alliancelist";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		?>
			<b>Alliances</b>
			<hr />
			<br />
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<tr>
					<thead>
					<th width="150px"><b>Alliance Name</b></th>
					</thead>
				</tr>
				<tbody>
				<?php
					$get_alliance = mysqli_query($mysqli, "SELECT * FROM alliance") or die(mysqli_error($mysqli));
					while($row = mysqli_fetch_assoc($get_alliance)) {
						echo "<tr><td>";
						echo "<a href=\"alliance?id=" .$row['id']."\">" . $row['name'] . "</a>";
						echo "</td></tr>";
					}
				?>
				</tbody>
			</table></div>
		<?php
	}
	
	include("./template/footer.php");
?>