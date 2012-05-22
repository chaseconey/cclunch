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
<html>
	<head>
		<title>Lunch for the Bunch</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<nav>
			<ul>
				<a href="index.php">Home</a>
				<a href="add_place.php">Add a Place</a>
			</ul>
		</nav>
		<h1>Lunch Picker - Add</h1>
		<div id="addChoice">
			<h2>Add a Global Choice</h2>
			<form action="add_place.php" method="post">
				<input type="text" name="choice[]" placeholder="Lunch Choice" /><br />
				<input type="text" name="choice[]" placeholder="Google Maps Location" /><br />
				<input type="submit" value="Enter Lunch Choice" />
			</form>
		</div>
	</body>
</html>