<?php
	require("connect.php");
	require("functions.php");

	$ip = getRealIpAddr();

	if(isset($_POST['choice_id'])){
		$choice_id = $_POST['choice_id'];

		//Check if already a daily choice
		$listed = isDailyChoice($choice_id);

		//Check if allowed to vote
		$canVote = isAbleToVote($ip);

		//If there are copies in the daily shiz yet
		if((!$listed) && ($canVote)){
			//Insert daily choice for day
			$sql = "INSERT INTO daily_choices (choice_id) VALUES ($choice_id)";
			$result = mysql_query($sql);

			if (!$result) {
			    echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
			    exit;
			}
		}

		//Check if already voted
		if(!$canVote){
			echo "<p class='error'>You have already voted, you cheater.";
		}
		else{
			//Insert daily choice for day
			addVote($choice_id);
			trackVotee($ip);
		}
	}

?>
<html>
	<head>
		<title>Lunch for the Bunch</title>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div id="content">
			<nav>
					<a href="index.php">Home</a>
					<a href="add_place.php">Add a Place</a>
			</nav>
			<div id="header">
				<img class="banner" src="img/bacon.png" />
				<h1>Food</h1>
			</div>
			<div id="addDailyChoice">
				<h2>Add a Daily Choice</h2>
				<form action="index.php" method="post">
					<select name="choice_id">
						<?php
							$sql = "SELECT * FROM choices";
							$result = mysql_query($sql);

							if (!$result) {
							    echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
							    exit;
							}
							if (mysql_num_rows($result) == 0) {
							    echo "<p class='error'>No rows found, nothing to print so am exiting";
							    exit;
							}
							while ($row = mysql_fetch_assoc($result)) {
								echo "<option type='text' value='" . $row['id'] . "'>" . $row['choice'] . "</option>";
							}
						?>
					</select><br />
					<input type="submit" value="Vote" />
				</form>
			</div>
			<div class="cf"></div>
			<div id="results">
				<table id="result_list">
					<thead>
						<tr>
							<th>Place</th><th>Location</th><th>Votes</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sql = "SELECT choice, location, choice_date, daily_votes AS votes FROM daily_choices LEFT JOIN choices on daily_choices.choice_id = choices.id WHERE daily_choices.choice_date BETWEEN concat(CURDATE(), ' 00:00:00') AND concat(CURDATE(), ' 23:59:59') GROUP BY daily_choices.choice_id ORDER BY choice;";
							$result = mysql_query($sql);

							if (!$result) {
							    echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
							    exit;
							}
							if (mysql_num_rows($result) == 0) {
							    echo "<p class='error'>No places added yet!";
							    exit;
							}
							while ($row = mysql_fetch_assoc($result)) {
								$choice = $row['choice'];
						?>
								<tr>
									<td><?php echo $choice ?></td>
									<td><a href="<?php echo urldecode($row['location']); ?>" target="_blank">Map</a></td>
									<td><?php echo ($row['votes'] > 0) ? $row['votes'] : "0"; ?></td>
								</tr>
						<?php 
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>