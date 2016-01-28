<?php
session_start();

if(isset($_SESSION['UserId'])) {
	
	if (isset($_POST['date'])
	&& isset($_POST['time'])
	&& isset($_POST['place'])
	&& isset($_POST['message'])
	&& isset($_POST['user2Id'])
	&& isset($_POST['phone'])) {
		include "db.php";
		print("the form data has been received and the database included");
		$user1IDUnquote = $_SESSION['UserId'];
		$dateUnquote = $_POST['date'];
		$timeUnquote = $_POST['time'];
		$placeUnquote = $_POST['place'];
		$messageUnquote = $_POST['message'];
		$user2Id = $_POST['user2Id'];
		$phoneUnquote = $_POST['phone'];
	
		//Quote for safety
		$user1Id = $db->quote($user1IDUnquote);
		$date = $db->quote($dateUnquote);
		$time = $db->quote($timeUnquote);
		$place = $db->quote($placeUnquote);
		$message = $db->quote($messageUnquote);
		$phone = $db->quote($phoneUnquote);
	
		//Insert the info from the date card into the Datecards table
		$possibleDate = "INSERT INTO Datecards (user1ID, user2ID, Time, Place, CalendarDate, Message, Phone)
				values ($user1Id, $user2Id, $time, $place, $date, $message, $phone);";
		$db->exec($possibleDate);
	}
}

?>