<?php
//Connect to Database
include 'connect.php';

//Setup
$cr =	$conn->query("SELECT * FROM ratings");
$totalResults = $cr->num_rows;
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
$offset = $section * $resultsPerPage;
$sql = "SELECT id, setRating FROM ratings ORDER BY setRating DESC LIMIT ? OFFSET ?";
if(!($statement = $conn->prepare($sql))){
   echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
} else {
	if(!$statement->bind_param("ii",$resultsPerPage,$offset)){
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
if (!$statement->fetch()) {
	echo "Fetch failed: (" . $statement->errno . ") " . $statement->error;
} else {
	//Card nav
	displayCardNav($resultsPerPage,$totalResults);
	//Start cards
	$num = ($section+1) * $resultsPerPage - $resultsPerPage + 1; 
	do {
	echo '<div id="standingCard">';
	echo "<h2>#".$num."</h2> ";
	echo '<div id="center"> <img src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' . $fetchID . '&type=card" >';
	echo "</div></div>";
	$num++;
	} while ($statement->fetch());
	displayCardNav($resultsPerPage,$totalResults);
}

function displayCardNav($rpp, $total){
	echo '<div id="sectionsbot">';
	echo '<table><tbody><tr>';
	if ($total < $rpp){
		echo '<th><a href="standings.php?s=0&rpp=' . $rpp . '">1-' . $total .'</a></th>';
	} else {
		echo '<th><a href="standings.php?s=0&rpp='.$rpp.'">1-' . $rpp . '</a></th>';
		for ($i = 1; ($i+1)*$rpp < $total; $i++){
			echo '<th><a href="standings.php?s='.$i.'&rpp='.$rpp.'">' . ($i * $rpp + 1) .'-'. ($i + 1)*$rpp .'</a></th>';
		}
	
	echo '<th><a href="standings.php?s='.$i.'&rpp='.$rpp .'">' . ($i * $rpp + 1) .'-'. $total .'</a></th>';
	}
	echo '</tr></tbody></table>';
	echo '</div>';
}

?>