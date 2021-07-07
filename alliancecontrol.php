<?php

	$page = "alliancecontrol";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} elseif ($user['allianceid'] == 0) {
		output("You must be in an Alliance to view this page!");
	} elseif ($alliance['president'] != $user['username']) {
		output("You must be an Alliance President to see this page!");
	} else {
		
		if (!empty($_POST['bank'])) {
			$option = protect($_POST['option']);
			$gold = protect($_POST['gold']);
			$iron = protect($_POST['iron']);
			$stone = protect($_POST['stone']);
			$lumber = protect($_POST['lumber']);
			$receiver = protect($_POST['receiver']);
			
			if($gold < 0 || $iron < 0 | $stone < 0 || $lumber < 0) {
				output("You must put a positive number of resources!");
			}
			
			if ($option == "deposit") {
				if ($stats['gold'] < $gold) {
					output("You do not have enough Gold!");
				} elseif ($stats['iron'] < $iron) {
					output("You do not have enough Iron!");
				} elseif ($stats['stone'] < $stone) {
					output("You do not have enough Stone!");
				} elseif ($stats['lumber'] < $lumber) {
					output("You do not have enough Lumber!");
				} else {
					
					$stats['gold'] -= $gold;
					$stats['iron'] -= $iron;
					$stats['stone'] -= $stone;
					$stats['lumber'] -= $lumber;
					
					$alliance['gold'] += $gold;
					$alliance['iron'] += $iron;
					$alliance['stone'] += $stone;
					$alliance['lumber'] += $lumber;
					
					$update_stats = mysqli_query($mysqli, "UPDATE alliance SET
														   gold='".$alliance['gold']."',
														   iron='".$alliance['iron']."',
														   stone='".$alliance['stone']."',
														   lumber='".$alliance['lumber']."'
														   WHERE id='".$user['allianceid']."'") or die(mysqli_error($mysqli));
														   
					$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														   gold='".$stats['gold']."',
														   iron='".$stats['iron']."',
														   stone='".$stats['stone']."',
														   lumber='".$stats['lumber']."'
														   WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					
					output("Resources have been successfully deposited!");
					
				}
			} elseif ($option == "send") {
				
				$check_user = mysqli_query($mysqli, "SELECT id FROM user WHERE username='$receiver'") or die(mysqli_error($mysqli));
				if (mysqli_num_rows($check_user) == 0) {
					output("No one has that username!");
				} elseif ($alliance['gold'] < $gold) {
					output("Your Alliance does not have enough Gold!");
				} elseif ($alliance['iron'] < $iron) {
					output("Your Alliance does not have enough Iron!");
				} elseif ($alliance['stone'] < $stone) {
					output("Your Alliance does not have enough Stone!");
				} elseif ($alliance['lumber'] < $lumber) {
					output("Your Alliance does not have enough Lumber!");
				} else {
					
					$receiver_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='$receiver'") or die(mysqli_error($mysqli));
					$receiver1 = mysqli_fetch_assoc($receiver_get);
					
					$user_receiver_get = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$receiver1['id']."'") or die(mysqli_error($mysqli));
					$user_receiver = mysqli_fetch_assoc($user_receiver_get);
					$user_receiver['gold'] += $gold;
					$user_receiver['iron'] += $iron;
					$user_receiver['stone'] += $stone;
					$user_receiver['lumber'] += $lumber;
					
					$alliance['gold'] -= $gold;
					$alliance['iron'] -= $iron;
					$alliance['stone'] -= $stone;
					$alliance['lumber'] -= $lumber;
					
					$update_stats = mysqli_query($mysqli, "UPDATE alliance SET
														   gold='".$alliance['gold']."',
														   iron='".$alliance['iron']."',
														   stone='".$alliance['stone']."',
														   lumber='".$alliance['lumber']."'
														   WHERE id='".$user['allianceid']."'") or die(mysqli_error($mysqli));
														   
					$update_stats = mysqli_query($mysqli, "UPDATE stats SET
														   gold='".$user_receiver['gold']."',
														   iron='".$user_receiver['iron']."',
														   stone='".$user_receiver['stone']."',
														   lumber='".$user_receiver['lumber']."'
														   WHERE id='".$receiver1['id']."'") or die(mysqli_error($mysqli));
					
					output("Resources have been sent successfully!");
				}
				
			} else {
				output("You have incorrectly filled out the form!");
			}
		}
		
		
		if (!empty($_POST['tax'])) {
			$gold = protect($_POST['gold']);
			$iron = protect($_POST['iron']);
			$stone = protect($_POST['stone']);
			$lumber = protect($_POST['lumber']);
			
			if ($gold < 0 || $iron < 0 || $stone < 0 || $lumber < 0) {
				output("You must put in a positive number in the tax field!");
			} else if ($gold < 0 || $gold > 100) {
				output("Gold tax must be no less that 0 and no more than 100!");
			} else if ($iron < 0 || $iron > 100) {
				output("Iron tax must be no less that 0 and no more than 100!");
			} else if ($stone < 0 || $stone > 100) {
				output("Stone tax must be no less that 0 and no more than 100!");
			} else if ($lumber < 0 || $lumber > 100) {
				output("Lumber tax must be no less that 0 and no more than 100!");
			} else {
				
				$update_taxes = mysqli_query($mysqli, "UPDATE alliance SET
													   goldtax='".$gold."',
													   irontax='".$iron."',
													   stonetax='".$stone."',
													   lumbertax='".$lumber."'
													   WHERE id='".$user['allianceid']."'") or die(mysqli_error($mysqli));
			
				output("Taxes have been successfully set!");
					
			}
		}
		
		if (!empty($_POST['remove'])) {
			$id = protect($_POST['id']);
			$remove_users = mysqli_query($mysqli, "UPDATE user SET allianceid=0 WHERE id='".$id."'") or die(mysqli_error($mysqli));
			output("Member was successfully removed!");
		}
		
		if (!empty($_POST['promote'])) {
			$username = protect($_POST['username']);
			$update_president = mysqli_query($mysqli, "UPDATE alliance SET president='".$username."' WHERE id='".$alliance['id']."'") or die(mysqli_error($mysqli));
			output("Member was successfully Promoted!");
		}
		
		if (!empty($_POST['accept'])) {
			$id = protect($_POST['id']);
			$username = protect($_POST['username']);
			
			$accept_members = mysqli_query($mysqli, "UPDATE user SET allianceid='".$alliance['id']."' WHERE id='".$id."'") or die(mysqli_error($mysqli));
			$delete1 = mysqli_query($mysqli, "DELETE FROM alliancerequest WHERE username='".$username."'") or die(mysqli_error($mysqli));
			output("Member was successfully added to your Alliance!");
		}
		
		if (!empty($_POST['deny'])) {
			$username = protect($_POST['username']);
			
			$delete1 = mysqli_query($mysqli, "DELETE FROM alliancerequest WHERE username='".$username."'") or die(mysqli_error($mysqli));
			output("Member was successfully denied access to your Alliance!");
		}
		
		if (!empty($_POST['changeflag'])) {
			
			$flag = protect($_POST['flag']);
			$update_flag = mysqli_query($mysqli, "UPDATE alliance SET flag='".$flag."' WHERE id='".$alliance['id']."'") or die(mysqli_error($mysqli));
			output("Your Alliance Flag Has Changed!");
			
		}
		
		?>
		
			<script>
				function select_img() {
					var flag = document.getElementById("flag").value;
					document.getElementById("flag_display").src="./flag/" + flag;
				}
			</script>
		
			<div class="heading" style="letter-spacing: 5px;">
				<img src="./art/gold.png" height="20" width="20"><i><?php echo number_format($alliance['gold']);?></i>   
				<img src="./art/iron.png" height="20" width="20"><i><?php echo number_format($alliance['iron']);?></i>   
				<img src="./art/stone.png" height="20" width="20"><i><?php echo number_format($alliance['stone']);?></i>   
				<img src="./art/lumber.png" height="20" width="20"><i><?php echo number_format($alliance['lumber']);?></i>
			</div><br />
		
			<h1>Alliance Bank</h1>
			<hr />
			<form action="alliancecontrol" method="POST">
				<select name="option">
					<option value="deposit">Deposit Resources</option>
					<option value="send">Send Resources</option>
				</select><br />
				Gold: <input type="text" name="gold" autocomplete="off"/><br />
				Iron: <input type="text" name="iron" autocomplete="off"/><br />
				Stone: <input type="text" name="stone" autocomplete="off"/><br />
				Lumber: <input type="text" name="lumber" autocomplete="off"/><br />
				To Whom (not required): <input type="text" name="receiver" autocomplete="off"/><br />
				<input type="submit" name="bank" value="Send / Deposit"/>
			</form><br /><br /><br />
		
			<h1>Alliance Taxes</h1>
			<hr />
			<b>NOTE</b>: The number you give must be between 1 - 100, its a percentage! (do not put the '%' sign).<br />
			<form action="alliancecontrol" method="POST">
				Gold Tax: <input type="text" name="gold" autocomplete="off"/><br />
				Iron Tax: <input type="text" name="iron" autocomplete="off"/><br />
				Stone Tax: <input type="text" name="stone" autocomplete="off"/><br />
				Lumber Tax: <input type="text" name="lumber" autocomplete="off"/><br />
				<input type="submit" name="tax" value="Apply Taxes"/>
			</form><br /><br /><br />
			
			<h1>Member List</h1>
			<hr />
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<tr><thead>
					<th>Member</th>
					<th></th>
					<th></th>
				</thead></tr><tbody>
				<?php
				
				$get_user = mysqli_query($mysqli, "SELECT * FROM user WHERE allianceid='".$alliance['id']."'") or die(mysqli_error($mysqli));
					while($row = mysqli_fetch_assoc($get_user)) {
						$form1 = "<form action='alliancecontrol' method='POST'><input type='hidden' name='id' value='". $row['id'] ."' /><input type='submit' name='remove' value='Remove'/></form>";
						$form2 = "<form action='alliancecontrol' method='POST'><input type='hidden' name='username' value='". $row['username'] ."' /><input type='submit' name='promote' value='Promote'/></form>";
						echo "<tr><td><a href=\"kingdom?id=" .$row['id']."\">" . $row['username'] . "</a></td><td>".$form1."</td><td>".$form2."</td><tr>";
					}
				
				?>
			</tbody></table></div><br /><br /><br />
			
			
			<h1>Applicant List</h1>
			<hr />
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Applicant</th>
					<th></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php
				
				$get_user = mysqli_query($mysqli, "SELECT * FROM alliancerequest WHERE allianceid='".$alliance['id']."'") or die(mysqli_error($mysqli));
					while($row = mysqli_fetch_assoc($get_user)) {
						
						$applicant_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$row['username']."'") or die(mysqli_error($mysqli));
						$applicant = mysqli_fetch_assoc($applicant_get);
						
						$form1 = "<form action='alliancecontrol' method='POST'><input type='hidden' name='username' value='". $row['username'] ."' /><input type='hidden' name='id' value='". $applicant['id'] ."' /><input type='submit' name='accept' value='Accept'/></form>";
						$form2 = "<form action='alliancecontrol' method='POST'><input type='hidden' name='username' value='". $row['username'] ."' /><input type='submit' name='deny' value='Deny'/></form>";
						echo "<tr><td><a href=\"kingdom?id=" .$row['id']."\">" . $row['username'] . "</a></td><td>".$form1."</td><td>".$form2."</td><tr>";
					}
				
				?>
			</tbody></table></div><br /><br /><br />
			
			<h1>Change Alliance Flag</h1>
			<hr />
			<img src="./flag/britonian.png" id="flag_display">
			<form action="alliancecontrol" method="POST">
				Alliance Flag: <select name="flag" id="flag" onchange="select_img()"><br />
					<option value="britonian.png">Britonian</option>
					<option value="corrupted.png">Corrupted</option>
					<option value="danlgen.png">Danglen</option>
					<option value="eswor.png">Eswor</option>
					<option value="lowriver.png">Low River</option>
					<option value="neworder.png">New Order</option>
					<option value="oozing.png">Oozing</option>
					<option value="rebellion.png">Rebellion</option>
					<option value="risingsun.png">Rising Sun</option>
					<option value="alda.png">Alda</option>
					<option value="laerun.png">Laerun</option>
					<option value="mapel.png">Mapel</option>
					<option value="marciao.png">Marciao</option>
					<option value="nacirema.png">Nacierema</option>
					<option value="nadshaw.png">Nadshaw</option>
					<option value="namor.png">Namor</option>
					<option value="noslarc.png">Noslarc</option>
					<option value="oblivion.png">Oblivion</option>
					<option value="ogitech.png">Ogitech</option>
				</select><br />
				<input type="submit" name="changeflag" value="Change Flag"/>
			</form>
		
			<?php
				if ($user['elite'] == 1) {
					?>
						<h1>Change Flag [Elite]</h1>
						<hr />
						<form action="uploadalliance" method="post" enctype="multipart/form-data">
							<input type="file" name="fileToUpload" id="fileToUpload"><br />
							<input type="submit" value="Upload Flag" name="submit">
						</form>
					<?php
				}
			?>
		
		<?php
		
	}
	
	include("./template/footer.php");
?>