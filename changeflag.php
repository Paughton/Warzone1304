<?php

	$page = "changeflag";
	include("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		if (!empty($_POST['flagchange'])) {
			
			$flag = protect($_POST['flag']);
			$update_flag = mysqli_query($mysqli, "UPDATE user SET flag='".$flag."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
			output("Your Flag Has Changed!");
			
		}
		
		?>
		
			<script>
				function select_img() {
					var flag = document.getElementById("flag").value;
					document.getElementById("flag_display").src="./flag/" + flag;
				}
			</script>
		
			<h1>Change Flag</h1>
			<hr />
			<img src="./flag/britonian.png" id="flag_display">
			<form action="changeflag.php" method="post" onchange="select_img()">
				Pick a Flag: <select name="flag" id="flag"><br />
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
			<input type="submit" name="flagchange" value="Change Flag"/>
			</form><br /><br />
			
			<?php
				if ($user['elite'] == 1) {
					?>
						<h1>Change Flag [Elite]</h1>
						<hr />
						<form action="upload" method="post" enctype="multipart/form-data">
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