<?php
	$page = "create_thread";
	include ("forum_header.php");
	
	if (empty($_SESSION['uid'])) {
		output("You must be logged in to view this page!");
	} else {
		
		if(!empty($_POST['post'])) {
			$title = protect($_POST['title']);
			$content = protect($_POST['content']);
			$genre = protect($_POST['genre']);
			
			$post1 = mysqli_query($mysqli, "INSERT INTO forums (user, title, genre, post) VALUES ('".$user['username']."', '".$title."', '".$genre."', '".$content."')") or die(mysqli_error($mysqli));
			output("Post Created!");
			
		}
		
		?>
			
			<div class="row">
				<div class="col-sm-10">
						<center><h1>Create Thread</h1></center>
						<form action="create_thread.php" method="post">
							Title: <input type="text" name="title" autocomplete="off"/><br />
							<textarea name="content" cols="50" rows="10">Content Here!</textarea><br />
							Genre: <select name="genre"><br />
								<option value="General">Anything about the Game</option>
								<option value="Feature">About a feature in the Game</option>
								<option value="Idea">An Idea for the Game</option>
								<option value="Question">A question about the game</option>
								<option value="Miscellaneous">Miscellaneous</option>
							</select><br />
							<input type="submit" name="post" value="Post Thread"/>
						</form>
				</div>
				<div class="col-sm-2">
					Here you can create threads that <b>everyone</b> can see!
				</div>
			</div>
		
		<?php
		
	}
	
	include ("forum_footer.php");
?>