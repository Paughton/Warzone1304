<?php
	$page = "account";
	include ("./template/header.php");
	
	if(empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
	
		if(!empty($_POST['changepassword'])) {
				$password =  protect($_POST['password']);
				$password_unprotect = md5($user['password']);
				$newpassword =  protect($_POST['newpassword']);
				$newpassword2 =  protect($_POST['newpassword2']);
				
				if (md5($password) != $user['password']) {
					output("That is the wrong password / that isn't your password");
				} elseif ($newpassword != $newpassword2) {
					ouptut("The passwords you have entered do not match!");
				} else {
					$update_email = mysqli_query($mysqli, "UPDATE user SET password='".md5($newpassword)."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					output("Your password has been changed!");
				}
				
		}
	
		if(!empty($_POST['changeemail'])) {
				$email =  protect($_POST['email']);
				$newemail =  protect($_POST['newemail']);
				$newemail2 =  protect($_POST['newemail2']);
			
			
				$eaccount1 = mysqli_query($mysqli, "SELECT id FROM user WHERE email='$newemail'") or die(mysqli_error($mysqli));
				if (mysqli_num_rows($eaccount1) > 0) {
					output("That email is already in use!");
				} elseif ($email != $user['email']) {
					output("That isn't your email!");
				} elseif ($newemail != $newemail2) {
					output("The emails you have entered do not match each other!");
				} else {
					$user['email'] = $newemail;
					$update_email = mysqli_query($mysqli, "UPDATE user SET email='".$user['email']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					output("E-mail changed from \"" . $email . "\" to \"" . $newemail . "\"");
				}
			
	}
	
		?>
	
		<center><h1>Your Account</h1></center>
		<b>Change Password</b><br /><br />
		<form action="account" method="POST">
			Current Password: <input type="password" name="password"/><br />
			New Password: <input type="password" name="newpassword"/><br />
			Confirm New Password: <input type="password" name="newpassword2"/><br />
		<input type="submit" name="changepassword" value="Change Password"/>
		</form><br /><hr /><br />
		
		<b>Change E-Mail</b><br /><br />
		<form action="account" method="POST">
			Current E-mail: <input type="text" name="email" autocomplete="off"/><br />
			New E-mail: <input type="text" name="newemail" autocomplete="off"/><br />
			Confirm New E-mail: <input type="text" name="newemail2" autocomplete="off"/><br />
		<input type="submit" name="changeemail" value="Change E-mail"/>
		</form>
	
		<?php
	
	}
	
	include ("./template/footer.php");
?>