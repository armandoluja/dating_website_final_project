"use-strict";

window.onload = function() {
	var usernameField = document.getElementById("username");
	var passwordField = document.getElementById("password");
	usernameField.onclick = highlightField;
	passwordField.onclick = highlightField;
};

function highlightField() {
	this.style.boxShadow = "0 0 5px red";
  	this.style.padding = "3px 0px 3px 3px";
  	this.style.margin = "5px 1px 3px 0px";
  	this.style.border = "1px solid red";
};
