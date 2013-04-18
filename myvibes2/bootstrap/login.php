<?php
session_start();
session_name("my_apps_name");

# Set all the php files to be incliude
include "class/pgsql_db.class.php";

$conn = new pgsql_db();
#var_dump($conn);
# Check if user has already logon
if(isset($_SESSION['username']) AND isset($_SESSION['authenticated']) AND $_SESSION['authenticated']){
   header("location:index.php");
   exit;
}
# End check

# Set variables to use
$error_message = "";

if(isset($_POST['btnLogin']))
{
   $username = str_replace("'","''",trim($_POST['username']));
   $password = str_replace("'","''",trim($_POST['password']));
   
   if($username=="" OR $password == "")
   {
		$error_message .= "Please fill-in username and password";
   }
   else{
		# Check username and password with our users table
		$sql = "SELECT COUNT(*) 
		        FROM userprof
				WHERE username = '".$username."' AND password = '".$password."'";
#echo $sql;
		$exist = $conn->get_var($sql);
        if($exist > 0)
		{
			# Ok ang username ug password, naa sa database
			$_SESSION['authenticated'] = true;
			$_SESSION['username'] = $username;
			header("location:index.php");
			exit;
		}
		else{
			$error_message .= "Invalid username or password!";
		}
   }
}


if(isset($_POST['btnSignUp'])) 
{
	$username = trim($_POST['username']);
	$repass = trim($_POST['repassword']);
	$password = trim($_POST['password']);
	var_dump($_POST);
   
   if($username=="" OR $password == "" OR $repass=="")
   {
		$errs = $error_message .= "Please fill-in all values.";
		die("die");
   }
   else{
		
		
		$sql = "INSERT INTO userprof(username, password) VALUES ( '$username', '$password')";
		
		$inserted = $conn->query($sql);
		

			$_SESSION['authenticated'] = true;
			$_SESSION['username'] = $username;
			header("location:register.php");
			exit;
		}
		
   
   
 } 

?>




<!DOCTYPE HTML>
<head>
<link rel="shortcut icon" type="image/ico" href="img/logo.ico"/>
<meta charset="utf-8">
<title>My Vibes - Music Recommender System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php include "includes/login-css.inc.php";?>
<?php include "includes/login-header.inc.php";?>

<body>
<div class="container">
	<div class="welcome">
	  <h4><strong>Welcome to MyVibes.</strong></h4>
	  <p><strong>Your Choice. Your Love. Your Music</br>
		Find out the best music and recommendation tracks under your circle.</p>
	  </strong>
	</div>
	<form class="form-horizontal" method="post" action="login.php">
	
	<?php if($error_message !=""){ ?>
		  <tr>
			 <td colspan="2" class="black"><?php echo $error_message; ?></td>
		  </tr>
		  <?php } ?>
		  
		  
	  <div class="control-group">
		  <input type="text" class="span4" name="username" id="username" placeholder="Email" />
	  </div>
	  <div class="control-group">
		  <input type="password" class="span3" name="password" id="password" placeholder="Password" />
		  <button type="submit" class="btn btn-info" id="btnLogin" name="btnLogin">Sign in</button>
	  </div>
	  <div class="control-group2">
		  <label class="checkbox">
			<label class="muted"><small>Remember Me . Forgot password?</small></label></label>
	  </div>
	</form>
	
		<form class="form-horizontal-signup" method="post" action="">
		
		<?php if($error_message !=""){ ?>
		  <tr>
			 <td colspan="2" class="black"><?php echo $errs; ?></td>
		  </tr>
		  <?php } ?>
		  
		<fieldset>
		<legend>Sign Up for MyVibes</legend>
		<input type="text" class="span4" name="username" id="username" placeholder="Username" />
		<input type="password" class="span4" name="password" id="password" placeholder="Password" />
		<input type="password" class="span4" name="repassword" id="repassword" placeholder="Retype Password" /></fieldset>
		<button type="submit" input name="btnSignUp" id="btnSignUp" class="btn btn-inverse">Sign Up</button>
		</form>
	
			
		<div class="row"><div class="span12"><div class="footerInverse"><?php include "includes/footer.inc.php"; ?></div></div></div>
	
</div>
<!-- /container -->
<!-- jQuery latest version since Bootstrap is dependent on it -->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!-- Bootstrap JS file (it containes predefined functionalities. Read the manual online on how to use) -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
