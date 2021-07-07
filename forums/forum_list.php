<?php
	$page = "forum_list";
	include ("forum_header.php");
	
	?>
	
	<div class="sidenav-right" id="sidenav-right">
	</div>
	
	<center><h1>Thread List</h1></center>
	<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
		<tr>
			<th><b>Author</b></th>
			<th><b>Title</b></th>
			<th><b>Genre</b></th>
		</tr>
		</thead><tbody>
		<?php
			$get_forum = mysqli_query($mysqli, "SELECT * FROM forums") or die(mysqli_error($mysqli));
			while($forum = mysqli_fetch_assoc($get_forum)) {
					echo "<tr>";
					echo "<td>" . $forum['user'] . "</td>";
					echo "<td> <a href='thread.php?id=" . $forum['id'] . "'>" . $forum['title'] . "</a></td>";
					echo "<td>" . $forum['genre'] . "</td>";
					echo "</tr>";
			}
		?>
	</tbody></table></div>
	
	<?php
	
	include ("forum_footer.php");
?>