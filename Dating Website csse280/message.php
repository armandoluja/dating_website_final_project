

<?php
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

include('db.php');
session_start();


if(isset($_POST['matchId']) && isset($_SESSION['UserId'])){
	$unquotedMatch = $_POST['matchId'];
	$unquotedId = $_SESSION['UserId'];
	
	$id = $db->quote($unquotedId);
	$match = $db->quote($unquotedMatch);
	
	//get name of other
	$nameOBJ = $db->query("Select firstname, lastname from profiles where id=$match;");
	$name = $nameOBJ->fetch();
	$firstname = $name['firstname'];
	$lastname = $name['lastname'];
	
	//get chat
	$chatIDobj = $db->query("Select chatID from chats where ((user1 = $id and user2 = $match ) or (user2 = $id and user1 = $match ));");
	if($chatIDobj->rowCount() > 0){
		$chatID_temp = $chatIDobj->fetch();
		$chatID = $chatID_temp['chatID'];
		?>
		<table class="chatTable" id="Message_<?=$chatID_temp['chatID']?>" rows="31" cols="2" id='messageArea'>
			<tr>
				<td><?= $firstname . " " . $lastname ?></td>
				<td>You</td>
			</tr>
			<?=getMessages($chatID_temp);?>
		</table>
		<input id="messageText" type="text" name="messageArea"></input>
	<?php
		echo "<button id='btn_send' onclick=sendMessage($chatID); > Send </button>";
	}else{
		echo "<p> No messages to display, say something! </p>";
	}


} else{
	echo "<p>derp</p>";
}


//gets all the messages between two people
function getMessages($chatID) {
	include('db.php');
	$num = $chatID['chatID'];
	$subQuery = "SELECT * FROM messages WHERE chatID = $num Order by messages.messageID DESC Limit 30";
	$messageQuery = $db->query("SELECT * FROM ($subQuery) m Order by m.messageID ASC");
	if($messageQuery->rowCount() > 0){
		foreach($messageQuery as $row){
			$message = $row['message'];
			$user = $row['fromID'];
			if($user == $_SESSION['UserId']){ //sender is the other person
			?>
				<tr>
					<td></td>
					<td id=<?= $user ?>><div class="message, yourMessage"><?= $message ?></div></td>
				</tr>
			<?php
			} else{ ?>
				<tr>
					<td id=<?= $user ?>><div class="message, theirMessage"><?= $message ?></div></td>
					<td></td>
				</tr>
			<?php
			}
		}
	}
}

?>
