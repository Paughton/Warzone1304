<?php

	$page = "rankings";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		?>
			<b>Rankings</b>
			<hr />
			<br />
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th width="50px"><b>Rank</b></th>
						<th width="150px"><b>Stronghold Name</b></th>
						<th width="200px"><b>Stronghold Level</b></th>
						<th width="200px"><b>Power</b></th>
					</tr>
					</thead>
					<tbody>
				<?php
					$get_user = mysqli_query($mysqli, "SELECT id, overall FROM ranking WHERE overall>0 ORDER BY overall ASC") or die(mysqli_error($mysqli));
					while($row = mysqli_fetch_assoc($get_user)) {
						$get_users = mysqli_query($mysqli, "SELECT * FROM user WHERE id='".$row['id']."'") or die(mysqli_error($mysqli));
						$rank_name = mysqli_fetch_assoc($get_users);
						$get_power = mysqli_query($mysqli, "SELECT power FROM ranking WHERE id='".$row['id']."'") or die(mysql_error($mysqli));
						$rank_power = mysqli_fetch_assoc($get_power);
						if ($rank_name['account'] == 0) {
							echo "<tr>";
							echo "<td>" . $row['overall'] . "</td>";
							echo "<td><a href=\"kingdom?id=" .$row['id']."\">" . $rank_name['username'] . "</a></td>";
							echo "<td>" . numeral_format($rank_name['level']) . "</td>";
							echo "<td>" . number_format($rank_power['power']) . "</td>";
							echo "</tr>";
						}
					}
				?>
				</tbody></table>
			</div>
		<?php
	}
	
	include("./template/footer.php");
?>