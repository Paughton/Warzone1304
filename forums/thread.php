<?php
	$page = "thread";
	include ("forum_header.php");
	
	if(empty($_GET['id'])) {
		output("You have visited this page incorrectly!");
	} else {
		$id = protect($_GET['id']);
		$forum_check = mysqli_query($mysqli, "SELECT * FROM forums WHERE id='".$id."'") or die(mysqli_error($mysqli));
		if (mysqli_num_rows($forum_check) == 0) {
			output("That thread does not exists!");
		} else {
			
			if(!empty($_POST['reply'])) {
				if (empty($_SESSION['uid'])) {
					output("You must be logged in to reply to this thread!");
				} else { 
					$reply = protect($_POST['content_reply']);
			
					$post1 = mysqli_query($mysqli, "INSERT INTO reply (postid, user, reply) VALUES ('".$id."', '".$user['username']."', '".$reply."')") or die(mysqli_error($mysqli));
					output("Reply Submitted!");
				}
			
			}
			
			$t_forum = mysqli_fetch_assoc($forum_check);
			?>
				
				<div class="row">
					<div class="col-sm-10">
						<center><h1><?php echo $t_forum['title']; ?></h1></center>
						<p><?php echo $t_forum['post']; ?></p><br /><br />
						
						<h2>Replies</h2>
						<hr />
						
						<?php
							$get_reply = mysqli_query($mysqli, "SELECT * FROM reply") or die(mysqli_error($mysqli));
							while($user_reply = mysqli_fetch_assoc($get_reply)) {
								if ($user_reply['postid'] == $id) {
									echo "<p>" . $user_reply ['user'] . " :: " . $user_reply['reply'] . "</p>";
								}
							}
						?>
						<hr /><br /><br />
						
						<h3>Reply Here</h3>
						<form action="thread.php?id=<?php echo $id ?>" method="post">
							<textarea name="content_reply" cols="50" rows="10">Reply Here!</textarea><br />
							<input type="submit" name="reply" value="Reply to Thread"/>
						</form>
						
					</div>
					<div class="col-sm-2">
						This post was written by the one and only: <?php echo $t_forum['user']; ?><br />
						Genre: <?php echo $t_forum['genre']; ?>
					</div>
				</div>
				
			<?php
			
		}
	}
	
	include ("forum_footer.php");
?>