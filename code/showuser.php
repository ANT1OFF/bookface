<html>
<HEAD>
    <LINK href="stylesheet.css" rel="stylesheet" type="text/css">
  </HEAD>
<?php

	$user = $_GET['user'];
#	$port = 8889;
#	$host = "localhost" . ":" . $port;
#	$db = "bookface";
	include_once "config.php";
	
		echo "\n<table class=headertable>\n<tr>";
			echo "<td class=header ><td class=header>";
						echo "<h1 class=header><a class=title href='$weburl'>bookface</a></h1>";
						echo "</tr></table><br><br>\n";
   if ( isset($replica_dbhost) ){
       $dbhost = $replica_dbhost;
   }

	$link = mysql_connect("$dbhost:$dbport", $dbuser,$dbpassw );


	if ($link){
#    	 echo "Connection successful!\n<br>";
    	$bfdb = mysql_select_db($db,$link);
    	if ( !$bfdb ){
				echo "Cannot use $db: " . mysql_error() ."<br>";
    	} else {
	    $memcache_override = 0;
	    if ( isset($_GET['nomemcache'])){
		$memcache_override = $_GET['nomemcache'];
	    }

			
			if ( isset($memcache_enabled) and $memcache_enabled == 1 and ! $memcache_override ){
				echo "<! Memcache is enabled !>";
				$memcache = new Memcache();
    			$memcache->addServer ( $memcache_server,"11211" );
			}
#			echo "Correct database found ( $dbhost:$dbport , $dbuser , $webhost)<br>\n";
			$result = mysql_query("select name,status,posts,comments,createDate from user where userID = $user");
			$res = mysql_fetch_array($result);

			echo "\n<table class=headertable>\n<tr>";
			echo "<td class=header ><img src='$weburl/showimage.php?user=$user'><td class=header>";
						echo "<h2 class=hader>" . $res['name'] . "</h2>";
						echo "</tr></table>\n";
			echo "<b>Member since: " . $res['createDate'] . " posts: " . $res['posts'] . "</b><br>\n";						

			if ( isset($memcache) and $memcache ){
				$posts_by_user = $memcache->get("posts_by_$user");
			} else {
    			echo "<! No memcache object found: posts_by_$user !>\n";
				$posts_by_user = array();
    			$sql = "select postID,text,postDate from posts where userID = $user order by postDate desc;";
    			$res = mysql_query($sql);
    			while($rec = mysql_fetch_assoc($res)){
        			$posts_by_user[] = $rec;
    			}
    // cache for 10 minutes
    			if ( $memcache ){
					$memcache->set("posts_by_$user", $posts_by_user,0,600);
				}	
			}
			
#			$posts = mysql_query("select postID,text,postDate from posts where userID = $user order by postDate desc;");
			$postcount = 0;
			$table = "<table>\n";
	    if ( isset($posts_by_user) ){
				foreach ( $posts_by_user as $res ){
					$table .= "<! postID:". $res['postID'] . " !><tr><td class=post>" . $res['postDate'] . "</td><td class=postcontent>" . $res['text'] . "</td><tr>\n";
					$postcount++;
					
					$key = "comments_on_" . $res['postID'];
					if ( $memcache ){
						$comments_on_post = $memcache->get($key);
					}
					if( $comments_on_post == false){
    					echo "<! No memcache object for comment found: $key !>\n";
						$comments_on_post = array();
    					$sql = "select commentID,text,userID,postDate from comments where postID = " . $res['postID'] . " order by postDate asc;";
    					$res = mysql_query($sql);
    					while($rec = mysql_fetch_assoc($res)){
        					$comments_on_post[] = $rec;
    					}
    // cache for 10 minutes
    			if ( $memcache ){
					$memcache->set($key, $comments_on_post,0,600);
				}	
			}
					
			#		$comments = mysql_query("select commentID,text,userID,postDate from comments where postID = " . $res['postID'] . " order by postDate asc;");

					if ( count($comments_on_post) > 0 ){
						$table .= "</table>\n<table class=commentrow>\n";
						foreach ( $comments_on_post as $cres ){
								$users = mysql_query("select name from user where userID = $cres[userID]");
								$ures = mysql_fetch_array($users);
								$table .= "<tr  ><td class=commentpost >" . $cres['postDate'] . "</td><td><a href='$weburl/showuser.php?user=" . $cres['userID']. "'><img src='$weburl/showimage.php?user=".$cres['userID'] ."'></a></td><td><b><a href='$weburl/showuser.php?user=" . $cres['userID']. "'>" . $ures['name'] . ": </a></b></td><td>" . $cres['text'] . "</td></tr>";
							
						}
						$comments_on_post = array();
					    $table .= "</table>\n<table>\n";							
					}
			}
	    }
			$table .= "</table>\n";

			echo "$table\n";
		}
		echo "</table>\n";
	}

?>

</html>
