<?php
$link = mysql_connect('localhost', 'lunch', 'lunch');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
if (!mysql_select_db("lunch")) {
    echo "Unable to select mydbname: " . mysql_error();
    exit;
}
?>