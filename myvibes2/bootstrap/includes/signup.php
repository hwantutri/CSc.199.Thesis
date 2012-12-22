<?php
if(isset($_POST['btnSignUp'])) 
{
	$username = trim($arr['username']);
	$email = trim($arr['email']);
	$password = trim($arr['pasword']);
   
   if($username=="" OR $password == "" OR $email=="")
   {
		$errs = $error_message .= "Please fill-in username, email and password";
   }
   else{
		
		$sql = "INSERT INTO profile(emailaddress) VALUES ( '$email')";
		$sql = "INSERT INTO userprof(username, password) VALUES ( '$username', '$password')";
	
		$inserted = $this ->conn -> query($sql);

			$_SESSION['authenticated'] = true;
			$_SESSION['username'] = $username;
			header("location:index.php");
			exit;
		}
		
   
   
 } 
 ?>