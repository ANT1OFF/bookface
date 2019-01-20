<?php
$dbhost = getenv("BF_DB_HOST");
$dbport = "3306";
$db = getenv("BF_DB");
$dbuser = getenv("BF_USER");
$dbpassw = getenv("BF_PASSWORD");
$webhost = getenv("BF_ENTRY_IP");
$weburl = 'http://' . $webhost ;
$memcache_enabled = 0;
if ( getenv("BF_MEMCACHE_SERVER")){
   $memcache_enabled_pictures = 1;
   $memcache_server = getenv("BF_MEMCACHE_SERVER");
   $memcache_enabled = 1;
}
?>
