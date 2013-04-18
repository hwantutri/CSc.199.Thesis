<?php
session_start();
session_name("my_apps_name");

# Set all the php files to be incliude
include "includes/authenticate.inc.php";
include "class/pgsql_db.class.php";

$conn = new pgsql_db();
#var_dump($conn);


# Set variables to use
$error_message = "";

if(isset($_POST['btnRegister'])) 
{
	$fname = trim($_POST['firstname']);
	$lname = trim($_POST['lastname']);
	$country = trim($_POST['country']);
	$email = trim($_POST['email']);
	$desc = trim($_POST['desc']);
	$age = trim($_POST['age']);
	$gender = trim($_POST['gender']);
	var_dump($_POST);
   
   if($fname=="" OR $lname =="" OR $country=="" OR $email=="" OR $gender=="" OR $age=="" OR $desc=="")
   {
		$errs = $error_message .= "Please fill-in every field.";
		die("die");
   }
   else{
	     
		$uname = $_SESSION['username'];
		$qry = "SELECT userid FROM userprof WHERE username='$uname'";
		$sql = "INSERT INTO profile(userid, firstname, lastname, gender, age, description) VALUES ( '$qry', '$fname', '$lname', '$country', '$email', '$desc', '$age', '$gender')";
		
		$inserted = $conn->query($sql);
		

			$_SESSION['authenticated'] = true;
			$_SESSION['username'] = $username;
			header("location:index.php");
			exit;
		}
		
   
   
 } 


?>


<!DOCTYPE html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/logo.ico"/>
<meta charset="utf-8">
<title>My Vibes - Music Recommender System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php include "includes/login-css.inc.php";?>
<?php include "includes/login-header.inc.php";?>
<body>
<div class="container">
	<div class="span12">
		<div class="span11">
		<div class="span10 background1">
			<fieldset>
				<legend>Register</legend>
			</fieldset>
				<div class="row show-grid">
					<div class="span5">
						<form class="form-horizontal-register" method="post" action="register.php">
							<fieldset style='margin-left:20px'>
								<div class="control-group">
									<label class="control-label" for="inputFirstName">First Name</label>
								<div class="controls">
									<input class="input-xlarge" type="text" name="firstname" id="firstname" placeholder="Fist Name">
								</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputLastName">Last Name</label>
								<div class="controls">
									<input class="input-xlarge" type="text" name="lastname" id="lastname" placeholder="Last Name">
								</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputCountry">Country</label>
								<div class="controls">
									<input class="input-xlarge" name="country" id="country" type="text" placeholder="Country">
								</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputEmail">Email</label>
								<div class="controls">
									<input type="text" class="input-xlarge" name="email" id="email" placeholder="Email">
								</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputDescription" name="desc" id="desc">Description</label>
								<div class="controls">
									<textarea rows="5"class="field span4"></textarea>								</div>
								</div>
							</fieldset>
						
					</div>
					<div class="span5">
						<fieldset>
								<div class="control-group">
									<label class="control-label" for="inputDateOfBirth">Date of Birth</label>
								<div class="controls">			 
									<select style='width:30%;'>
										<option>Month:</option>
										<option>Jan</option>
										<option>Feb</option>
										<option>Mar</option>
										<option>Apr</option>
										<option>May</option>
										<option>Jun</option>
										<option>Jul</option>
										<option>Aug</option>
										<option>Sep</option>
										<option>Oct</option>
										<option>Nov</option>
										<option>Dec</option>
									</select>
									<select style='width:30%;'>
										<option>Day:</option>
									<?php
										for ($i=1; $i<32; $i++){
											echo "<option value=".$i.">$i</option>";
										}
									?>
									</select>
									<select style='width:30%;'>
										<option>Year:</option>
										<?php
										for ($i=1905; $i<2013; $i++){
											echo "<option value=".$i.">$i</option>";
										}
									?>
									</select>
								</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputGender">Gender</label>
								<div class="controls">	
									<input type="checkbox" name="gender" id="gender" value="male" />  Male
									<input type="checkbox" name="gender" id="gender" value="female" />  Female</option>
								</div>
								</div>
								<div class="span4" style='margin-top:20px'><img src="img/musicnotes2y.jpg" width="100%" class="img-polaroid"></div>
								<button type="submit" class="btn btn-inverse" style='margin-top:30px' id="btnRegister" name="btnRegister">Register</button>
							</fieldset>
					</div>
					</form>
				</div>
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

