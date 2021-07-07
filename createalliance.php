<?php

	$page = "createalliance";
	include("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} elseif ($user['allianceid'] != 0) {
		output("You are already in an alliance!");
	} else {
		
		if (!empty($_POST['createalliance'])) {
			$name = protect($_POST['alliancename']);
			$flag = protect($_POST['flag']);
			
			if ($name == "") {
				output("Please supply all the fields!");
			} elseif(strlen($name) > 20) {
				output("Alliance Name must be no more than 20 characters!");
			} else {
				$register1 = mysqli_query($mysqli, "SELECT id FROM alliance WHERE name='$name'") or die(mysqli_error($mysqli));
				if (mysqli_num_rows($register1) > 0) {
					echo "That Alliance Name is already in use!";
				} else {
			
					$ins1 = mysqli_query($mysqli, "INSERT INTO alliance (name, president, flag, gold, iron, stone, lumber, goldtax, irontax, stonetax, lumbertax) VALUES ('".$name."', '".$user['username']."', '".$flag."', 0, 0, 0, 0, 0, 0, 0, 0)") or die(mysqli_error($mysqli));
					
					$alliance_get2 = mysqli_query($mysqli, "SELECT * FROM alliance WHERE name='".$name."'") or die(mysqli_error($mysqli));
					$alliance2 = mysqli_fetch_assoc($alliance_get2);
					
					$update_user = mysqli_query($mysqli, "UPDATE user SET allianceid='".$alliance2['id']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					output("Alliance was successfully created!");
				
				}
			}
		}
		
		?>
		
		<script>
			function select_img() {
				var flag = document.getElementById("flag").value;
				document.getElementById("flag_display").src="./flag/" + flag;
			}
		</script>
		
		<b>Create Alliance</b> <br /> <br />
		
		<img src="./flag/britonian.png" id="flag_display">
		<hr />
		<form action="createalliance" method="POST">
		Alliance Name: <input type="text" name="alliancename" autocomplete="off"/><br />
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
		<input type="submit" name="createalliance" value="Create Alliance"/>
		</form>
		
	<?php
		
	}

	include("./template/footer.php");
?>