<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/authenticate.inc.php";
include "class/pgsql_db.class.php";

# Instantiate class/es to use
$conn = new pgsql_db();

$results = array();
				$uname = $_SESSION['username'];
				$sql = "SELECT * FROM profile WHERE userid = (SELECT userid FROM userprof WHERE username='$uname')";
				$results = $conn->get_results($sql);

?>

<!DOCTYPE html>

<link rel="shortcut icon" type="image/ico" href="img/logo.ico"/>
<meta charset="utf-8">
<title>My Vibes - Music Recommender System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include "includes/css.inc.php"; ?>
<?php include "includes/header.inc.php"; ?>
<body>

	<div class="container">
		<div class="row show-grid">
		<div class="span4">
			<div class="span4"><!--Profile info-->
				<div class="row show-grid">
					<div class='row background1 profile-circle' style='width:90%;margin-left:6%'>
					<div class="span3">
						<form class="form-profile">
							<div class="prof-pic"><img src="img/140x140.gif" width="100%" class="img-polaroid"></div>
						</form>
					</div>
					<div class="row show-grid">
					<div class='span3 adjustment'>
						<div class="prof-info">
							<strong><?php					
				//print_r($results);
				
				$n =0;
				
				//print_r($results);
				//print "<br/><br/><br/>";
				
				while($n<count($results)){
				
					$row = (array)json_decode(json_encode($results[$n]));
					
					//prints
					print "<div style='float:left; min-width:100px; padding:10px;'>";
						print "".$row['firstname']." ".$row['lastname']." ";
					print "</div>";
					$n++;
					
				}
				?></strong>
							  <!--<div class="muted">view profile <span class="divider">|</span>
							  edit profile</div>-->
						</div>
						<div class='description font'><br/><br/><?php					
				//print_r($results);
				
				$n =0;
				
				//print_r($results);
				//print "<br/><br/><br/>";
				
				while($n<count($results)){
				
					$row = (array)json_decode(json_encode($results[$n]));
					
					//prints
					print "<div style='float:left; min-width:100px; padding:10px;'>";
						print "".$row['description']." "."<br/> ";
						print "".$row['gender']." "."<br/> ";
						print "".$row['address']." "."<br/> ";
						print "".$row['emailaddress']." "."<br/> ";
					print "</div>";
					$n++;
					
				}
				?></div>
						<div class="friends">Friends: <strong>200</strong></div>
						<div class="friends">Starred: <strong>45</strong></div>
					</div>
					</div>
					<!--<div class="span3" style='width:85%;margin-top:-5%;'><hr></div>-->
				</div>
				</div>
			</div>
			</div>
			<div class="span6"><!--Tracks-->
				<div class='form-tracks background1'>
					<div class="timeline-title">
						<strong>Tracks</strong>
					</div>
					<hr>
				</div>
			</div>
		</div>
	<div class="row"><div class="span12"><div class="footerInverse"><?php include "includes/footer.inc.php"; ?></div></div></div>
</div>
<!-- /container -->
<!-- jQuery latest version since Bootstrap is dependent on it -->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!-- Bootstrap JS file (it containes predefined functionalities. Read the manual online on how to use) -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>