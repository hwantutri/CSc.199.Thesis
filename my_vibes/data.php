<?php

$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db('pr_vibes',$con);

$user = $_GET['u'];
$pass = $_GET['p'];
$song = $_GET['song'];

list($artist,$title,$album,$year,$genre,$length,$rating) = explode(" -", $song);

if((strlen($artist)==0) && (strlen($title)==0)){
	return FALSE;
}else{

	$check_duplicate = mysql_query("SELECT sid from songs 
									WHERE title='$title' AND artist='$artist'");

	if(mysql_num_rows($check_duplicate)>0){
		return FALSE;
	}else{
			mysql_query("INSERT INTO songs (title, artist, album, year, genre, length, rating)
						VALUES ('$title','$artist','$album','$year','$genre','$length','$rating')");
	}
	$get_song_sid = mysql_query("SELECT sid from songs 
								WHERE title='$title' AND artist='$artist'");
	
	while($row = mysql_fetch_object($get_song_sid)){
		$sid = $row->sid;
		mysql_query("INSERT INTO submissions (uid,sid)
					VALUES ('$user','$sid')");
	}			
}

?>