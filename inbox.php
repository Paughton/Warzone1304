<?php

	$page = "inbox";
	include ("./template/header.php");
	
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} elseif($user['inbox'] == 1) {
		output("We're sorry but you are banned from using the inbox!");
	} else {
		
		$message_count = 0;
		$get_messages = mysqli_query($mysqli, "SELECT * FROM messages") or die(mysqli_error($mysqli));
		while($message = mysqli_fetch_assoc($get_messages)) {
			if ($user['username'] == $message['receiver']) {
				$message_count += 1;
			}
		}
		
		?>
		<script type="text/javascript">
			document.title = "<?php echo "Inbox (" . $message_count . ")" ?>"
		</script>
		<?php
		
		
		if(!empty($_POST['send'])) {
			$receiver =  protect($_POST['receiver']);
			$message =  protect($_POST['message']);
			$sender = $user['username'];
			
			if (in_array($receiver, $admin['namelist'])) { // Checks to see if receiver is one of the names I hold
				$receiver = $admin['name']; // Change this to whatever account I'm using
			}
			
			if ($sender == $admin['name']) {
				$sender = protect($_POST['sendas']);
			}
			
			$message1 = mysqli_query($mysqli, "SELECT id FROM user WHERE username='$receiver'") or die(mysqli_error($mysqli));
			if ($receiver != "All Users") {
				if (mysqli_num_rows($message1) == 0) {
					output("No one has that username!");
					$receiver = "-404(ERROR)-";
				}
			}
			
			if ($receiver != "-404(ERROR)-") {
				if ($receiver == "All Users" && $user['username'] == $admin['name']) {
					$get_users = mysqli_query($mysqli, "SELECT * FROM user") or die(mysqli_error($mysqli));
					while($player = mysqli_fetch_assoc($get_users)) {
						$ins1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('".$sender."', '".$player['username']."', '".$message."')") or die(mysqli_error($mysqli));
			           output("You're message was sent!");
					}
				} else {
					$reciver_user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='$receiver'") or die (mysqli_error($mysqli));
				$reciver_user = mysqli_fetch_assoc($reciver_user_get);
					$ins1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('".$sender."', '".$receiver."', '".$message."')") or die(mysqli_error($mysqli));
					$ins1 = mysqli_query($mysqli, "INSERT INTO notification (userid, page, message) VALUES ('".$reciver_user['id']."', 'kingdom', '".$sender." has sent you a message!')") or die(mysqli_error($mysqli));
			       output("You're message was sent!");
				}
			}
		}
		if (!empty($_POST['delete'])) {
			
			$id = protect($_POST['id']);
			$delete1 = mysqli_query($mysqli, "DELETE FROM messages WHERE id='".$id."'") or die(mysqli_error($mysqli));
		}
		
	?>
	
	<b>Your Inbox</b>
	<br /><hr /><br /><br />
	<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<tr><thead>
			<th><b>Sender</b></th>
			<th><b>Message</b></th>
            <th><b>Actions</b></th>
		</thead></tr><tbody>
		<?php
			$get_messages = mysqli_query($mysqli, "SELECT * FROM messages") or die(mysqli_error($mysqli));
			while($message = mysqli_fetch_assoc($get_messages)) {
				if ($user['username'] == $message['receiver']) {
					echo "<tr>";
					echo "<td>" . $message['sender'] . "</td>";
					echo "<td>" . $message['message'] . "</td>";
					?>
					<form action="inbox.php" method="post">
						<input type="hidden" name="id" value="<?php echo $message['id']; ?>"/>
						<td><input type="submit" name="delete" value="Delete"/></td>
					</form>
					<?php
					echo "</tr>";
				}
			}
		?>
	</tbody></table></div>
	
	<br /><br />
	<b>Send Message</b>
	<br /><hr /><br /><br />
	<form action="inbox" method="post">
		To: <input type="text" name="receiver" autocomplete="off"/>
		Message: <input type="text" name="message" autocomplete="off"/>
		<?php if ($user['username'] == $admin['name']) { echo "Send As:<input type='text' name='sendas' autocomplete='off'/>"; } ?>
		<input type="submit" name="send" value="Send"/>
	</form>
	
	<?php
	
	}
	
	include ("./template/footer.php");
?>	