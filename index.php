<!DOCTYPE html>

<html>
<head>
<script type="text/javascript">
function refreshCards (cardID,set,callback) {
	var dx = window.pageXOffset || document.documentElement.scrollLeft;
	var dy = window.pageYOffset || document.documentElement.scrollTop;
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState ==4 && this.status == 200) {
			document.getElementById("cardsToCompare").innerHTML = this.responseText;
			console.log("Updated Cards");
			setTimeout(function(){window.scrollTo(dx,dy);}, 300);
			callback();
		}
	};
	xhttp.open("POST", "updaterating.php", true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send("cardClicked=" + cardID + "&set=" + set);
}
</script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Vote for your favorite cards and see which one rises to the top.">
	<meta name="keywords" content="standings, mtg, mtgstanding, vote, voting, community, rank, ranking, magic cards, magic the gathering, black lotus, magic: the gathering, wizards of the coast, wizards, trading card game, trading cards, collectible card game, tcg, ccg, magic sets, game, multiplayer, hobby">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>MTGS - Vote</title>
<?php
require './subRoutines.php';
$set = "AER";
getNewRandomCards($set);
?>

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
 <p><span>Magic:&nbsp;The&nbsp;Gathering&nbsp;is&nbsp;™&nbsp;&&nbsp;©&nbsp;2015&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	<span>MTGStandings.com&nbsp;is&nbsp;NOT&nbsp;affiliated&nbsp;with&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	Find&nbsp;a&nbsp;problem?&nbsp;Email:phoenix7700@gmail.com</p>
</footer>
</html>
