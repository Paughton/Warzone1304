<?php
	$page = "register";
	include("./template/header.php");
	if (!empty($_SESSION['uid'])) {
		output("You already have an account!");
	} else {
?>

<?php
	if (!empty($_POST['register'])) {
		$username = protect($_POST['username']);
		$leader = protect($_POST['leader']);
		$terrain = protect($_POST['terrain']);
		$flag = protect($_POST['flag']);
		$password = protect($_POST['password']);
		$confirmpassword = protect($_POST['confirmpassword']);
		$email = protect($_POST['email']);
		$confirmemail = protect($_POST['confirmemail']);
		
		if ($username == "" || $password == "" || $email == "" || $leader == "" || $confirmpassword == "" || $confirmemail == "") {
			output("Please supply all the fields!");
		} elseif(strlen($username) > 20) {
			output("Stronghold Name must be less than 20 characters!");
		} elseif(strlen($leader) > 20) {
			output("Leader Name must be less than 20 characters!");
		} elseif(strlen($email) > 100) {
			output("E-mail must be less than 100 characters!");
		} elseif(strlen($password) > 32) {
			output("E-mail must be less than 32 characters!");
		} elseif ($password != $confirmpassword) {
			output("Passwords do not match!");
		} elseif ($email != $confirmemail) {
			output("E-mails do not match!");
		} elseif (empty($_POST['age'])) {
			output("Please check the following box: \"I am 13 years of age or older\"!");
		} elseif (empty($_POST['termsofservice'])) {
			output("Please accept our Terms of Service!");
		} else {
			$register1 = mysqli_query($mysqli, "SELECT id FROM user WHERE username='$username'") or die(mysqli_error($mysqli));
			$register2 = mysqli_query($mysqli, "SELECT id FROM user WHERE email='$email'") or die(mysqli_error($mysqli));
			if (mysqli_num_rows($register1) > 0) {
				output("That username is already in use!");
			} elseif (mysqli_num_rows($register2) > 0) {
				output("That e-mail address is already in use!");
			} else {
				$ins1 = mysqli_query($mysqli, "INSERT INTO stats (gold, iron, stone, lumber, wheat, flour, apples, bread, cheese, meat) VALUES (25, 5, 75, 125, 5, 0, 50, 0, 0, 15)") or die(mysqli_error($mysqli));
				$ins2 = mysqli_query($mysqli, "INSERT INTO unit (worker, footman, archer, knight, horse, rifleman, pikeman, trebuchet, batteringram, siegetower, catapult, moat, murderhole, battlement, machicolation, general) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)") or die(mysqli_error($mysqli));
				$ins3 = mysqli_query($mysqli, "INSERT INTO user (username, password, email, lastlogin, elite, flag, level, land, terrain, leader, allianceid, blockade, account, inbox, active) VALUES ('$username', '".md5($password)."', '$email', '1', '0', '".$flag."', '1', '10000', '".$terrain."', '".$leader."', '0' , '0', '0', '0', '0')") or die(mysqli_error($mysqli));
				$ins4 = mysqli_query($mysqli, "INSERT INTO buildings (lumbermill, mine, hunterpost, barrack, stable, shootingrange, house, applefarm, dairyfarm, wheatfarm, mill, bakery, marketplace, wall, siegecamp, engineerworkshop) VALUES (0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)") or die(mysqli_error($mysqli));
				$ins5 = mysqli_query($mysqli, "INSERT INTO worker (lumbermill, mine, hunterpost, applefarm, dairyfarm, wheatfarm, mill, bakery) VALUES (0, 0, 0, 0, 0, 0, 0, 0)") or die(mysqli_error($mysqli));
				$ins6 = mysqli_query($mysqli, "INSERT INTO ranking (power, overall) VALUES (0, 0)") or die(mysqli_error($mysqli));
				$ins6 = mysqli_query($mysqli, "INSERT INTO techtree (techid, display1, display2, path) VALUES (0, 0, 0, 0)") or die(mysqli_error($mysqli));
				$ins7 = mysqli_query($mysqli, "INSERT INTO weapon (crossbow, longbow, sword, pike, armor, shield) VALUES (0, 0, 0, 0, 0, 0)") or die(mysqli_error($mysqli));
				$player_id_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$username."'") or die (mysqli_error($mysqli));
				$player_id = mysqli_fetch_assoc($player_id_get);
				$ins8 = mysqli_query($mysqli, "INSERT INTO notification (userid, page, message) VALUES ('".$player_id['id']."', 'kingdom', 'Thank you for registering to " . $title . ", if you would like to know how to play or like to know a great start to the game go to the tutorial section under Community!')") or die(mysqli_error($mysqli));
				$key = $username . $email . date('mY');
				$key = md5($key);
				$ins9 = mysqli_query($mysqli, "INSERT INTO confirm (userid, email, confirmkey) VALUES  ('".$player_id['id']."', '".$player_id['email']."', '".$key."')") or die(mysqli_error($mysqli));
				
				// The message
				$message = "Please copy the link below to confirm your email.\r\nWEBSITEADDRESS/confirm?key=" . $key;
				$message2 = "Hello, there is a new user that has registered into Warzone1304!\r\nThere username is, " . $username;

				// In case any of our lines are larger than 70 characters, we should use wordwrap()
				$message = wordwrap($message, 70, "\r\n");
				$message2 = wordwrap($message2, 70, "\r\n");
				
				mail($player_id['email'], $title . " Activation", $message);
				mail("YOUREMAILHERE@message.com", "New User!", $message2);
				
				output("You have registered! We have sent you an activation link to your e-mail.");
			}
		}
	}
