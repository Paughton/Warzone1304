<?php

	$page = "research";
	include ("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} elseif ($user['level'] < 5) {
		output("You must be at least level V to research");
	} else {
		
		if(!empty($_POST['research'])) {
			$techid = protect($_POST['techid']);
			$cost = protect($_POST['cost']);
			
			if ($cost > $stats['gold']) {
				output("You do not have enough gold!");
			} else {
				
				if ($techid == 7 || $techid == 19) {
					$techtree['path'] = $techtree['techid'];
				}
				
				list ($techtree['display1'], $techtree['display2']) = get_nexttech($techid);
				$techtree['techid'] = $techid;
				$stats['gold'] -= $cost;
				$update_gold = mysqli_query($mysqli, "UPDATE stats SET gold='".$stats['gold']."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				$updage_techtree = mysqli_query($mysqli, "UPDATE techtree SET techid='".$techid."',
																			  display1='".$techtree['display1']."',
																			  display2='".$techtree['display2']."',
																			  path='".$techtree['path']."'
																			  WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
				output("Research was successful!");
			}
			
		}
		
		?>
		
		<h1>Research</h1>
		<hr />
		<p>You <b>must</b> complete the entire tech tree to receive its bonuses! Current amount of Technologies :: 6.</p><br /><br />
		
		<?php
		
		if ($techtree['techid'] == 0 && $techtree['display1'] == 0 && $techtree['display2'] == 0) {
			display_research(0, $worker['mine']);
		} elseif ($techtree['techid'] == 7 || $techtree['techid'] == 19) {
			output("You have researched everything already!");
		} else {
			display_research($techtree['display1'], $worker['mine']);
			if ($techtree['display2'] != 0) {
				display_research($techtree['display2'], $worker['mine']);
			}
		}
	
	}
	include ("./template/footer.php");
?>