<?php

function protect($string) {
	//return real_escape_string(strip_tags(addslashes($string)));
	include("config.php");
	return mysqli_real_escape_string($mysqli, strip_tags(addslashes($string)));
}

function output($string) {
	echo "<div class=\"output\">" . $string . "</div>";
}

function numeral_format($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

/* Research Stuff */

function display_research($id, $worker) {
	
	$title = "404 Error";
	$text = "That Research does not Exist!";
	$cost = "No Cost Found";
	
	switch ($id) {
		case 0:
			$title = "Calculations I";
			$text = "Allows you to start researching technologies";
			$cost = 0;
			break;
		
		/* Military Research */
		case 1:
			$title = "Improved Training I";
			$text = "Increases the efficiency of your Footman by 2%.";
			$cost = round($worker);
			break;
			
		case 2:
			$title = "Sharpened Pikes I";
			$text = "Increases the efficiency of your Pikeman by 2%.";
			$cost = round($worker);
			break;
			
		case 3:
			$title = "Longbow Attachments I";
			$text = "Increases the efficiency of your Archers by 2%.";
			$cost = round($worker);
			break;
			
		case 4:
			$title = "Improved Training II";
			$text = "Increases the efficiency of your Footman and your Pikeman by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 5:
			$title = "Calculations II";
			$text = "Increases the efficiency of your Pikeman and your Archers by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 6:
			$title = "Barracks Fortifications I";
			$text = "Doubles the amount of space in each Barracks.";
			$cost = round($worker * 3);
			break;
			
		case 7:
			$title = "Accounting I";
			$text = "Military costs are cut in half.";
			$cost = round($worker * 4);
			break;
			
		case 8:
			$title = "Advance Training I";
			$text = "Increases the efficiency of your Knights by 2%.";
			$cost = round($worker);
			break;
			
		case 9:
			$title = "Matchlock Modifications I";
			$text = "Increases the efficiency of your Rifleman by 2%.";
			$cost = round($worker);
			break;
			
		case 10:
			$title = "Advance Training II";
			$text = "Increases the efficiency of your Knights by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 11:
			$title = "Matchlock Modifications II";
			$text = "Increases the efficiency of your Knights by 2%.";
			$cost = round($worker * 2);
			break;
		
		case 12:
			$title = "Shooting Range Fortifications";
			$text = "Doubles the space of your Stables and Shooting Ranges.";
			$cost = round($worker * 3);
			break;
			
		/* Building Research */
		case 13:
			$title = "Improved Technology I";
			$text = "Increases the efficiency of your Lumber Mills by 2%.";
			$cost = round($worker);
			break;
			
		case 14:
			$title = "Improved Digging Strategies I";
			$text = "Increases the efficiency of your Mines by 2%.";
			$cost = round($worker);
			break;
			
		case 15:
			$title = "Improved Technology II";
			$text = "Increases the efficiency of your Lumber Mills by 2%.";
			$cost = round($worker);
			break;
			
		case 16:
			$title = "Lighter Wind Blades I";
			$text = "Increases the efficiency of your Mill by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 17:
			$title = "Improved Wells I ";
			$text = "Increases the efficiency of your Wheat Farms by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 18:
			$title = "Production Increase I";
			$text = "Production building's worker cap is increased by 100%.";
			$cost = round($worker * 3);
			break;
			
		case 19:
			$title = "Compact Packing I";
			$text = "Houses now provide 16 instead of 8 citizens.";
			$cost = round($worker * 4);
			break;
			
		case 20:
			$title = "Improved Hunting Tactics I";
			$text = "Increases the efficiency of your Hunter's Posts by 2%.";
			$cost = round($worker);
			break;
			
		case 21:
			$title = "Fertile Soil I";
			$text = "Increases the efficiency of your Apple Farms by 2%.";
			$cost = round($worker);
			break;
			
		case 22:
			$title = "Cheese Improvements I";
			$text = "Increases the efficiency of your Dairy Farms by 2%.";
			$cost = round($worker * 2);
			break;
			
		case 23:
			$title = "Advance Ovens I";
			$text = "Increases the efficiency of your Bakeries by 2%.";
			$cost = round($worker * 2);
			break;
		
		case 24:
			$title = "Iron Stomach I";
			$text = "Military units now only eat one unit of food instead of two.";
			$cost = round($worker * 3);
			break;
			
	}
	
	include ("config.php");
	$cost += round(0.002*($user['land']) + pow($user['level'], 5));
	
	?>
	
		<div class="upgrade">
			<div class="title">
				<?php echo $title ?>
			</div>
			<div class="text">
				<?php echo $text ?>
			</div>
			<div class="cost">
				<?php echo $cost ?> <img id="shop" src="./art/gold.png">
				<form action="research.php" method="post">
					<input type="hidden" name="techid" value="<?php echo $id; ?>"/>
					<input type="hidden" name="cost" value="<?php echo $cost; ?>"/>
					<input type="submit" name="research" value="Research"/>
				</form>
			</div>
		</div>
	
	<?php
	
}

function get_nexttech($id) {
	$display1 = 0;
	$display2 = 0;
	switch ($id) {
		case 0:
			$display1 = 1;
			$display2 = 13;
			break;
			
		/* Military Research */
		case 1:
			$display1 = 2;
			$display2 = 8;
			break;
			
		case 2:
			$display1 = 3;
			break;
			
		case 3:
			$display1 = 4;
			$display2 = 5;
			break;
			
		case 4:
			$display1 = 6;
			break;
			
		case 5:
			$display1 = 6;
			break;
			
		case 6:
			$display1 = 7;
			break;
			
		case 7:
			/* End of Military Side */
			break;
			
		case 8:
			$display1 = 9;
			break;
		
		case 9:
			$display1 = 10;
			$display2 = 11;
			break;
			
		case 10:
			$display1 = 12;
			break;
			
		case 11:
			$display1 = 12;
			break;
			
		case 12:
			$display1 = 7;
			break;
			
		/* Building Research */
		case 13:
			$display1 = 14;
			$display2 = 20;
			break;
			
		case 14:
			$display1 = 15;
			break;
			
		case 15:
			$display1 = 16;
			$display2 = 17;
			break;
			
		case 16:
			$display1 = 18;
			break;
			
		case 17:
			$display1 = 18;
			break;
			
		case 18:
			$display1 = 19;
			break;
			
		case 19:
			/* End of Buildings Side */
			break;
			
		case 20:
			$display1 = 21;
			break;
		
		case 21:
			$display1 = 22;
			$display2 = 23;
			break;
			
		case 22:
			$display1 = 24;
			break;
			
		case 23:
			$display1 = 24;
			break;
			
		case 24:
			$display1 = 19;
			break;
	}
	
	return array ($display1, $display2);
	
}

?>