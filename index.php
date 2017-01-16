<!DOCTYPE html>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>MTGS - Vote</title>
<?php
require './subRoutines.php';
$set = "AER";
getNewRandomCards($set);
?>
<script type="text/javascript">
function refreshCards (cardID,set) {
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState ==4 && this.status == 200) {
			document.getElementById("cardsToCompare").innerHTML = this.responseText;
		}
	};
	xhttp.open("POST", "updaterating.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("cardClicked=" + cardID + "&set=" + set);
	console.log("Updated Cards");

}
</script>
</head>
<body>
<div id="wrapper">
<?php include 'nav.php'; 
include_once("analyticstracking.php") ?>
<div id="title">
<img src="Title.png" alt="MtG Standing" />
</div>
<div id="whichset">
<table>
<tr>
	<th><a href="index.php?set=KLD"><div id="imgkld">KLD</div></a></th>
	<th><a href="index.php?set=AER"><div id="imgaer">AER</div></a></th>
</tr>
</table>
</div>
<h1> Which card is more awesome? </h1>
<div id="cardsToCompare">
	<input id="compcard" type="image" name="cardClicked" onclick="refreshCards(this.value,<?php if (isset($_GET['set'])){echo "'".$_GET['set']."'";} else {/*echo '%';*/echo "'".$set."'";} ?>)" value="<?php echo $_SESSION['leftCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['leftCard']?>&type=card" />
	<input id="compcard" type="image" name="cardClicked" onclick="refreshCards(this.value,<?php if (isset($_GET['set'])){echo "'".$_GET['set']."'";} else {/*echo '%';*/echo "'".$set."'";} ?>)" value="<?php echo $_SESSION['rightCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['rightCard']?>&type=card" />
</div>
</div>
</body>
<footer>
 <p>Magic: The Gathering is ™ & © 2015 Wizards of the Coast&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MTGStandings.com is unaffiliated with Wizards of the Coast&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Find a problem? Email:phoenix7700@gmail.com</p>
</footer>
</html>
