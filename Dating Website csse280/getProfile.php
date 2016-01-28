<?php
session_start();

if(isset($_POST['profileId']) && isset($_SESSION['UserId'])){
	include('db.php');
	$thisIDUnquoted = $_SESSION['UserId'];
	$idUnquoted = $_POST['profileId'];
	$thisID = $db->quote($thisIDUnquoted);
	$id = $db->quote($idUnquoted);
	$sql = "select * from profiles where profiles.id = $id";
	$profileData = $db->query($sql);
	$info = $profileData->fetch();
	?>
	<div>
		<?php
		$pic = $db->query("select ProfilePic from pictures where id=$id");
		$pic = $pic->fetch();
	
		$like = $db->query("select * from interested i where i.interested = $thisID and i.id= $id");
		?>
		<img src=<?='images/'. $idUnquoted . '/profilePic.jpg'?> class="bigPic" />
		<?php
		echo "<p> Name: ".$info['firstname']."</p>";
		echo "<p> Age: " . $info['age'] . "</p>";
		echo "<p> Gender: " . $info['gender'] . " </p>";
		echo "<p> Bio: " . $info['bio'] . "</p>";
		if($like->rowCount() == 0){
			echo "<button onclick=like($id); > Like! </button>";
		}
		?>
	</div>
	<?php
	}else{
		//redirect to login page if not logged in
		header('location: LoginPage.php');
	}
?>