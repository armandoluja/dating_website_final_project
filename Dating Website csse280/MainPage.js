"use-strict";
var profileDiv;
var browseDiv;
var matchesDiv;
var viewOtherProfileDiv;
var messagesDiv;// chat area
var dateCardRecipId;
var clickedDateCardButton;
var editProfileDiv;
var currentChatID;
var viewDateCardDiv;

window.onload= function(){
	//start by showing only the profile div
	profileDiv = $("#profileDiv");// the only thing that shows initially
	
	
	//all theses others are hidden initially
	browseDiv = $("#browseDiv");
	matchesDiv = $("#matchesDiv");
	viewOtherProfileDiv = $("#viewOtherProfileDiv");
	messagesDiv = $("#messagesDiv");
	dateCardDiv = $("#dateCardDiv");
	editProfileDiv = $("#editProfileDiv");
	viewDateCardDiv = $("#viewDateCardDiv");
	
	browseDiv.css("display","none");
	matchesDiv.css("display","none");
	viewOtherProfileDiv.css("display","none");
	messagesDiv.css("display","none");
	dateCardDiv.css("display","none");
	editProfileDiv.css("display","none");
	viewDateCardDiv.css("display","none");
	
	// end of hidden divs
	
	
	//set the navigation buttons to display
	//the div they correspond to, when they
	//are clicked
	$(".navbar").click(function(){
		hideAll();
		var id = $(this).attr('id');
		var str = "#"+id+"Div";
		$(str).css("display","");
	});
	
	$(".profilePreview").click(function(){
		hideAll();
		var str = $(this).attr('id');//id's look like "PP_id"
		str = str.split("_")[1];//split at the underscore and get id
		viewProfile(str);
		//add a "interested button" and give it on click event
		//so that it updates the tables
		viewOtherProfileDiv.css("display","");//shows the div
	});
	
	$(".matchPreviewPicImg").click(function(){
		hideAll();
		var closestTr = $(this).closest('tr'); //gets the tr so that we can get the id
		var str = closestTr.attr('id');//id's look like "M_id"
		str = str.split("_")[1];//split at the underscore and get id
		viewProfile(str);
		viewOtherProfileDiv.css("display","");//shows the div
	});
	
	$(".chat").click(function(){
		hideAll();
		var closestTr = $(this).closest('tr'); //gets the tr so that we can get the id
		var str = closestTr.attr('id');//id's look like "M_id"
		str = str.split("_")[1];//split at the underscore and get id
		console.log(str);
		currentChatID = str;
		viewMessages(str);
		messagesDiv.css("display","");//shows the div
	});
	
	
	
	$(".dateCardButton").click(function() {
		hideAll();
		clickedDateCardButton = $(this);
		// clickedDateCardButton.hide();
		console.log(clickedDateCardButton);
		var closestTr = $(this).closest('tr'); //gets the tr so that we can get the id
		var str = closestTr.attr('id');//id's look like "M_id"
		dateCardRecipId = str.split("_")[1];//split at the underscore and get id
		dateCardDiv.css("display","");
	});
	
	$("#dateCardForm").submit(function(event) {
		var formData = {
			"date": $('input[name="date"]').val(),
			"time": $('input[name = "time"]').val(),
			"place": $('input[name = "place"]').val(),
			"message": $('textarea[name = "message"]').val(),
			"user2Id": dateCardRecipId,
			"phone": $('input[name="phone"]').val()
		};
		$.ajax({
			type: "POST",
			url: "DateCard.php",
			data: formData
		})
		.success(function(data) {
			$("#dateCardForm")[0].reset();
			console.log(data);
			clickedDateCardButton.prop('disabled', true);
			clickedDateCardButton.text('Date Pending');
			hideAll();
			matchesDiv.css("display",""); //show the matches Div after Datecard submit
			
		});
		event.preventDefault();
	});
	
	var editProfileButton = $("#editProfileButton");
	editProfileButton.click(function(){
		hideAll();
		editProfileDiv.css("display","");
	});
	
	
	
};

function viewDateCards(){
	hideAll();
	viewDateCardDiv.css("display","");
}


function hideAll(){
	$(".content").css("display","none");
}

function viewMessages(id){
	//use ajax here to get profile contents
	if (id == "") {
		//invalid id to chat with 
		messagesDiv.html("Invalid User");
        return;
    } else { 
        $.ajax({
			type : "POST",
			url : "message.php",
			data : {
				'matchId' : id
			}
		}).success(function(returnData) {
			messagesDiv.html(returnData);
		});
    }
}

function viewProfile(id){
	//use ajax here to get profile contents
	if (id == "") {
		viewOtherProfileDiv.html("Invalid User");
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
               	viewOtherProfileDiv.html(xmlhttp.responseText);
            }
        };
        //create post request, use post because
        //we dont want people to be able to view others profiles
        //by changing get params
        
        xmlhttp.open("POST","getProfile.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var postVals = "profileId="+id;
        console.log(postVals);
        xmlhttp.send(postVals);
    }
}

function like(id){
	$.ajax({
		type:"POST",
		url:"like.php",
		data:{
			'idToLike':id
		},
		success: function(){
			//alert("liked!");
		}
	});
}


function sendMessage(chatID){
	textBox = $("#messageText");
	var text = textBox.val();
	console.log("text:"+ text);
		//use ajax here to get profile contents
	if (text.trim() == "") {
        return;
    } else {
        $.ajax({
			type : "POST",
			url : "sendMessage.php",
			data : {
				'messageText' : text,
				'chatId' : chatID
			}
		}).success(function() {
			viewMessages(currentChatID);
		});
    }
}


function changeBio(){
	var bioText = $("#newBio");
	var text = bioText.val();
		//use ajax here to get profile contents
	if (text.trim() == "") {
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
               	bioText.val("");//clear the text to send area
            }
        };
        
        $("#bioText").text("Bio: "+text);
        
        xmlhttp.open("POST","changeBio.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        var postVals = "bioText="+text;
        xmlhttp.send(postVals);
        
        //goes back to profile
        hideAll();
        $("#profileDiv").css("display","");
    }
}
