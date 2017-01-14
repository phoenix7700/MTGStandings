<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>MTGS - Standings</title>
<script>
function refreshStanding (section,rpp) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState ==4 && this.status == 200) {
			document.getElementById("cardsToCompare").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "updaterating.php?s=" . section . "&rpp=" . rpp, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send();

}
</script>
</head>
<body >
<div id="wrapper">
<?php include 'nav.php';
 include_once("analyticstracking.php") ?>
<div id="title">
<img id="title" src="Title.png" alt="MtG Standing" />
</div>
<h1>Current Standings</h1>
<div id="standings">
<?php include 'updatestanding.php' ?>


</div>
</div>
</body>
<footer>
 <p>Magic: The Gathering is ™ & © 2015 Wizards of the Coast&nbsp;&nbsp;&nbsp;&nbsp;MTGStandings.com is unaffiliated with Wizards of the Coast</p>
</footer>
</html>
