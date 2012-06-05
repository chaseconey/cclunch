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
<!DOCTYPE html>
<html><head>
<title>Lunch for the Bunch</title>
<meta charset="UTF-8">
<meta name="description" content="" />
<meta http-equiv="refresh" content="60" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="js/kickstart.js"></script>                                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="css/kickstart.css" media="all" />                  <!-- KICKSTART -->
<link rel="stylesheet" type="text/css" href="style.css" media="all" />                          <!-- CUSTOM STYLES -->
</head><body><a id="top-of-page"></a><div id="wrap" class="clearfix">
<!-- ===================================== END HEADER ===================================== -->
<?php 
	$web_root = $_SERVER['DOCUMENT_ROOT'];
	require_once("$web_root/includes/navigation.php"); 
?>
	 
<div class="col_12 center">
	<div id="header">
		<h1>Lunch Picker</h1>
	</div>
	<div id="col_12">
		<form action="index.php" method="post">
			<select name="choice_id">
				<?php
					$sql = "SELECT * FROM choices ORDER BY choice ASC";
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
			</select>
			<button type="submit" class="small green">Vote</button>
		</form>
	</div>
	<hr />
	<div id="results">
		<table id="result_list" class="tight sortable">
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
</div>
</body>
</html>