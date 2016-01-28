<?php
session_start();
include("generateMainpage.php");
?>
<html>
	<head>
		<title> Rose Students Meet</title>
		<link href="MainPage.css" type="text/css" rel="stylesheet" />
		<link href="shared.css" type="text/css" rel="stylesheet" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="http://malsup.github.com/jquery.form.js"></script> 
		<script src="MainPage.js"> </script>
	</head>
  <body>
  	<div id="mainPageDiv">
	  	<h1>Rose Students Meet</h1>
	  	<div id="navigation">
	  		<div class="navbar" id="browse">Browse</div>
	  		<div class="navbar" id="matches">Matches</div>
	  		<div class="navbar" id="profile">Profile</div>
	  	</div>
	  	<hr class = "horizRule">
	  	<div id="contentArea">
	  		<?php
	  		generateContent();
			?>
	  	</div>
	  	<button><a id="logoutBtn" href="LoginPage.php">Logout</a></button>
	 </div>
  </body>
</html>