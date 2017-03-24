<?php
ini_set('display_errors',1);
//Connect to Database
include 'connect.php';

//Setup
if (isset($_GET['s'])){
	$section = $_GET['s'];
} else {
	$section = 0;
}
if (isset($_GET['rpp'])){
	$resultsPerPage = $_GET['rpp'];
} else {
	$resultsPerPage = 35;
}
if (isset($_GET['set'])){
	$set = $_GET['set'];
} else {
	$set = 'AER'; //Will cause problems if you change to new set
}
$offset = $section * $resultsPerPage;

$sql = "SELECT r.id FROM ratings r INNER JOIN card c ON r.id=c.id WHERE collection LIKE ?";
if(!($statement = $conn->prepare($sql))){
   echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
} else {
	if(!$statement->bind_param("s",$set)){
			 echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
	if (!($statement->execute())) {
			echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
}
$statement->store_result();
$totalResults = $statement->num_rows;
$statement->free_result();
$sql = "SELECT r.id, r.setRating
	FROM ratings r
	INNER JOIN card c
	ON r.id=c.id 
	WHERE c.collection LIKE ? 
	ORDER BY setRating DESC, r.id ASC
	LIMIT ? OFFSET ?";
if(!($statement = $conn->prepare($sql))){
   echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
} else {
	if(!$statement->bind_param("sii",$set,$resultsPerPage,$offset)){
			 echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
	if (!$statement->bind_result($fetchID,$fetchSetRating)){
			echo "Binding result failed: (" . $statement->errno.")" . $statement->error;
	}
	if (!($statement->execute())) {
			echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
}

//Display

//Set Nav
displaySetNav($resultsPerPage);
//Card nav
displayCardNav($resultsPerPage,$totalResults,$set);
if (!$statement->fetch()) {
	echo "Fetch failed: (" . $statement->errno . ") " . $statement->error;
} else {
	//Start cards
	$prevRating = 0;
	$num = ($section+1) * $resultsPerPage - $resultsPerPage + 1;
	$numdif = 0;
	do {
	if($prevRating > $fetchSetRating){
		$num += $numdif;
		$numdif = 1;
	} else {
		$numdif++;
	}
	$prevRating = $fetchSetRating;
	echo '<div id="standingCard">';
	echo "<h2>#".$num."</h2> ";
	echo '<div id="center"> <img src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' . $fetchID . '&type=card" >';
	echo "</div></div>";
	
	} while ($statement->fetch());
	$statement->close();
	$conn->close();
	
}
//End Nav
	displayCardNav($resultsPerPage,$totalResults,$set);
	
//End Code---------------------------------------------------------------------------------------------------------------------------------------------
	
//Functions
function displaySetNav($rpp){
echo '<div id="whichset">
<div class="centerlist">
<table>
<tr>
	<th><a href="standings.php?set=KLD&s=0&rpp='.$rpp.'"><div id="imgkld">KLD</div></a></th>
	<th><a href="standings.php?set=AER&s=0&rpp='.$rpp.'"><div id="imgaer">AER</div></a></th>
	<th><a href="standings.php?set=MM3&s=0&rpp='.$rpp.'"><div id="imgmm3">MM3</div></a></th>
</tr>
</table>
</div>
</div>';
}

function displayCardNav($rpp, $total, $set){
	echo '<div id="sectionsbot">';
	echo '<div class="centerlist">';
	echo '<table><tbody><tr>';
	if ($total < $rpp){
		echo '<th><a href="standings.php?set='. $set .'&s=0&rpp=' . $rpp . '">1-' . $total .'</a></th>';
	} else {
		echo '<th><a href="standings.php?set='. $set .'&s=0&rpp='.$rpp.'">1-' . $rpp . '</a></th>';
		for ($i = 1; ($i+1)*$rpp < $total; $i++){
			echo '<th><a href="standings.php?set='. $set .'&s='.$i.'&rpp='.$rpp.'">' . ($i * $rpp + 1) .'-'. ($i + 1)*$rpp .'</a></th>';
		}
	
	echo '<th><a href="standings.php?set='. $set .'&s='.$i.'&rpp='.$rpp .'">' . ($i * $rpp + 1) .'-'. $total .'</a></th>';
	}
	echo '</tr></tbody></table>';
	echo '</div></div>';
}

?>