<?php
session_start();
include('db.php');

if(isset($_POST['messageText']) && isset($_POST['chatId']) && isset($_SESSION['UserId'])){
	$unquotedMessage = $_POST['messageText'];
	$unquotedchatID = $_POST['chatId'];
	$unquotedId = $_SESSION['UserId'];
	
	$id = $db->quote($unquotedId);
	$chatID = $db->quote($unquotedchatID);
	$message = $db->quote($unquotedMessage);
	
	$sendMessage = "insert into messages (chatID, message, fromID) values ($chatID , $message , $id );";
	$db->exec($sendMessage);
}

?>
