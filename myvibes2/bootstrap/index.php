<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "includes/authenticate.inc.php";
include "class/pgsql_db.class.php";

# Instantiate class/es to use
$conn = new pgsql_db();

$results = array();
				$uname = $_SESSION['username'];
				$sql = "SELECT firstname, lastname FROM profile WHERE userid = (SELECT userid FROM userprof WHERE username='$uname')";
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
		<div class='span12'>
			<div class="span4"><!--  Profile info -->
				<div class="row">
				<div class='row background1 profile-circle' style='width:94%;margin-left:6%'>
					<div class="span1">
						<form class="form-profile">
							<div class="prof-pic"><img src="img/140x140.gif" width="100px" class="img-polaroid"></div>
						</form>
					</div>
					<div class='span2' style='width: 55%'>
						<div class='prof-info adjustment1'>
							<strong><?php					
				//print_r($results);
				
				
				$n =0;
				
				//print_r($results);
				//print "<br/><br/><br/>";
				
				while($n<count($results)){
				
					$row = (array)json_decode(json_encode($results[$n]));
					
					//prints
					print "<div style='float:left; min-width:100px; padding:10px;'>";
						print "".$row['firstname']." ".$row['lastname']."<br/> ";
					print "</div>";
					$n++;
					
				}
				?> </strong>
				<strong> (<?php echo $_SESSION['username']; ?>) </strong>
							  <!--<div class="muted">view profile <span class="divider">|</span>
							  edit profile</div>-->
						</div>
						
						 
			<div class='links'><strong>Playlist</strong></div>
					</div>
					<!--<div class="span3" style='width:85%;margin-top:-5%;'><hr></div>-->
					
				</div>
				<div class="span4">
				<div class='recommendation background1'><!--Recommendation-->
						<div class="recommendation-title">
							<strong>Recommendation</strong>
						</div><hr>
				</div>
				</div>
			</div>		
		</div>
			<div class="span7"><!-- Timeline -->
				<div class="form-timeline background1">
				<div class="timeline-title">
					<strong>Timeline</strong>
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