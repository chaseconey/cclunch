<?php
	require("connect.php");

	if(isset($_POST['choice'])){
		$choiceArr = $_POST['choice'];
		$location = urlencode($choiceArr[1]);
		$choice = mysql_real_escape_string(stripslashes($choiceArr[0]));
		if($location)
			$sql = "INSERT INTO choices (choice, location) VALUES ('$choice', '$location')";
		else
			$sql = "INSERT INTO choices (choice) VALUES ('$choice')";
		$result = mysql_query($sql);

		if (!$result) {
		    echo "Could not successfully run query ($sql) from DB: " . mysql_error();
		    exit;
		}
	}
?>
<!DOCTYPE html>
<html><head>
<title>Lunch for the Bunch</title>
<meta charset="UTF-8">
<meta name="description" content="" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="js/kickstart.js"></script>                                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="css/kickstart.css" media="all" />                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="style.css" media="all" />                          <!-- CUSTOM STYLES -->
</head><body><a id="top-of-page"></a><div id="wrap" class="clearfix">
<!-- ===================================== END HEADER ===================================== -->

	<ul class="menu">
		<li><a href="/index.php">Home</a></li>
		<li><a href="/add_place.php">Add a Choice</a></li>
	</ul>
	 
<div class="col_12 center">
	<h1>Lunch Picker - Add</h1>
	<div class="col_4"></div>
	<div class="col_4">
		<h2>Add a Global Choice</h2>
		<form class="vertical" action="add_place.php" method="post">
			<label for="text1">Lunch Choice</label>
			<input id="text1" name="choice[]" type="text" />
			<label for="text2">Google Maps Location</label>
			<input id="text2" name="choice[]" type="text" />
			<button type="submit" class="green">Add Choice</button>
		</form>
	</div>
	<div class="col_4"></div>
</div>
</div>
</body>
</html>