<?php //register.php/btnRegister
session_start();
session_name("my_apps_name");

# Set all the php files to be incliude
include "class/pgsql_db.class.php";

$conn = new pgsql_db();
#var_dump($conn);


# Set variables to use
$error_message = "";

if(isset($_POST['btnRegister'])) 
{
	$fname = trim($_POST['firstname']);
	$lname = trim($_POST['lastname']);
	$gender = trim($_POST['gender']);
	$age = trim($_POST['age']);
	$desc = trim($_POST['description']);
	var_dump($_POST);
   
   if($fname=="" OR $lname == "" OR $gender=="" OR $age=="" OR $desc=="")
   {
		$errs = $error_message .= "Please fill-in every field.";
		die("die");
   }
   else{
	     
		$uname = $_SESSION['username'];
		$qry = "SELECT userid FROM userprof WHERE username='$uname'";
		$sql = "INSERT INTO profile(userid, firstname, lastname, gender, age, description) VALUES ( '$qry', '$fname', '$lname', '$gender', '$age', '$desc')";
		
		$inserted = $conn->query($sql);
		

			$_SESSION['authenticated'] = true;
			$_SESSION['username'] = $username;
			header("location:index.php");
			exit;
		}
		
   
   
 } 


?>

-----------------------------------

<?php  //login/btnLogin-->btnSignUp
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
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	var_dump($_POST);
   
   if($username=="" OR $password == "" OR $email=="")
   {
		$errs = $error_message .= "Please fill-in username, email and password";
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


-------------------------------------


<?php  //index.php
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


<?php					
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
				?>
				
				
--------------


<?php //friends.php
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
				
$results2 =array();
				$uname = $_SESSION['username'];
				$sql2 = "SELECT firstname, lastname FROM friends WHERE userid = (SELECT userid FROM userprof WHERE username='$uname')";
				$results2 = $conn->get_results($sql2);
	

?>


<?php					
		
				$n =0;
				
				while($n<count($results2)){
				
					$row = (array)json_decode(json_encode($results2[$n]));
					
					//prints
					
						print "".$row['firstname']." ".$row['lastname']."<br/> ";
				
				
					$n++;
					
				}
				?>