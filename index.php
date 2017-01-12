<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
<?php
require 'subRoutines.php';
getNewRandomCards();
?>
<script>
function refreshCards (cardID) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState ==4 && this.status == 200) {
			document.getElementById("cardsToCompare").innerHTML = this.responseText;
		}
	};
	xhttp.open("POST", "updaterating.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("cardClicked=" + cardID);

}
</script>
</head>
<body >
<div id="wrapper">
<?php include 'nav.php'; 
include_once("analyticstracking.php") ?>
<div id="title">
<img src="Title.png" alt="MtG Standing" />
</div>
<h1> Which card is more awesome? </h1>
<div id="cardsToCompare">
	<input type="image" name="cardClicked" onclick="refreshCards(this.value)" value="<?php echo $_SESSION['leftCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['leftCard']?>&type=card" />
	<input type="image" name="cardClicked" onclick="refreshCards(this.value)" value="<?php echo $_SESSION['rightCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['rightCard']?>&type=card" />
</div>
</div>
</body>
<footer>
 <p>Magic: The Gathering is ™ & © 2015 Wizards of the Coast&nbsp;&nbsp;&nbsp;&nbsp;MTGStandings.com is unaffiliated with Wizards of the Coast</p>
</footer>
</html>
