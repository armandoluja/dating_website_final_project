<?php
session_start();
include('db.php');

if(isset($_SESSION['UserId']) && isset($_POST['bioText'])){
	$unquotedId = $_SESSION['UserId'];
	$unquotedText = $_POST['bioText'];

	
	$id = $db->quote($unquotedId);
	$text = $db->quote($unquotedText);
	
	$updateQuery = "UPDATE profiles set bio = $text where id= $id;";
	
	$db->exec($updateQuery);
	
}

?>