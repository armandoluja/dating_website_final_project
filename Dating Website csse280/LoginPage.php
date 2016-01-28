<?php
session_start();
if(isset($_SESSION)){
	session_unset();
	session_destroy();
}
session_start();

function login(){	
	include('db.php');
	$username = $_POST['username'];
	$password = $_POST['password'];
	$username = $db->quote($username);
	$password = $db->quote($password);
	//passwords aren't hashed and no salt is added
	//do as an extra if time
	$sql = "select id from users where username = $username and password = $password;";
	$rows = $db->query($sql);
	if($rows->rowCount() > 0){
		$_SESSION["UserId"] = $rows->fetch()['id'];
		header("location: MainPage.php");
	}else{
		header("location: LoginPage.php?invalidLogin=1");
	}
}
?>
<html>
	<head>
		<title> Rose Students Meet</title>
		<link href = "LoginPage.css" type = "text/css" rel = "stylesheet" />
		<link href="shared.css" type="text/css" rel="stylesheet" />
		<script src = "LoginPage.js" type = "text/javascript"></script>
	</head>
  <body>
  	<div id = "loginDiv">
  		<div id="banner">
  			<h1>Welcome to Rose Students Meet!</h1>
		</div>
			<?php
			if(isset($_POST['username']) && isset($_POST['password'])){
				login();
			}else{
			?>
		<div id="inputArea">
	        	<h3>Login to Rose Students Meet</h3>
	            <?php
	            	if(isset($_GET['invalidLogin'])){
	            		if($_GET['invalidLogin'] == 1){
	            			?>
	            				<div id="invalidLoginMessage">Invalid username or password.</div>
	            			<?php
	            		}
	            	}
	            ?>
	            <form id="loginForm" method="post" action="LoginPage.php">
	               Username: <input id="username" type="text" name="username"><br />
	               Password: <input id="password" type="password" name="password"><br />
              		<button type="submit" />Login</button>
	            </form>	
       		<div>
				<p>New to Rose Students Meet? <br />
				Create a  
				<a href = "register.php">free account</a> today!
				</p>
			</div>
		</div>	
	</div>	
	<?php
	}
	?>
  </body>
	
</html>