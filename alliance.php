<?php
	$page = "alliance";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		if(empty($_GET['id'])) {
			output("You have visited this page incorrectly!");
		} else {
			$id = protect($_GET['id']);
			$alliance_check = mysqli_query($mysqli, "SELECT * FROM alliance WHERE id='".$id."'") or die(mysqli_error($mysqli));
			if (mysqli_num_rows($alliance_check) == 0) {
				output("There is no alliance with that ID!");
			} else {
				$a_alliance = mysqli_fetch_assoc($alliance_check);
				
				if (!empty($_POST['join'])) {
					$ins1 = mysqli_query($mysqli, "INSERT INTO alliancerequest (allianceid, username) VALUES ('".$id."', '".$user['username']."')") or die(mysqli_error($mysqli));
					output("Your request to join " . $a_alliance['name'] . " has been sent!");
				}
				
				if (!empty($_POST['leave'])) {
					$update_stats = mysqli_query($mysqli, "UPDATE user SET allianceid=0 WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					output("You have left " . $a_alliance['name'] . "!");
				}
				
				if (!empty($_POST['disband'])) {
					$update_stats = mysqli_query($mysqli, "UPDATE user SET allianceid=0 WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					$delete_alliance = mysqli_query($mysqli, "DELETE FROM alliance WHERE id='".$a_alliance['id']."'") or die(mysqli_error($mysqli));
					output("You have disbaned " . $a_alliance['name'] . "!");
				}
				
				?>
			
			<hr />
			
			<div class="jumbotron">
				<div class="row">
					<div class="col-sm-6">
						<img class="flag" src="./flag/<?php echo $a_alliance['flag']; ?>" onclick="location.href='<?php if ($a_alliance['president'] == $user['username']) { echo "alliancecontrol"; } ?>';">
					</div>
					<div class="col-sm-6">
						<h1><?php echo $a_alliance['name']; ?> 
					</div>
				</div>
			</div>
			
			<hr />
			
			<?php if($user['allianceid'] == 0) { ?>
				<form action="alliance?id=<?php echo $id ?>" method="POST">
					<input type="submit" name="join" value="Apply to Join"/>
				</form>
			<?php 
				} elseif ($a_alliance['id'] == $user['allianceid']) {
					
					$member_count = 0;
					$get_members = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
					while($member = mysqli_fetch_assoc($get_members)) {
						if ($member['allianceid'] == $a_alliance['id']) {
							$member_count += 1;
						}
					}
					
					?>
					<div class="heading">
						<img src="./art/gold.png" height="20" width="20"><i><?php echo number_format($alliance['gold']);?></i>   
						<img src="./art/iron.png" height="20" width="20"><i><?php echo number_format($alliance['iron']);?></i>   
						<img src="./art/stone.png" height="20" width="20"><i><?php echo number_format($alliance['stone']);?></i>   
						<img src="./art/lumber.png" height="20" width="20"><i><?php echo number_format($alliance['lumber']);?></i>
					</div><br />
					<?php if ($a_alliance['president'] != $user['username']) { ?>
						<form action="alliance?id=<?php echo $id ?>" method="POST">
							<input type="submit" name="leave" value="Leave Alliance"/>
						</form>
					<?php } elseif ($member_count == 1) { ?>
						<form action="alliance?id=<?php echo $id ?>" method="POST">
							<input type="submit" name="disband" value="Disband Alliance"/>
						</form>
					<?php } ?>
				<?php }
			?>
			
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<tr>
					<thead><th><b>Member List</b></th><th><b>Position</b></th>
					<?php
					
						
						if ($user['username'] == $a_alliance['president']) {
							echo "<th>";
							?><button onclick="location.href='alliancecontrol';">Alliance Control Panel</button><?php
							echo "</th>";
						}
						
					
					?>
				</thead></tr>
				<tbody>
				<?php
				
					$get_user = mysqli_query($mysqli, "SELECT * FROM user WHERE allianceid='".$a_alliance['id']."'") or die(mysqli_error($mysqli));
					while($row = mysqli_fetch_assoc($get_user)) {
						
						if ($a_alliance['president'] == $row['username']) {
							echo "<tr><td><a href=\"kingdom?id=" .$row['id']."\">" . $row['username'] . "</a></td><td>President</td><td></td></tr>";
						} else {
							echo "<tr><td><a href=\"kingdom?id=" .$row['id']."\">" . $row['username'] . "</a></td><td>Member</td></tr>";
						}
					}
				
				?>
				</tbody>
			</table>
			</div>
				<?php
			}
		}
	}
	
	include("./template/footer.php");
?>