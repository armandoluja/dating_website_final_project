<?php
session_start();

if(isset($_POST['idToLike']) && isset($_SESSION['UserId'])){
	include('db.php');
	$userIdUnquote = $_SESSION['UserId'];
	$otherIdUnquote = $_POST['idToLike'];
	$userId = $db->quote($userIdUnquote);
	$otherId = $db->quote($otherIdUnquote);
	
	//check if match exists
	$checkMatches = "Select * from matches where (id = $userId and id_2=$otherId) 
	 OR (id = $otherId and id_2=$otherId);";
	 //there is a better way to do this, by always storing the smallest id first
	// we can make the query simpler (no OR statement), add to things we'd change
	// same thing for the Interested table...
	$checkMatches = $db->query($checkMatches);
	
	//checks if the other user is interested in this user (if rowcount > 0)
	$checkIfOtherIsInterested = "Select * from interested where (id = $userId and interested = $otherId);";
	$checkIfOtherIsInterested = $db->query($checkIfOtherIsInterested);
	
	if($checkMatches->rowCount() > 0){
	 	//they are already a match
	 	//do nothing
	}else{
	 	//they are not a match yet
	 	//check if other is interested
	 	$createInterest = "INSERT INTO interested (id,interested) values ($otherId, $userId);";
	 	$createMatch1 = "Insert into matches (id, id_2) values ($userId , $otherId);";
	 	//do both ways because it makes
	 	//the queries easier on the main page when you generate matches tab
		$createMatch2 = "Insert into matches (id, id_2) values ($otherId , $userId);";
		$createChat = "Insert into chats (user1, user2) values ($userId, $otherId);";
	 	if($checkIfOtherIsInterested->rowCount() > 0){
	 		//create an interest and a match!
			$db->exec($createInterest);
			$db->exec($createMatch1);
			$db->exec($createMatch2);
			$db->exec($createChat);
			//
	 	}else{
	 		//create an interest!
	 		$db->exec($createInterest);
	 	}
	}
}else{
	//redirect to login page if not logged in
	header('location: LoginPage.php');
}
?>