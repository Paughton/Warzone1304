<?php
	$page = "marketplace";
	include("./template/header.php");
	
	$trade_amount = 0;
	
	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} elseif ($user['blockade'] == 1) {
		output("You can not trade while being blockaded!");
	} else {
		
		
		if(!empty($_POST['buy'])) {
			$id = protect($_POST['id']);
			$seller = protect($_POST['seller']);
			$item = protect($_POST['item']);
			$itemamount = protect($_POST['itemamount']);
			$price = protect($_POST['price']);
			$priceamount = protect($_POST['priceamount']);
			
			if($user['username'] == $seller) {
				output("You cannot buy your own trades!");
			} elseif ($stats[$price] >= $priceamount) {
				$stats[$price] -= $priceamount;
				$stats[$item] += $itemamount;
				
				$user_get = mysqli_query($mysqli, "SELECT * FROM user WHERE username='".$seller."'") or die(mysqli_error($mysqli));
				$user_data = mysqli_fetch_assoc($user_get);
				
				$user_stats_get = mysqli_query($mysqli, "SELECT * FROM stats WHERE id='".$user_data['id']."'") or die(mysqli_error($mysqli));
				$user_stats = mysqli_fetch_assoc($user_stats_get);
				
				$user_stats[$price] += $priceamount;
				$user_stats[$item] -= $itemamount;
				
				if ($user_stats[$item] >= 0) {
					$message = $user['username'] . " has bought your " . $itemamount . " " . $item . " for " . $priceamount . " " . $price . ".";
					$insert1 = mysqli_query($mysqli, "INSERT INTO messages (sender, receiver, message) VALUES ('Trader Logs', '".$seller."', '".$message."')") or die(mysqli_error($mysqli));
					$delete2 = mysqli_query($mysqli, "DELETE FROM marketplace WHERE id='".$id."'") or die(mysqli_error($mysqli));
					$update_stats1 = mysqli_query($mysqli, "UPDATE stats SET ".$price."='".$stats[$price]."', ".$item."='".$stats[$item]."' WHERE id='".$_SESSION['uid']."'") or die(mysqli_error($mysqli));
					$update_stats2 = mysqli_query($mysqli, "UPDATE stats SET ".$price."='".$user_stats[$price]."', ".$item."='".$user_stats[$item]."' WHERE id='".$user_stats['id']."'") or die(mysqli_error($mysqli));
					output("Trade was Successful");
				} else {
					output("Uh-Oh! The player you are attempting to trade with won't be able to pay you back. Try some other time!");
				}
				
			} else {
				output("You need more " . $price . "!");
			}
			
		}
		if (!empty($_POST['delete'])) {
			
			$id = protect($_POST['id']);
			$delete1 = mysqli_query($mysqli, "DELETE FROM marketplace WHERE id='".$id."'") or die(mysqli_error($mysqli));
		}
		
		if (!empty($_POST['trade'])) {
			
			$seller = $user['username'];
			$item = protect($_POST['item']);
			$itemamount = protect($_POST['itemamount']);
			$price = protect($_POST['price']);
			$priceamount = protect($_POST['priceamount']);
			
			if ($itemamount < 1 || $priceamount < 1) {
				output("You have to put in a number greater than 0!");
			} elseif ($buildings['marketplace'] - $trade_amount < 1) { 
				output("You need to have more marketplaces to put that trade on the marketplace!");
			} elseif ($stats[$item] < $itemamount) { 
				output("You need more " . $item . " to create that trade!");
			} else {
				$trade1 = mysqli_query($mysqli, "INSERT INTO marketplace (seller, item, itemamount, price, priceamount) VALUES ('".$user['username']."', '".$item."', '".$itemamount."', '".$price."', '".$priceamount."')") or die(mysqli_error($mysqli));
			}
		}
		
	?>
	
	<b>Marketplace</b>
	<br /><hr /><br /><br />
	<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<tr><thead>
			<th><b>Trade</b></th>
            <th><b>Actions</b></th>
		</thead></tr><tbody>
		<?php
			$get_marketplace = mysqli_query($mysqli, "SELECT * FROM marketplace") or die(mysqli_error($mysqli));
			while($marketplace = mysqli_fetch_assoc($get_marketplace)) {
				if ($user['username'] == $marketplace['seller']) {
					$trade_amount += 1;
					echo "<tr>";
					echo "<td>".$marketplace['seller']." will sell <b>".number_format($marketplace['itemamount'])." ".$marketplace['item']."</b> for <b>".number_format($marketplace['priceamount'])." ".$marketplace['price']."</b></td>";
					?>
					<form action="marketplace" method="post">
						<input type="hidden" name="id" value="<?php echo $marketplace['id']; ?>"/>
						<input type="hidden" name="seller" value="<?php echo $marketplace['seller']; ?>"/>
						<input type="hidden" name="itemamount" value="<?php echo $marketplace['itemamount']; ?>"/>
						<input type="hidden" name="item" value="<?php echo $marketplace['item']; ?>"/>
						<input type="hidden" name="priceamount" value="<?php echo $marketplace['priceamount']; ?>"/>
						<input type="hidden" name="price" value="<?php echo $marketplace['price']; ?>"/>
						<td><input type="submit" name="delete" value="Delete"/></td>
					</form>
					<?php
					echo "</tr>";
				} else {
					echo "<tr>";
					echo "<td>".$marketplace['seller']." will sell <b>".number_format($marketplace['itemamount'])." ".$marketplace['item']."</b> for <b>".number_format($marketplace['priceamount'])." ".$marketplace['price']."</b></td>";
					?>
					<form action="marketplace" method="post">
						<input type="hidden" name="id" value="<?php echo $marketplace['id']; ?>"/>
						<input type="hidden" name="seller" value="<?php echo $marketplace['seller']; ?>"/>
						<input type="hidden" name="itemamount" value="<?php echo $marketplace['itemamount']; ?>"/>
						<input type="hidden" name="item" value="<?php echo $marketplace['item']; ?>"/>
						<input type="hidden" name="priceamount" value="<?php echo $marketplace['priceamount']; ?>"/>
						<input type="hidden" name="price" value="<?php echo $marketplace['price']; ?>"/>
						<td><input type="submit" name="buy" value="Buy"/></td>
					</form>
					<?php
					echo "</tr>";
				}
			}
		?>
	</tbody></table></div>
	
	<br /><br />
	<b>Create Trade</b>
	<br /><hr /><br />
	<form action="marketplace" method="post">
		Item you are selling: <select name="item"><br />
				<option value="gold">Gold</option>
				<option value="stone">Stone</option>
				<option value="iron">Iron</option>
				<option value="lumber">Lumber</option>
				<option value="wheat">Wheat</option>
				<option value="flour">Flour</option>
				<option value="apples">Apples</option>
				<option value="bread">Bread</option>
				<option value="cheese">Cheese</option>
				<option value="meat">Meat</option>
			  </select><br />
		Amount of the item you are selling: <input type="text" name="itemamount" autocomplete="off"/><br />
		What item do you want in return: <select name="price"><br />
				<option value="gold">Gold</option>
				<option value="stone">Stone</option>
				<option value="iron">Iron</option>
				<option value="lumber">Lumber</option>
				<option value="wheat">Wheat</option>
				<option value="flour">Flour</option>
				<option value="apples">Apples</option>
				<option value="bread">Bread</option>
				<option value="cheese">Cheese</option>
				<option value="meat">Meat</option>
			  </select><br />
		Amount of the item you want in return: <input type="text" name="priceamount" autocomplete="off"/><br />
		<input type="submit" name="trade" value="Create Trade"/>
	</form>
	
	<?php
	
	}
	
	include("./template/footer.php");
?>	