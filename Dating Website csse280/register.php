<?php
session_start();
if(isset($_SESSION)){
	session_unset();
	session_destroy();
}
session_start();

function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
?>

<?php
	include ("db.php");
    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) 
    	&& isset($_POST['email']) 
    	&& isset($_POST['password']) 
    	&& isset($_POST['firstname'])
	    && isset($_POST['lastname']) 
	    && isset($_POST['age'])
		&& isset($_POST['gender'])
		&& isset($_POST['taste'])
		&& $_FILES['userfile']['size'] > 0){
			
		//get all the values to setup account
        $username = $_POST['username'];
		$email = $_POST['email'];
        $password = $_POST['password'];
		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$age = $_POST['age'];
		$gender = $_POST['gender'];
		$taste = $_POST['taste'];
		//should we ask them for a bio on signup??
		$bio = "Say something about yourself.";
		
		//use this to see if file is actually an image, modify this
		// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		//quote them for safety
		$username = $db->quote($username);
		$email = $db->quote($email);
		$password = $db->quote($password);
		$firstname = $db->quote($firstname);
		$lastname = $db->quote($lastname);
		$age = $db->quote($age);
		$gender = $db->quote($gender);
		$taste = $db->quote($taste);
		$bio = $db->quote($bio);
		$fileName = $db->quote($fileName);
		
		//check availability
		$usernameCheck = $db->query("Select * from users where username = $username");
		$emailCheck = $db->query("Select * from users where email = $email");
		if($usernameCheck->rowCount() > 0){//if it exists already
			header('location: register.php?validUser=0');
		}else if($emailCheck->rowCount() > 0){
			header('location: register.php?validEmail=0');
		}else{
			$stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES ($username, $email, $password)");
			$stmt->execute();
			
			$getId = $db->query("Select id from users where username = $username");
			$getId = $getId->fetch();
			$id = $getId['id'];
			
			$stmt2 = $db->prepare("INSERT INTO profiles (id,firstname,lastname,age,gender,taste,bio) VALUES ($id,$firstname,$lastname,$age,$gender,$taste,$bio)");
			$stmt2->execute();
			
			//picture--will break if 2 with same name
			if(!is_dir('images/' . $id)){
				mkdir('images/' . $id);
			}
			copy($tmpName, 'images/' . $id . '/profilePic.jpg');
			
			$_SESSION['UserId'] = $getId['id'];
			
			header('location: MainPage.php');
		}
    }else{
    	
    }
	
function alert(){
	if(isset($_GET['validUser'])){
		if($_GET['validUser']== 0){
			echo "Sorry, that username is already being used.";
		}
	}
	
	if(isset($_GET['validEmail'])){
		if($_GET['validEmail']== 0){
			echo "Sorry, that email is already being used.";
		}
	}
}
?>
    
<!DOCTYPE html>
<html>
	<head>
		<link href="shared.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div id="registerDiv">
			<form id="reg" action=<?=curPageURL()?> method="POST" enctype="multipart/form-data">
				<h1>Register</h1>
				<p id="alertBox"><?php
					alert();
				?></p>
				<table>
				<tr>
					<td>First Name: </td>
					<td><input form="reg" type="text" name="firstname" placeholder="First Name"></td>
				</tr>
				<tr>
					<td>Last Name: </td>
					<td><input form="reg" type="text" name="lastname" placeholder="Last Name"></td>
				</tr>
				<tr>
					<td>Profile&nbsp;Picture: </td>
					<td>
					<input form="reg" name="userfile" type="file" id="userfile"> 
					</td>
				</tr>
				<tr>
					<td>Age: </td>
					<td><select form="reg" name="age">
						<?php
							for($i = 18 ; $i < 101; $i++){
								?>
									<option <?php if($i == 18){ echo 'selected = \"selected\"';}?> value= <?=$i?> ><?=$i?></option>
								<?php
							}
						?>
				</select></td>
				</tr>
				<tr>
					<td>Gender: </td>
					<td><select form="reg" name="gender">
						<option selected="selected" value="M">Male</option>
						<option value="F">Female</option>
					</select></td>
				</tr>
				<tr>
					<td>Interested in: </td>
					<td><select form="reg" name="taste">
						<option value="M">Men</option>
						<option selected="selected" value="F">Women</option>
					</select></td>
				</tr>
				<tr>
					<td>Username: </td>
					<td><input form="reg" id="username" type="text" name="username" placeholder="Username" /></p></td>
				</tr>
				<tr>
					<td>E-Mail: </td>
					<td><input form="reg" id="email" type="email" name="email" placeholder="Email"/></p></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td><input form="reg" id="password" type="password" name="password" placeholder="Password" /></p></td>
				</tr>
				<tr>
					<td form="reg" colspan="2"><button type="submit" name="submit"/>Register</button></td>
				</tr>
				<tr>
					
				</tr>
				<tr>
					<td colspan="2">Already registered? <a href="LoginPage.php"> Login</a></td>
				</tr>
				</table>
		    </form>
		</div>
	</body>
</html>