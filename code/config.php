<?php
$dbhost = getenv("BF_DB_HOST");
$dbport = "3306";
$db = getenv("BF_DB");
$dbuser = getenv("BF_USER");
$dbpassw = getenv("BF_PASSWORD");
$webhost = getenv("BF_ENTRY_IP");
$weburl = 'http://' . $webhost ;

?>
