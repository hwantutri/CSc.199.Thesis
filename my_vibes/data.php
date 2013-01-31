<?php

//time zone
date_default_timezone_set("Asia/Manila");

//start_time initial
$start = date('Y-m-d H:i:s', time());

//connect to db
$con = mysql_connect("localhost","root","");
if (!$con){
  die('Could not connect: ' . mysql_error());
}
mysql_select_db('pr_vibes',$con);

//passed values from Winamp Plugin
$user = $_GET['u'];
$pass = $_GET['p'];
$song = $_GET['song'];

//tokenize $song
list($artist,$title,$album,$year,$genre,$length,$rating) = explode(" -", $song);

//Check if $user and $pass exists in the 'users' table
$confirm_user = mysql_query("SELECT * from users WHERE uid='$user' AND pass='$pass'");

//If the user is in the table
if((mysql_num_rows($confirm_user)!=0)&&(mysql_num_rows($confirm_user)>0)){
	//and if the length of $title and $artist is 0 or an empty string
	if((strlen($artist)==0) && (strlen($title)==0)){
		//check if the $user has an existing data
		//if there is, just update the start and end time. else insert.
		$check_user_in_elapsed = mysql_query("SELECT * from elapsed WHERE user='$user'");
			if(mysql_num_rows($check_user_in_elapsed)>0){
				mysql_query("UPDATE elapsed
							SET start_time='$start', end_time='$start', song_id=0, curr_track_length='00:00'
							WHERE user='$user'");
			}else{
				mysql_query("INSERT INTO elapsed (user, start_time, end_time, song_id, curr_track_length)
							VALUES('$user','$start','$start','','')");
			}
	}else{
		//else if $title and $artist are not empty, check for the duplicate of the song in the 'songs' table
		$check_duplicate = mysql_query("SELECT sid from songs 
										WHERE title='$title' AND artist='$artist'");
		//if a duplicate is found, do nothing. If none, insert
		if(mysql_num_rows($check_duplicate)==0){
				//insert the mp3 tags into the 'songs' table
				mysql_query("INSERT INTO songs (title, artist, album, year, genre, length, rating)
							VALUES ('$title','$artist','$album','$year','$genre','$length','$rating')");
		}
		//get song id from songs table using the title and artist given by the plugin
		$get_id_from_songs = mysql_query("SELECT sid from songs 
										WHERE title='$title' AND artist='$artist'");
		//if an id is found
		if(mysql_num_rows($get_id_from_songs)>0){
			//get the id
			while($row = mysql_fetch_object($get_id_from_songs)){
				$sid = $row->sid;
				$check_submissions = mysql_query("SELECT sid from submissions 
												WHERE sid='$sid' AND uid='$user'");
				//if the id is not yet in the submissions table
				if(mysql_num_rows($check_submissions)==0){
					//insert it into the submissions table together with the user id
					mysql_query("INSERT INTO submissions (uid,sid,num_plays,num_skips,timestamp)
								VALUES ('$user','$sid','0','0','$start')");
				}else{
					mysql_query("UPDATE submissions SET timestamp='$start'
								WHERE uid='$user' AND sid='$sid'");
				}
				
				//get the latest sid from the elapsed table by the current user (sid is also in the songs table)
				$get_sid_from_elapsed = mysql_query("SELECT songs.sid AS new_sid FROM elapsed, songs 
													WHERE elapsed.user='$user' AND songs.sid=elapsed.song_id");
				//if an id is found
				if(mysql_num_rows($get_sid_from_elapsed)>0){
					//extract the id
					while($row = mysql_fetch_object($get_sid_from_elapsed)){
						$e_sid = $row->new_sid;
						mysql_query("UPDATE elapsed SET end_time='$start' 
									WHERE user='$user' AND song_id='$e_sid'");
						//get the start_time and end_time, compute the time elapsed and then compare it from the length of the song
						$start_and_end = mysql_query("SELECT start_time, end_time FROM elapsed
													WHERE song_id='$e_sid' AND user='$user'");
						while($row = mysql_fetch_object($start_and_end)){
							$new_start = strtotime($row->start_time);
							$new_end = strtotime($row->end_time);
							$elapsed = date('i:s',$new_end - $new_start);
							$get_length_of_song = mysql_query("SELECT curr_track_length FROM elapsed
															 WHERE song_id='$e_sid' AND user='$user'");
							while($row2 = mysql_fetch_object($get_length_of_song)){
								//get percentage
								$percentage = get_percentage($elapsed,$row2->curr_track_length);
								
								//minimum track length percentage (played compared to actual)
								$min_percentage = 85;
								
								if($percentage >= $min_percentage){
									$get_latest_num_plays_of_song = mysql_query("SELECT num_plays FROM submissions
																				WHERE sid='$e_sid' AND uid='$user'");
									while($row3 = mysql_fetch_object($get_latest_num_plays_of_song)){
										$num_plays = ($row3->num_plays) + 1;
										mysql_query("UPDATE submissions SET num_plays='$num_plays'
													WHERE sid='$e_sid' AND uid='$user'");
									}
								}else{
									$get_latest_num_skips_of_song = mysql_query("SELECT num_skips FROM submissions
																				WHERE sid='$e_sid' AND uid='$user'");
									while($row3 = mysql_fetch_object($get_latest_num_skips_of_song)){
										$num_skips = ($row3->skips) + 1;
										mysql_query("UPDATE submissions SET num_skips='$num_skips'
													WHERE sid='$e_sid' AND uid='$user'");
									}
								}
							}
						}
						mysql_query("UPDATE elapsed
									SET start_time='$start', end_time='$start', song_id='$sid', curr_track_length='$length'
									WHERE user='$user'");
					}
				}else{
					mysql_query("UPDATE elapsed
								SET start_time='$start', end_time='$start', song_id='$sid', curr_track_length='$length'
								WHERE user='$user'");
				}
			}
		}
	}
}


// get the percentage between actual length and computed/played length

function get_percentage($x,$y){
	$default = strtotime("00:00:00");
	if(strlen($y)<=5){
		$y = strtotime('00:'.$y) - $default;
		if(strlen($x)<=5){
			$x = strtotime('00:'.$x) - $default;
			return ($x * 100)/$y;
		}else{
			return 100;
		}
	}else{
		$x = strtotime($x) - $default;
		$y = strtotime($y) - $default;
		return ($x * 100)/$y;
	}
}

?>