<?php
	$page = "tutorial";
	include("./template/header.php");

	if (empty($_SESSION['uid'])) {
		echo "You must be logged in to view this page!";
	} else {
		?>
			<center><h1>Tutorial</h1></center><hr /><br />
			<h2>Getting Started</h2>
			
			<p>When getting started in the game there are a few buildings you <b>need</b> to have in order to have a great start to the game. Now there are really 4 main resources: 
			Stone, Iron, Gold, and Lumber. While there are 4 main food items: Apples, Bread, Cheese, and Meat. Now the most efficient food item to mass produce is: Bread.
			But at the same time Bread is the most expensive food to produce. In order to make Bread you need Flour, which comes from a Mill grinding Wheat, which comes from
			a Wheat Farm that produces wheat. Then the flour is then baked into bread using a Bakery. When starting out it Warzone 1503 you want to have the most efficient
			income for the lowest of prices. At the <a href="buildings.php">Your Buildings</a> page build a <b>Lumber Mill</b>, <b>Mine</b>, <b>Hunter's Post</b>, and <b><i>2</i> Houses</b>.</p>
			
			<p>Once you have built those buildings, you need to assign the citizens you have gotten from building the houses. In the 
			<a href="workers.php">Assign Workers</a> page assign 5 workers to each building you have built (except the house). You should have one citizen left over, assign this
			citizen to your <b>Hunter Post</b>. Therefore you should have 6 workers in your <b>Hunter Post</b> while the rest should have only 5 workers. Now this method
			is not perfect, but it gives you the basics you need to have a great start to the game. Every hour is one turn, and after every turn you receive your income.
			
			<h2>Overview of Each Building</h2>
			
			<p>In the <a href="buildings.php">Your Buildings</a> page you can see many different buildings, each building has its own purpose! You can view the details of each
			building on that page. Below are what each worker adds to its income.</p>
			<ul>
				<li>Lumber Mill - Each worker adds +4 lumber to your income.</li>
				<li>Mine - Each worker adds +3 stone, +2 iron, and +1 gold to your income.</li>
				<li>Hunter's Post - Each worker adds +3 meat to your income.</li>
				<li>Apple Farm - Each worker adds +5 apples to your income.</li>
				<li>Dairy Farm - Each worker adds +6 cheese to your income.</li>
				<li>Wheat Farm - Each worker adds +3 wheat to your income.</li>
				<li>Mill - Each worker grinds 1 wheat into 4 flour per turn.</li>
				<li>Bakery - Each worker bakes 1 flour into 4 bread per turn.</li>
			</ul><br />
			
			<h2>Other Tips</h2>
			
			<p>Below are some tips that can help you with your journey to become greatness. These tips are <b>not perfect</b> and are not fit for every situation you are facing</p>
			<ul>
				<li>Have about most of your power come from your Military and your Buildings, <b>not</b> from your Stronghold Level and Land.</li>
				<li>Start working on your military immediatly after leveling your Stronghold up to level III (3), for you cannot attack or be attacked until your
				Stronghold level is IV (4).</li>
				<li>Once you are somewhat in the mid-game, I suggest starting the bread making process, for it is the <b>best</b> way to make food and will<br />
				feed the most citizens. It might be expensive at front but it is really worth while, for you can sell your excess bread on the Marketplace.</li>
			</ul><br />
			
			<h2>Breakdown of all the Pages</h2>
			<ul>
				<li><b>Stronghold</b> - Here you can work on <b>your</b> stronghold and progress it through the levels, and the chance of becomming the best player.
								 Here you can also change your account settings and access your inbox.</li>
				<li><b>World</b> - Here you can access the Marketplace, where you can trade with other players. You can not <b>create</b> trades until your Stronghold
							level is V (5), but you can still purchase or sell items. You can also view the Rankings where all the players are reanked from
							best to last.</li>
				<li><b>Alliance</b> - Here you can access your Alliance, and if your the president, the Alliance Control Panel. You can also find the list of active
									  Alliances. If you are not in an Alliance you can find an Alliance to join on the Alliances page. But if you want to create your
									  Own allaince you have to be not in an alliance, you can do this by clicking the Create Alliance in the dropdown menu.</li>
				<li><b>Community</b> - Here you can access this page and many other pages. Some of those pages include are the Changelog, where you can view all the new features
									   and what version the game is currently on.</li>
			</ul><br />
			
			<b>If you have any questions or concerns please contact "Warzone 1304 CS".</b><br />
			
		<?php
	}
	
	include("./template/footer.php");
?>