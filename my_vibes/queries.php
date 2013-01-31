<?php

//connect to db
$con = mysql_connect("localhost","root","");
if (!$con){
  die('Could not connect: ' . mysql_error());
}
mysql_select_db('pr_vibes',$con);



//MySQL queries from data.php

function confirmUser($user,$pass){
	mysql_query("SELECT * from users WHERE uid='$user' AND pass='$pass'");
}

function checkUserInElapsed($user){
	mysql_query("SELECT * from elapsed WHERE user='$user'")
}

//check if the $user has an existing data
//if there is, just update the start and end time. else insert.

function updateElapsed($start,$user){
	mysql_query("UPDATE elapsed
				SET start_time='$start', end_time='$start', song_id=0, curr_track_length='00:00'
				WHERE user='$user'");
}

function insertIntoElapsed($start,$user){
	mysql_query("INSERT INTO elapsed (user, start_time, end_time, song_id, curr_track_length)
				VALUES('$user','$start','$start','','')");
}

//check for the duplicate of the song in the 'songs' table

function checkDuplicate($title,$artist){
	mysql_query("SELECT sid from songs 
				WHERE title='$title' AND artist='$artist'");
}

function insertDataIntoSongsTable($title,$artist,$album,$year,$genre,$length,$rating){
	mysql_query("INSERT INTO songs (title, artist, album, year, genre, length, rating)
				VALUES ('$title','$artist','$album','$year','$genre','$length','$rating')");
}

function getSongId($title,$artist){
	mysql_query("SELECT sid from songs 
				WHERE title='$title' AND artist='$artist'");
}

function checkSubmissions($sid,$user){
	mysql_query("SELECT sid from submissions 
				WHERE sid='$sid' AND uid='$user'");
}

function insertIntoSubmissions($start,$sid,$user){
	mysql_query("INSERT INTO submissions (uid,sid,num_plays,num_skips,timestamp)
				VALUES ('$user','$sid','0','0','$start')");
}

function updateSubmissions($sid,$user){
	mysql_query("UPDATE submissions SET timestamp='$start'
				WHERE uid='$user' AND sid='$sid'");
}

function getSongIdFromElapsed($user){
	mysql_query("SELECT songs.sid AS new_sid FROM elapsed, songs 
				WHERE elapsed.user='$user' AND songs.sid=elapsed.song_id");
}

function updateElapsedWhenASongIdIsFound($start,$e_sid,$user){
	mysql_query("UPDATE elapsed SET end_time='$start' 
				WHERE user='$user' AND song_id='$e_sid'");
}

function getStartAndEndTime($e_sid,$user){
	mysql_query("SELECT start_time, end_time FROM elapsed
				WHERE song_id='$e_sid' AND user='$user'");
}

function getCurrentLengthOfSong($e_sid,$user){
	mysql_query("SELECT curr_track_length FROM elapsed
				WHERE song_id='$e_sid' AND user='$user'");
}

function getLatestNumPlaysOfSong($e_sid,$user){
	mysql_query("SELECT num_plays FROM submissions
				WHERE sid='$e_sid' AND uid='$user'");
}

function updateNumPlays($num_plays,$e_sid,$user){
	mysql_query("UPDATE submissions SET num_plays='$num_plays'
				WHERE sid='$e_sid' AND uid='$user'");
}

function getLatestNumSkipsOfSong($e_sid,$user){
	mysql_query("SELECT num_skips FROM submissions
				WHERE sid='$e_sid' AND uid='$user'");
}

function updateNumSkips($num_skips,$e_sid,$user){
	mysql_query("UPDATE submissions SET num_skips='$num_skips'
				WHERE sid='$e_sid' AND uid='$user'");
}

function updateElapsedTable($start,$sid,$length,$user){
	mysql_query("UPDATE elapsed
				SET start_time='$start', end_time='$start', song_id='$sid', curr_track_length='$length'
				WHERE user='$user'");
}

?>