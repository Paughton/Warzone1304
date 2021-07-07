<script>

var myGamePiece;
cursor = "none";

function select_building() {
	building = document.getElementById("building_value").value;
	if (building != "auto") {
		change_cursor("./cursor/" + building + ".png");
		curosr = building;
	} else {
		var map = document.getElementById('map_canvas');
		map.style = "cursor: auto;";
		cursor = "none";
	}
}

window.onload = function() {
	
	startGame();
	
};

function startGame() {
    myGameArea.start();
	
	// draw_image(width, height, image, x-cord, y-cord);
	
	<?php
		
			/*echo "building2 = document.getElementById('" . $playermap['building'] . "');";
			echo "alert(building2);";
			echo "var building = new draw_img(32, 32, building2, " . $playermap['x'] . ", " . $playermap['y'] . ");";*/
		
			$get_playermap = mysqli_query($mysqli, "SELECT * FROM playermap") or die(mysqli_error($mysqli));
			while($playermap = mysqli_fetch_assoc($get_playermap)) {
				if ($playermap['userid'] == $s_user['id']) {
					?>
					display_building("<?php echo $playermap['building'] ?>", <?php echo $playermap['x'] ?>, <?php echo $playermap['y'] ?>);
					<?php
				}
			}
		?>
	
	create_listner("map_canvas");
	
}

function create_listner(id) {
	document.getElementById(id).addEventListener('click',function click(evt){
		
		var elem = document.getElementById(id),
			elemLeft = elem.offsetLeft,
			elemTop = elem.offsetTop,
			//context = elem.getContext('2d');
		
		x = evt.pageX - elemLeft;
		y = evt.pageY - elemTop;
		
		building = document.getElementById("building_value").value;
		
		if (building != "none") {
			window.location.href = "buildings?building=" + building + "&x=" + x + "&y=" + y;
		}
		
	},false);
}

function display_building(building, x, y) {
	
	lumbermill = document.getElementById('lumbermill');
	mine = document.getElementById('mine');
	hunterpost = document.getElementById('hunterpost');
	barrack = document.getElementById('barrack');
	stable = document.getElementById('stable');
	bowyerworkshop = document.getElementById('bowyerworkshop');
	weaponsmithery = document.getElementById('weaponsmithery');
	armory = document.getElementById('armory');
	house = document.getElementById('house');
	applefarm = document.getElementById('applefarm');
	dairyfarm = document.getElementById('dairyfarm');
	wheatfarm = document.getElementById('wheatfarm');
	mill = document.getElementById('mill');
	bakery = document.getElementById('bakery');
	marketplace = document.getElementById('marketplace');
	wall = document.getElementById('wall');
	siegecamp = document.getElementById('siegecamp');
	engineerworkshop = document.getElementById('engineerworkshop');
	
		if (building == "lumbermill") {
			building2 = new draw_img(32, 32, lumbermill, x, y);
		} else if (building == "mine") {
			building2 = new draw_img(32, 32, mine, x, y);
		} else if (building == "hunterpost") {
			building2 = new draw_img(32, 32, hunterpost, x, y);
		} else if (building == "barrack") {
			building2 = new draw_img(32, 32, barrack, x, y);
		} else if (building == "stable") {
			building2 = new draw_img(32, 32, stable, x, y);
		} else if (building == "shootingrange") {
			building2 = new draw_img(32, 32, shootingrange, x, y);
		} else if (building == "bowyerworkshop") {
			building2 = new draw_img(32, 32, bowyerworkshop, x, y);
		}  else if (building == "weaponsmithery") {
			building2 = new draw_img(32, 32, weaponsmithery, x, y);
		}  else if (building == "armory") {
			building2 = new draw_img(32, 32, armory, x, y);
		}  else if (building == "house") {
			building2 = new draw_img(32, 32, house, x, y);
		} else if (building == "applefarm") {
			building2 = new draw_img(32, 32, applefarm, x, y);
		} else if (building == "dairyfarm") {
			building2 = new draw_img(32, 32, dairyfarm, x, y);
		} else if (building == "wheatfarm") {
			building2 = new draw_img(32, 32, wheatfarm, x, y);
		} else if (building == "mill") {
			building2 = new draw_img(32, 32, mill, x, y);
		} else if (building == "bakery") {
			building2 = new draw_img(32, 32, bakery, x, y);
		} else if (building == "marketplace") {
			building2 = new draw_img(32, 32, marketplace, x, y);
		} else if (building == "wall") {
			building2 = new draw_img(32, 32, wall, x, y);
		} else if (building == "siegecamp") {
			building2 = new draw_img(32, 32, siegecamp, x, y);
		} else if (building == "engineerworkshop") {
			building2 = new draw_img(32, 32, engineerworkshop, x, y);
		}
}

var myGameArea = {
    canvas : document.createElement("canvas"),
    start : function() {
		land = document.getElementById('land_amount').innerHTML;
		this.canvas.width = land * 0.0099;
        this.canvas.height = land * 0.0085;
		this.canvas.id = "map_canvas";
        this.context = this.canvas.getContext("2d");
		var container = document.getElementById('canvas_content');
		container.insertBefore(this.canvas, container.childNodes[0]);
    }
}

function change_cursor(url) {
	var map = document.getElementById('map_canvas');
	map.style = "cursor: url('" + url + "'), auto;";
}

function draw_img(width, height, img, x, y) {
    this.width = width;
    this.height = height;
	this.img = img;
    this.x = x;
    this.y = y;
	ctx = myGameArea.context;
	ctx.drawImage(this.img, this.x, this.y, this.width, this.height);
}

</script>