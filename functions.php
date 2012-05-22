<?php

function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
function isDailyChoice($choice_id){
    $sql = "SELECT count(daily_votes) AS listed FROM daily_choices WHERE choice_id = $choice_id AND choice_date BETWEEN concat(CURDATE(), ' 00:00:00') AND concat(CURDATE(), ' 23:59:59')";
    $result = mysql_query($sql);

    if (!$result) {
        echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }
    $count = mysql_fetch_assoc($result);
    $listed = $count['listed'];

    if($listed > 0)
        return true;
    else
        return false;
}

function isAbleToVote($ip){
    $sql = "SELECT count(ip) as vote_count FROM voted WHERE ip = '$ip' AND vote_date BETWEEN concat(CURDATE(), ' 00:00:00') AND concat(CURDATE(), ' 23:59:59')";
    $result = mysql_query($sql);

    if (!$result) {
        echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }
    $count = mysql_fetch_assoc($result);
    $vote_count = $count['vote_count'];

    if($vote_count > 0)
        return false;
    else
        return true;
}
function addVote($choice_id){
    $sql = "UPDATE daily_choices SET daily_votes=daily_votes+1 WHERE choice_id = $choice_id";
    $result = mysql_query($sql);

    if (!$result) {
        echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }
}

function trackVotee($ip){
    $sql = "INSERT INTO voted (ip) VALUES ('$ip')";
    $result = mysql_query($sql);

    if (!$result) {
        echo "<p class='error'>Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
    }
}

?>