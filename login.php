<?php
	
	$page = "login";
	include("./template/header.php");
	
	if(!empty($_POST['login'])) {
		if(!empty($_SESSION['uid'])) {
			echo "You are already logged in!";
		} else {
			$username = protect($_POST['username']);
			$password = protect($_POST['password']);
			
			$login_check = mysqli_query($mysqli, "SELECT id FROM user WHERE username='$username' AND password='".md5($password)."'") or die (mysqli_error($mysqli));
			if (mysqli_num_rows($login_check) == 0) {
				echo "Invalid Username/Password Combination";
			} else {
				$login_user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='$username' AND password='".md5($password)."'") or die (mysqli_error($mysqli));
				$login_user = mysqli_fetch_assoc($login_user_get);
				if ($login_user['account'] == 1) {
					output("Sorry but it seems that your account has been banned!");
				} elseif ($login_user['active'] == 0) {
					output("You need to activate your account!");
				} else {
					$get_id = mysqli_fetch_assoc($login_check);
					$_SESSION['uid'] = $get_id['id'];
				
					include("./system/config.php");
				
					if ($user['lastlogin'] == 0) {
						$stats['gold'] = round($stats['gold'] * 1.20);
						$stats['iron'] = round($stats['iron'] * 1.20);
						$update_user_login = mysqli_query($mysqli, "UPDATE user SET lastlogin=1 WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
						$update_stats_login = mysqli_query($mysqli, "UPDATE stats SET gold='".$stats['gold']."', iron='".$stats['iron']."' WHERE id='".$user['id']."'") or die(mysqli_error($mysqli));
					} else {
						// Do Nothing
					}
				
				    ?> <script>window.location = "kingdom?id=<?php echo $user['id'] ?>"</script> <?php
					//header("Location: kingdom?id=" . $user['id']);
				}
			}
		}
	}
	
	?>
	
	<form action="login" method="post">
		Stronghold Name: <input type="text" name="username" autocomplete="off"/><br />
		Password: <input type="password" name="password" autocomplete="off"/><br />
		<input type="submit" name="login" value="Login"/> or <a href="register">Register</a>
	</form>
	
	<?php
	
	include("./template/footer.php");
?>