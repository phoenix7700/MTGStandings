<!DOCTYPE html>

<html>
<head>
<script type="text/javascript">
var imgLeft = new Image();
var imgRight = new Image (); 

function checkIfCardImagesLoaded () {
	imgLeft.src	= document.getElementById("compcardleft").src;
	imgRight.src = document.getElementById("compcardright").src;
	console.log("left " + imgLeft.src + " " + imgLeft.width);
	console.log("right " + imgRight.src + " " + imgRight.width);
	if (imgLeft.width == 0 || imgRight.width == 0){
		var t = document.getElementById('cardsToCompare')
		t.innerHTML = "<h3>There was an error loading the card images</h3>"
	}
}

function refreshCards (cardID,set,callback) {
	var dx = window.pageXOffset || document.documentElement.scrollLeft;
	var dy = window.pageYOffset || document.documentElement.scrollTop;
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	if (this.readyState ==4 && this.status == 200) {
			document.getElementById("cardsToCompare").innerHTML = this.responseText;
			checkIfCardImagesLoaded();
			console.log("Updated Cards");
			setTimeout(function(){window.scrollTo(dx,dy);}, 300);
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
$set = "AKH";
getNewRandomCards($set);
include_once("analyticstracking.php")
?>
</head>
<body>

<div id="wrapper">
<?php include 'nav.php'; ?>
<div id="title">
<img src="Title.png" alt="MtG Standing" />
</div>
<div id="whichset">
<table>
<tr>
	<th><a href="index.php?set=AKH"><div id="imgakh">AKH</div></a></th>
	<th><a href="index.php?set=MM3"><div id="imgmm3">MM3</div></a></th>
	<th><a href="index.php?set=AER"><div id="imgaer">AER</div></a></th>
	<th><a href="index.php?set=KLD"><div id="imgkld">KLD</div></a></th>
</tr>
</table>
</div>
<h1> Which card is more awesome? </h1>
<div id="cardsToCompare">
	<input id="compcardleft"  type="image" name="cardClicked" onerror="checkIfCardImagesLoaded()" onclick="refreshCards(this.value,<?php if (isset($_GET['set'])){echo "'".$_GET['set']."'";} else {/*echo '%';*/echo "'".$set."'";} ?>)" value="<?php echo $_SESSION['leftCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['leftCard']?>&type=card" />
	<input id="compcardright" type="image" name="cardClicked" onerror="checkIfCardImagesLoaded()" onclick="refreshCards(this.value,<?php if (isset($_GET['set'])){echo "'".$_GET['set']."'";} else {/*echo '%';*/echo "'".$set."'";} ?>)" value="<?php echo $_SESSION['rightCard']?>" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=<?php echo $_SESSION['rightCard']?>&type=card" />
</div>
</div>
<script> /*This script must be here for the checkIfCardImagesLoaded to work properly. If not the img.width will always be 0 */
	imgLeft.src	= document.getElementById("compcardleft").src;
	imgRight.src = document.getElementById("compcardright").src;
</script>
</body>
<footer>
 <p><span>Magic:&nbsp;The&nbsp;Gathering&nbsp;is&nbsp;™&nbsp;&&nbsp;©&nbsp;2015&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	<span>MTGStandings.com&nbsp;is&nbsp;NOT&nbsp;affiliated&nbsp;with&nbsp;Wizards&nbsp;of&nbsp;the&nbsp;Coast</span>	Find&nbsp;a&nbsp;problem?&nbsp;Email:phoenix7700@gmail.com</p>
</footer>
</html>