?>

Register
<br /><br />

<script>
	function select_terrain() {
		var terrain = document.getElementById("terrain").value;
		
		if (terrain == "hill") {
			text = "Hills make all calvary half as effective.";
		} else if (terrain == "desert") {
			text = "Deserts make footman three times less effective, but food production is 30% less.";
		} else if (terrain == "moutain") {
			text = "Moutainous terrain boost all gold production by 25%.";
		} else if (terrain == "woodland") {
			text = "Woodlands increase all lumber production by 40%.";
		} else if (terrain == "grassland") {
			text = "Grasslands boost dairy farm prouction by 40%.";
		}
		
		document.getElementById("terrain_desc").innerHTML = text;
	}
	function select_img() {
		var flag = document.getElementById("flag").value;
		document.getElementById("flag_display").src="./flag/" + flag;
	}
</script>

<form action="register" method="POST">
	Stronghold Name: <input type="text" name="username" autocomplete="off"/><br />
	Leader Name: <input type="text" name="leader" autocomplete="off"/><br /><br /><br />
	<span id="terrain_desc">Hills make all calvary half as effective</span><br />
	Terrain: 	<select name="terrain" id="terrain" onchange="select_terrain()"><br />
					<option value="hill">Hills</option>
					<option value="desert">Desert</option>
					<option value="moutain">Mountainous</option>
					<option value="woodland">Woodlands</option>
					<option value="grassland">Grasslands</option>
				</select><br /><br />
	<img src="./flag/britonian.png" id="flag_display"><br />
	Pick a Flag: <select name="flag" id="flag" onchange="select_img();"><br />
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
				</select><br /><br /><br />
	Password: <input type="password" name="password" autocomplete="off"/><br />
	Confirm Password: <input type="password" name="confirmpassword" autocomplete="off"/><br /><br /><br />
	E-mail: <input type="text" name="email" autocomplete="off"/><br />
	Confirm E-mail: <input type="text" name="confirmemail" autocomplete="off"/><br /><br />
	<input type="checkbox" name="age"> I am 13 years of age or older.<br />
	<input type="checkbox" name="termsofservice"> I have read and accept the terms of service.<br /><br />
	<input type="submit" name="register" value="Register"/>
</form>

<?php
	}
	include("./template/footer.php");
?>