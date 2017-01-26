<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Vote for your favorite cards and see which one rises to the top.">
	<meta name="keywords" content="standings, mtg, mtgstanding, vote, voting, community, rank, ranking, magic cards, magic the gathering, black lotus, magic: the gathering, wizards of the coast, wizards, trading card game, trading cards, collectible card game, tcg, ccg, magic sets, game, multiplayer, hobby">
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
 <p><span>Magic:&nbsp;The&nbsp;Gathering&nbsp;is&nbsp;™&nbsp;&&nbsp;©&nbsp;2015&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	<span>MTGStandings.com&nbsp;is&nbsp;NOT&nbsp;affiliated&nbsp;with&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	Find&nbsp;a&nbsp;problem?&nbsp;Email:phoenix7700@gmail.com</p>
</footer>
</html>
