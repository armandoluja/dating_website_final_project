<?php
/**
 * Returns the actual url of this webpage
 */
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

/**
 * Creates the div that contains the contents of the "Profile"
 * section
 */
function createProfileDiv($db, $profile){//add pictures later
	$info = $profile->fetch();
	$id = $info['id'];
	?>
		<div id="profileDiv" class="content">
			<p>Hello <?= $info['firstname']?>!</p>
			<div class="picture" id="profilePicture">
				<img src="<?='images/'. $id . '/profilePic.jpg'?>" class="bigPic" />
			</div>
			<?php
				echo "<p> Age: ". $info['age'] ."</p>";
				echo "<p> Gender: ". $info['gender']." </p>";
				echo "<p id='bioText'> Bio: ". $info['bio'] ."</p>";
			?>
			<button id="editProfileButton">Edit Profile</button>
		</div>
		<div id="editProfileDiv" class="content">
			<table>
				<tr>
					<td><input id="newBio" type="text" placeholder="Enter a new bio here" /></td>
					<td><button onclick="changeBio();">Update Bio</button></td>
				</tr>
			</table>
		</div>
	<?php
}

/**
 * Creates the div that contains the content of the "Matches"
 * section
 */
function createMatchesDiv($db, $id){
	//the following lines get the profile info of this user's matches
	$id = $db->quote($id);
	$sql = "select profiles.id,firstname,lastname,age,gender,bio from profiles join matches on matches.id_2 = profiles.id\n"
    . "where matches.id = $id";
	$matchesRows = $db->query($sql);
	?>
		<div id="matchesDiv" class="content">
			<p>View Matches</p>
			<button onclick="viewDateCards();">View DateCards</button>
			<table id="matchesTable">
			<?php
				//echo html below with matches
				foreach($matchesRows as $match){
					createMatchPreview($match, $id, $db);
				}
			?>
			</table>
		</div>
		<div id="messagesDiv" class="content">
			<!-- messages go here -->
		</div>
		<div id="viewDateCardDiv" class="content">
			<?php
			$dateCardSQL = "Select * from Datecards where user2ID = $id;";
			$dateCards = $db->query($dateCardSQL);
			
			if($dateCards->rowCount() > 0){
				
				foreach($dateCards as $card){
					$fromId = $card['user1ID'];
					$fromId = $db->quote($fromId);
					$fromUser = "Select firstname, lastname from profiles where id=$fromId;";
					$fromUser = $db->query($fromUser);
					$fromUser = $fromUser->fetch();
					$time = $card['Time'];
					$place = $card['Place'];
					$date = $card['CalendarDate'];
					$msg = $card['Message'];
					$phone = $card['Phone'];
					$fname = $fromUser['firstname'];
					$lname = $fromUser['lastname'];
					?>
						<div class="datecardDisplay">
							<p>From: <?= $fname ?> <?= $lname ?></p>
							<p>Time: <?= $time ?></p>
							<p>Place: <?= $place ?></p>
							<p>Date: <?= $date ?></p>
							<p><?= $msg ?></p>
							<p>Call me if you are interested: <?= $phone ?></p>
						</div>
					<?php
				}
			}
			?>
		</div>
	<?php
}

function createMatchPreview($match, $userId, $db){
	$matchId = $match['id'];
	// echo $id;
	?>
		<tr id="M_<?= $match['id']?>">
			<td class="matchTableTd">
				<img class="matchPreviewPicImg" src=<?='images/' . $match['id'] . '/profilePic.jpg' ?> alt="Profile Picture"/>
			</td>
			<td class="matchTableTd">
				<p><?= $match['firstname']?></p>
			</td>
			<td class="matchTableTd">
				<button class='chat'>Chat</button>
			</td>
			<td class="matchTableTd">
				<?php 
				$temp = $db->query("select Accepted from Datecards where ((user1ID = $userId and user2ID = $matchId) 
					or (user2ID = $userId and user1ID = $matchId))");
				if ($temp->rowCount() > 0) {
					?>
					<button class = "dateCardButton" disabled>Date Pending</button>
					<?php
				} else {
					?>
					<button class = "dateCardButton">Request a Date</button>
					<?php
				}
				?>
			</td>
		</tr>
	<?php
}

function createBrowseDiv($db, $id){
	?>
		<div id="browseDiv" class="content">
			<p>Browse other users</p>
			<?php
				$id = $db->quote($id);
				$tasteRow = $db->query("Select taste from profiles where id = $id");
				$taste = $tasteRow->fetch();
				$taste = $taste['taste'];
				$taste = $db->quote($taste);
				//now we know which gender to show
				//if we have time to do pages in the browse tab: only get n profiles per page
				$profilesToShow = $db->query("select * from profiles where gender=$taste");
				foreach($profilesToShow as $profile){
					createProfileForBrowseDiv($db, $profile);
				}
				//the div below will be used to show another users profile if you click it
			?>
		</div>
		<div id="viewOtherProfileDiv" class="content">
			
		</div>
	<?php
}

function createProfileForBrowseDiv($db, $profileInfo){
	//load their picture into a div, show their name, age, bio or w/e
	?>
	<div class="profilePreview" id="PP_<?= $profileInfo['id'] ?>">
		<img class="profilePreviewPicImg" src=<?='images/' . $profileInfo['id'] . '/profilePic.jpg'?> alt="Profile Picture" />
		<p><?= $profileInfo['firstname']?></p>
		<p><?= $profileInfo['age']?></p>
	</div>
	<?php
}

function createDateCardDiv() {
	//Creates the form for the date card to be submitted to DateCard.php
	?>
	<div id ="dateCardDiv" class ="content">
		<h1>Welcome to the Date Card!</h1>
		<h2>You've made it this far...now take it home!</h2>
		<form id = "dateCardForm" action = "DateCard.php" method = "post">
			When would you like to schedule this date? <br />Date:
			<input type = "text" name = "date" placeholder = "MM/DD/YY"/><br /><br />
			Time:<input type = "text" name = "time" placeholder = "2:30pm"/><br /><br />
			Place:<input type = "text" name = "place" placeholder = "M. Moggers"/><br /><br />
			Phone Number:<input type="text" name = "phone" placeholder="(###)###-####" /><br /><br />
			What is the plan for this date? <br /><textarea name = "message" rows = "10" cols = "25">
			</textarea>
			<br /><br />
			<input type = "submit" class = "dateCardSubmitButton" value = "Try my luck"/>
		</form>
	</div>
	
	<?php
}


//call this function from main page to load up the website
function generateContent(){
	include ("db.php");
	if(isset($_SESSION["UserId"])){
		$idVal = $_SESSION["UserId"];
		$id = $db->quote($idVal);
		//load all info needed to create profile page, and matches pages here.
		$profileRows = $db->query("Select * from profiles where profiles.id = $id");
		$matchesRows = $db->query("Select id_2 from matches where matches.id = $id");
		$interestedRows = $db->query("Select interested from interested where interested.id = $id");
		//generate the hidden divs
		createProfileDiv($db, $profileRows);
		createMatchesDiv($db, $idVal);
		createBrowseDiv($db,$idVal);//this function should know who is browsing
		createDateCardDiv($db,$idVal);
		//display the default div
		//USE JAVASCRIPT
	}else{
		//if they aren't logged in, send them to login page
		header('location: LoginPage.php');
	}
}
?>