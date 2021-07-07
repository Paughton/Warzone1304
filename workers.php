<?php

	$page = "workers";
	include ("./template/header.php");
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		
		$food_consumption = $unit['worker'] + $worker['mine'] + $worker['hunterpost'] + $worker['applefarm'] + $worker['dairyfarm'] + $worker['lumbermill'] + $worker['wheatfarm'] + $worker['mill'] + $worker['bakery'];
		$food_consumption += $worker['bowyerworkshop'] + $worker['weaponsmithery'] + $worker['armory'];
		$food_consumption += ($unit['footman'] * 2) + ($unit['archer'] * 2) + ($unit['knight'] * 2) + ($unit['rifleman'] * 2);
		
		$food_producing = $worker['hunterpost'] * 3;
		$food_producing += $worker['applefarm'] * 5;
		$food_producing += $worker['dairyfarm'] * 6;
		
		if ($techtree['path'] == 18) {
			$capcity = 2;
		} else {
			$capcity = 1;
		}
		
		$cheese_mod = 1;
		$lumber_mod = 1;
		$gold_mod   = 1;
		if ($user['terrain'] == "grassland") {
			$cheese_mod = 1.4;
		} elseif ($user['terrain'] == "woodland") {
			$lumber_mod = 1.4;
		} elseif ($user['terrain'] == "mountain") {
			$gold_mod   = 1.25;
		}
		
		$wheat_production = $stats['wheat'] + $worker['wheatfarm'] * 3;
		if ($wheat_production >= $worker['mill'] * 3) {
			$flour_production = $stats['flour'] + ($worker['mill'] * 3)*3;
		} else {
			$flour_production = $stats['flour'] + ($wheat_production * 3)*3;
		}
		if ($flour_production >= $worker['bakery'] * 4) {
			$bread_production = ($worker['bakery'] * 4)*9;
		} else {
			$bread_production = ($flour_production * 4)*9;
		}
		$food_producing += $bread_production;
		
		$sword_display    = $worker['weaponsmithery'];
		$pike_display     = $worker['weaponsmithery'];
		$longbow_display  = $worker['bowyerworkshop'];
		$crossbow_display = $worker['bowyerworkshop'];
		$armor_display    = $worker['weaponsmithery'];
		$shield_display   = $worker['armory'];
		
		$wheat_display = $wheat_production - $stats['wheat'];
		$flour_display = $flour_production - $stats['flour'];
		$bread_display = $bread_production;
		
		if(!empty($_POST['assign'])) {
			$lumbermill =  protect($_POST['lumbermill']);
			$mine =  protect($_POST['mine']);
			$hunterpost =  protect($_POST['hunterpost']);
			$applefarm =  protect($_POST['applefarm']);
			$dairyfarm =  protect($_POST['dairyfarm']);
			$wheatfarm =  protect($_POST['wheatfarm']);
			$mill =  protect($_POST['mill']);
			$bakery =  protect($_POST['bakery']);
			$bowyerworkshop = protect($_POST['bowyerworkshop']);
			$weaponsmithery = protect($_POST['weaponsmithery']);
			$armory = protect($_POST['armory']);
			
			if ($lumbermill < 0 || $mine < 0 || $hunterpost < 0 || $applefarm < 0 || $dairyfarm < 0 || $wheatfarm < 0 || $mill < 0 || $bakery < 0 || $bowyerworkshop < 0 || $weaponsmithery < 0 || $armory < 0) {
				output("You must train a positive number of workers!");
			} elseif ($unit['worker'] < $lumbermill + $mine + $hunterpost + $applefarm + $dairyfarm + $wheatfarm + $mill + $bakery) { 
				output("You need more citizens!");
			} elseif ($lumbermill > (($buildings['lumbermill'] * 10)*$capcity-($worker['lumbermill']))) { output("You need more lumber mills!");
			} elseif ($mine > (($buildings['mine'] * 10*$capcity)-($worker['mine']))) { output("You need more mines!");
			} elseif ($hunterpost > (($buildings['hunterpost'] * 10)*$capcity-($worker['hunterpost']))) { output("You need more hunter's posts!");
			} elseif ($applefarm > (($buildings['applefarm'] * 10)*$capcity-($worker['applefarm']))) { output("You need more apple farms!");
			} elseif ($dairyfarm > (($buildings['dairyfarm'] * 10)*$capcity-($worker['dairyfarm']))) { output("You need more dairy farms!");
			} elseif ($wheatfarm > (($buildings['wheatfarm'] * 10)*$capcity-($worker['wheatfarm']))) { output("You need more wheat farms!");
			} elseif ($bowyerworkshop > (($buildings['bowyerworkshop'] * 10)*$capcity-($worker['bowyerworkshop']))) { output("You need more bowyer's Workshops!");
			} elseif ($weaponsmithery > (($buildings['weaponsmithery'] * 10)*$capcity-($worker['weaponsmithery']))) { output("You need more weaponsmitheries!");
			} elseif ($armory > (($buildings['armory'] * 10)*$capcity-($worker['armory']))) { output("You need more armories!");
			} elseif ($mill > (($buildings['mill'] * 10)*$capcity-($worker['mill']))) { output("You need more mills!");
			} elseif ($bakery > (($buildings['bakery'] * 10)*$capcity-($worker['bakery']))) { 
				output("You need more bakeries!");
			} else {
				$worker['lumbermill'] += $lumbermill;
				$worker['mine'] += $mine;
				$worker['hunterpost'] += $hunterpost;
				$worker['applefarm'] += $applefarm;
				$worker['dairyfarm'] += $dairyfarm;
				$worker['wheatfarm'] += $wheatfarm;
				$worker['mill'] += $mill;
				$worker['bakery'] += $bakery;
				$worker['bowyerworkshop'] += $bowyerworkshop;
				$worker['weaponsmithery'] += $weaponsmithery;
				$worker['armory'] += $armory;
				
				$unit['worker'] -= $lumbermill + $mine + $hunterpost + $applefarm + $dairyfarm + $wheatfarm + $mill + $bakery + $bowyerworkshop + $weaponsmithery + $armory;
				$update_units = mysqli_query($mysqli, "UPDATE unit SET worker='".$unit['worker']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				
				$update_workers = mysqli_query($mysqli, "UPDATE worker SET
														lumbermill='".$worker['lumbermill']."',
														mine='".$worker['mine']."',
														hunterpost='".$worker['hunterpost']."',
														applefarm='".$worker['applefarm']."',
														dairyfarm='".$worker['dairyfarm']."',
														wheatfarm='".$worker['wheatfarm']."',
														mill='".$worker['mill']."',
														bakery='".$worker['bakery']."',
														bowyerworkshop='".$worker['bowyerworkshop']."',
														weaponsmithery='".$worker['weaponsmithery']."',
														armory='".$worker['armory']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				
				
				output("You have assigned your citizens!");
					
			}
			
		} elseif (!empty($_POST['unassign'])) {
			$lumbermill =  protect($_POST['lumbermill']);
			$mine =  protect($_POST['mine']);
			$hunterpost =  protect($_POST['hunterpost']);
			$applefarm =  protect($_POST['applefarm']);
			$dairyfarm =  protect($_POST['dairyfarm']);
			$wheatfarm =  protect($_POST['wheatfarm']);
			$mill =  protect($_POST['mill']);
			$bakery =  protect($_POST['bakery']);
			$bowyerworkshop = protect($_POST['bowyerworkshop']);
			$weaponsmithery = protect($_POST['weaponsmithery']);
			$armory = protect($_POST['armory']);
			
			if ($lumbermill < 0 || $mine < 0 || $hunterpost < 0 || $applefarm < 0 || $dairyfarm < 0 || $wheatfarm < 0 || $mill < 0 || $bakery < 0 || $bowyerworkshop < 0 || $weaponsmithery < 0 || $armory < 0) {
				output("You must train a positive number of workers!");
			} elseif ($worker['lumbermill'] < $lumbermill) { output("You need more lumberjacks!");
			} elseif ($worker['mine'] < $mine) { output("You need more miners!");
			} elseif ($worker['hunterpost'] < $hunterpost) { output("You need more hunters!");
			} elseif ($worker['applefarm'] < $applefarm) { output("You need more apple farmers!");
			} elseif ($worker['dairyfarm'] < $dairyfarm) { output("You need more dairy farmers!");
			} elseif ($worker['wheatfarm'] < $wheatfarm) { output("You need more wheat farmers!");
			} elseif ($worker['mill'] < $mill) { output("You need more millers!");
			} elseif ($worker['bakery'] < $bakery) { output("You need more bakers!");
			} elseif ($worker['bowyerworkshop'] < $bowyerworkshop) { output("You need more bowyers!");
			} elseif ($worker['weaponsmithery'] < $weaponsmithery) { output("You need more weaponsmiths!");
			} elseif ($worker['armory'] < $armory) { output("You need more armorers!");
			} else {
				$worker['lumbermill'] -= $lumbermill;
				$worker['mine'] -= $mine;
				$worker['hunterpost'] -= $hunterpost;
				$worker['applefarm'] -= $applefarm;
				$worker['dairyfarm'] -= $dairyfarm;
				$worker['wheatfarm'] -= $wheatfarm;
				$worker['mill'] -= $mill;
				$worker['bakery'] -= $bakery;
				$worker['bowyerworshop'] -= $bowyerworkshop;
				$worker['weaponsmithery'] -= $weaponsmithery;
				$worker['armory'] -= $armory;
				
				$unit['worker'] += $lumbermill + $mine + $hunterpost + $applefarm + $dairyfarm + $wheatfarm + $mill + $bakery + $bowyerworkshop + $weaponsmithery + $armory;
				$update_units = mysqli_query($mysqli, "UPDATE unit SET worker='".$unit['worker']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				
				$update_workers = mysqli_query($mysqli, "UPDATE worker SET
														lumbermill='".$worker['lumbermill']."',
														mine='".$worker['mine']."',
														hunterpost='".$worker['hunterpost']."',
														applefarm='".$worker['applefarm']."',
														dairyfarm='".$worker['dairyfarm']."',
														wheatfarm='".$worker['wheatfarm']."',
														mill='".$worker['mill']."',
														bakery='".$worker['bakery']."',
														bowyerworkshop='".$worker['bowyerworshop']."',
														weaponsmithery='".$worker['weaponsmithery']."',
														armory='".$worker['armory']."'
														WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				
				
				output("You have unassigned your citizens!");
					
			}
			
		}
		?>
			<b>Assign Workers / Your Workers</b>
			<br /><hr />
			<img id="shop" src="./art/citizen.png">Citizens: <i><?php echo number_format($unit['worker'])?></i><br /><br />
			Current Food Consumption: <i><?php echo number_format($food_consumption) . "</i> / <i>" . number_format($food_producing); ?></i><br /><br />
			<form action="workers" method="post">
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th></th>
					<th><b>Building Name</b></th>
					<th><b>Workers</b></th>
					<th><b>Efficiency</b></th>
					<th><b>Assign More</b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><img id="building" src="./art/lumbermill.png"></td>
					<td>Lumber Mill</td>
					<td><?php echo "<b>" . number_format($worker['lumbermill']) . "</b>" . " / " . "<b>" . number_format($buildings['lumbermill']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($worker['lumbermill'] * 4 * $lumber_mod) . " <img id='shop' src='./art/lumber.png'> per turn" ?></td>
					<td><input type="text" name="lumbermill" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/mine.png"></td>
					<td>Mine</td>
					<td><?php echo "<b>" . number_format($worker['mine']) . "</b>" . " / " . "<b>" . number_format($buildings['mine']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($worker['mine'] * 3) . " <img id='shop' src='./art/stone.png'>, " . number_format($worker['mine'] * 2) . " <img id='shop' src='./art/iron.png'>, and " . number_format($worker['mine'] * $gold_mod) . " <img id='shop' src='./art/gold.png'> per turn" ?></td>
					<td><input type="text" name="mine" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/hunterpost.png"></td>
					<td>Hunter's Post</td>
					<td><?php echo "<b>" . number_format($worker['hunterpost']) . "</b>" . " / " . "<b>" . number_format($buildings['hunterpost']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($worker['hunterpost'] * 3) . " <img id='shop' src='./art/meat.png'> per turn" ?></td>
					<td><input type="text" name="hunterpost" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/applefarm.png"></td>
					<td>Apple Farm</td>
					<td><?php echo "<b>" . number_format($worker['applefarm']) . "</b>" . " / " . "<b>" . number_format($buildings['applefarm']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($worker['applefarm'] * 5) . " <img id='shop' src='./art/apple.png'> per turn" ?></td>
					<td><input type="text" name="applefarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/dairyfarm.png"></td>
					<td>Dairy Farm</td>
					<td><?php echo "<b>" . number_format($worker['dairyfarm']) . "</b>" . " / " . "<b>" . number_format($buildings['dairyfarm']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($worker['dairyfarm'] * 6 * $cheese_mod) . " <img id='shop' src='./art/cheese.png'> per turn" ?></td>
					<td><input type="text" name="dairyfarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/wheatfarm.png"></td>
					<td>Wheat Farm</td>
					<td><?php echo "<b>" . number_format($worker['wheatfarm']) . "</b>" . " / " . "<b>" . number_format($buildings['wheatfarm']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($wheat_display) . " <img id='shop' src='./art/wheat.png'> per turn" ?></td>
					<td><input type="text" name="wheatfarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/mill.png"></td>
					<td>Mill</td>
					<td><?php echo "<b>" . number_format($worker['mill']) . "</b>" . " / " . "<b>" . number_format($buildings['mill']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($flour_display) . " <img id='shop' src='./art/flour.png'> per turn" ?></td>
					<td><input type="text" name="mill" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/bakery.png"></td>
					<td>Bakery</td>
					<td><?php echo "<b>" . number_format($worker['bakery']) . "</b>" . " / " . "<b>" . number_format($buildings['bakery']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($bread_display) . " <img id='shop' src='./art/bread.png'> per turn" ?></td>
					<td><input type="text" name="bakery" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/bowyerworkshop.png"></td>
					<td>Bowyer's Workshop</td>
					<td><?php echo "<b>" . number_format($worker['bowyerworkshop']) . "</b>" . " / " . "<b>" . number_format($buildings['bowyerworkshop']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($longbow_display) . " <img id='shop' src='./art/longbow.png'> and " .number_format($crossbow_display). " <img id='shop' src='./art/crossbow.png'> per turn" ?></td>
					<td><input type="text" name="bowyerworkshop" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/weaponsmithery.png"></td>
					<td>Weaponsmithery</td>
					<td><?php echo "<b>" . number_format($worker['weaponsmithery']) . "</b>" . " / " . "<b>" . number_format($buildings['weaponsmithery']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($sword_display) . " <img id='shop' src='./art/sword.png'>, " .number_format($pike_display). " <img id='shop' src='./art/pike.png'>, and " .number_format($shield_display). " <img id='shop' src='./art/shield.png'> per turn" ?></td>
					<td><input type="text" name="weaponsmithery" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/armory.png"></td>
					<td>Armory</td>
					<td><?php echo "<b>" . number_format($worker['armory']) . "</b>" . " / " . "<b>" . number_format($buildings['armory']*10)*$capcity . "</b>"; ?></td>
					<td><?php echo "Your workers are producing " . number_format($armor_display) . " <img id='shop' src='./art/armor.png'> per turn" ?></td>
					<td><input type="text" name="armory" /></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input type="submit" name="assign" value="Assign"/></td>
				</tr>
				</tbody>
			</table>
			</form>
			</div>
			
			<br />
			<b>Unassign Workers / Your Workers</b>
			<br /><hr />
			<form action="workers" method="post">
			<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
				<tr>
					<th></th>
					<th><b>Building Name</b></th>
					<th><b>Number of Workers Assigned</b></th>
					<th><b>Unassign More</b></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><img id="building" src="./art/lumbermill.png"></td>
					<td>Lumber Mill</td>
					<td><?php echo "<b>" . number_format($worker['lumbermill']) . "</b>" . " / " . "<b>" . number_format($buildings['lumbermill']*10) . "</b>"; ?></td>
					<td><input type="text" name="lumbermill" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/mine.png"></td>
					<td>Mine</td>
					<td><?php echo "<b>" . number_format($worker['mine']) . "</b>" . " / " . "<b>" . number_format($buildings['mine']*10) . "</b>"; ?></td>
					<td><input type="text" name="mine" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/hunterpost.png"></td>
					<td>Hunter's Post</td>
					<td><?php echo "<b>" . number_format($worker['hunterpost']) . "</b>" . " / " . "<b>" . number_format($buildings['hunterpost']*10) . "</b>"; ?></td>
					<td><input type="text" name="hunterpost" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/applefarm.png"></td>
					<td>Apple Farm</td>
					<td><?php echo "<b>" . number_format($worker['applefarm']) . "</b>" . " / " . "<b>" . number_format($buildings['applefarm']*10) . "</b>"; ?></td>
					<td><input type="text" name="applefarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/dairyfarm.png"></td>
					<td>Dairy Farm</td>
					<td><?php echo "<b>" . number_format($worker['dairyfarm']) . "</b>" . " / " . "<b>" . number_format($buildings['dairyfarm']*10) . "</b>"; ?></td>
					<td><input type="text" name="dairyfarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/wheatfarm.png"></td>
					<td>Wheat Farm</td>
					<td><?php echo "<b>" . number_format($worker['wheatfarm']) . "</b>" . " / " . "<b>" . number_format($buildings['wheatfarm']*10) . "</b>"; ?></td>
					<td><input type="text" name="wheatfarm" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/mill.png"></td>
					<td>Mill</td>
					<td><?php echo "<b>" . number_format($worker['mill']) . "</b>" . " / " . "<b>" . number_format($buildings['mill']*10) . "</b>"; ?></td>
					<td><input type="text" name="mill" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/bakery.png"></td>
					<td>Bakery</td>
					<td><?php echo "<b>" . number_format($worker['bakery']) . "</b>" . " / " . "<b>" . number_format($buildings['bakery']*10) . "</b>"; ?></td>
					<td><input type="text" name="bakery" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/bowyerworkshop.png"></td>
					<td>Bowyer's Workshop</td>
					<td><?php echo "<b>" . number_format($worker['bowyerworkshop']) . "</b>" . " / " . "<b>" . number_format($buildings['bowyerworkshop']*10)*$capcity . "</b>"; ?></td>
					<td><input type="text" name="bowyerworkshop" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/weaponsmithery.png"></td>
					<td>Weaponsmithery</td>
					<td><?php echo "<b>" . number_format($worker['weaponsmithery']) . "</b>" . " / " . "<b>" . number_format($buildings['weaponsmithery']*10)*$capcity . "</b>"; ?></td>
					<td><input type="text" name="weaponsmithery" /></td>
				</tr>
				<tr>
					<td><img id="building" src="./art/armory.png"></td>
					<td>Armory</td>
					<td><?php echo "<b>" . number_format($worker['armory']) . "</b>" . " / " . "<b>" . number_format($buildings['armory']*10)*$capcity . "</b>"; ?></td>
					<td><input type="text" name="armory" /></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><input type="submit" name="unassign" value="Unassign"/></td>
				</tr>
				</tbody>
			</table>
			</form>
			</div>
			<hr />
		<?php
	}
	
	include ("./template/footer.php");
?>