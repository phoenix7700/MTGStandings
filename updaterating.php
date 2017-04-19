<!DOCTYPE html>
<?php
require 'subRoutines.php';
//Connect to Database
include 'connect.php';
//Get POST info------------------------------------------------------------------------------------
if (isset($_POST['cardClicked'])){
$cardClicked = $_POST['cardClicked'];
if (isset($_POST['set'])){
	$set = $_POST['set'];
} else {
	$set = 'SETMISSING';
}
session_start();
$leftCard = $_SESSION ['leftCard'];
$rightCard = $_SESSION ['rightCard'];
if ($cardClicked == $leftCard){
	$loser = $rightCard;
} else if ($cardClicked == $rightCard){
	$loser = $leftCard;
} else{
	echo "<p>Something messed up please e-mail phoenix7700@gmail.com :(". $cardClicked .",". $leftCard .",". $rightCard .")</p>";
}

//UPDATE Card Ratings in Database-----------------------------------------------------------------
$sql = "SELECT * FROM ratings WHERE id IN (?)"; // get rating entry for clicked cardClicked
if(!($statement = $conn->prepare($sql))){
	    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
	}
if(!$statement->bind_param("i",$cardClicked)){
	     echo "Binding winner parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
if (!$statement->bind_result($fetchID,$fetchSetRating,$fetchAllRating,$fetchWins,$fetchLosses)){
		echo "Binding winner result failed: (" . $statement->errno.")" . $statement->error;
}
if (!($statement->execute())) {
        echo "Execute winner failed: (" . $statement->errno . ") " . $statement->error;
    }
	if ($statement->fetch() != true) { //if winning card doesn't have rating entry make new entry
//		echo "Created new entry for " . $cardClicked . "<br />";
		$sql = "INSERT INTO ratings (id, setRating, overallRating) VALUES (?, 1500, 1500)";
		if(!($statement = $conn->prepare($sql))){
	    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
		}
		if(!$statement->bind_param("i",$cardClicked)){
				 echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
		if (!($statement->execute())) {
				echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
		$sql = "SELECT * FROM ratings WHERE id IN (?)"; // query server again after creating new entry
		if(!($statement = $conn->prepare($sql))){
	    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
			}
		if(!$statement->bind_param("i",$cardClicked)){
				 echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
			}
		if (!$statement->bind_result($fetchID,$fetchSetRating,$fetchAllRating,$fetchWins,$fetchLosses)){
				echo "Binding result failed: (" . $statement->errno.")" . $statement->error;
		}
		if (!($statement->execute())) {
				echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
			}
		$statement->fetch();
	}
	
$clickedRatings = $fetchSetRating;
$clickedWins = $fetchWins;
$clickedLoss = $fetchLosses;
if(!$statement->bind_param("i",$loser)){ //bind loser parameter then query again
	echo "Binding loser parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
if (!$statement->bind_result($fetchID,$fetchSetRating,$fetchAllRating,$fetchWins,$fetchLosses)){
	echo "Binding loser result failed: (" . $statement->errno.")" . $statement->error;
}
if (!($statement->execute())) {
	echo "Execute loser failed: (" . $statement->errno . ") " . $statement->error;
}

	if($statement->fetch() != true){ //if losing card doesn't have rating entry make new entry
//		echo "Created new entry for " . $loser . "<br />";
		$sql = "INSERT INTO ratings (id, setRating, overallRating) VALUES (?, 1500, 1500)";
		if(!($statement = $conn->prepare($sql))){
			echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
		}
		if(!$statement->bind_param("i",$loser)){
			echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
		if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
		$sql = "SELECT * FROM ratings WHERE id IN (?)"; // get rating entry for loser
		if(!($statement = $conn->prepare($sql))){
			echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
		}
		if(!$statement->bind_param("i",$loser)){
			echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
		if (!$statement->bind_result($fetchID,$fetchSetRating,$fetchAllRating,$fetchWins,$fetchLosses)){
				echo "Binding result failed: (" . $statement->errno.")" . $statement->error;
		}
		if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
		
		$statement->fetch();
	}

$loserRatings = $fetchSetRating;
$loserWins = $fetchWins;
$loserLoss = $fetchLosses;

$statement->close();
	
// USED IN NON-PREPARED STATEMENTS
//$clickedSQL->data_seek(0);
//$clickedRatings = $clickedSQL->fetch_assoc(); //get clicked card rating data as associative array
//$loserSQL->data_seek(0);
//$loserRatings = $loserSQL->fetch_assoc(); //get loser card rating data as associative array
//$clickedSQL->close();
//$loserSQL->close();

// Formula used to update ratings.
//
//	R(a): clicked card rating
//	R(b): losing card rating
//	E(a): expected win rate of clicked card
//	E(b): expected win rate of losing card
//	Q(a): 10^(R(a)/400)
//	Q(b): 10^(R(b)/400)
// 	K: maximum possible adjustment (calculated in function)
//	
//	E(a) = Q(a) / (Q(a)+Q(b))
//	E(b) = Q(b) / (Q(a)+Q(b))
//
$Qa = pow(10,($clickedRatings/400));
$Qb = pow(10,($loserRatings/400));
$Ea = $Qa / ($Qa + $Qb);
$Eb = $Qb / ($Qa + $Qb);
$K = calcKValue($clickedWins,$clickedLoss);
$loserK = calcKValue($loserWins,$loserLoss);

$newClickedRating = $clickedRatings + $K * (1-$Ea);
$newLoserRating = $loserRatings + $loserK * (0-$Eb);
settype($newClickedRating, "integer");
settype($newLoserRating, "integer");
/*echo '<pre>';
var_dump($Qa);
var_dump($Qb);
var_dump($Ea);
var_dump($Eb);
var_dump($newClickedRating);
var_dump($newLoserRating);
echo '</pre>';*/

//UPDATE RATINGS FOR WINNER AND LOSER
//Winner
$sql = "UPDATE ratings SET setRating = ? WHERE id = ?";
	if(!($statement = $conn->prepare($sql))){
		echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
	}
	if(!$statement->bind_param("ii",$newClickedRating,$cardClicked)){
		echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
	if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
//loser
	if(!$statement->bind_param("ii",$newLoserRating,$loser)){ //bind new parameters and query again
		echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
	if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
	
//INCREMENT WIN/LOSS COUNTERS
//Winner	
$sql = "UPDATE ratings SET wins = wins+1 WHERE id = ?";
	if(!($statement = $conn->prepare($sql))){
		echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
	}
	if(!$statement->bind_param("i",$cardClicked)){
		echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
	if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
	//Loser
$sql = "UPDATE ratings SET losses = losses+1 WHERE id = ?";
	if(!($statement = $conn->prepare($sql))){
		echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
	}
	if(!$statement->bind_param("i",$loser)){ 
		echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
	if (!($statement->execute())) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
	}
$conn->close();
} else {
echo "didn't get clicked card";
}
//Get new random cards in Session
getNewRandomCards($set);
//Echo New Cards to be displayed
$c1 =  $_SESSION['leftCard'];
$c2 =  $_SESSION['rightCard'];

echo '<input id="compcardleft" type="image" name="cardClicked" onerror="checkIfCardImagesLoaded()" onclick="refreshCards(this.value,\''.$set.'\')" value="' . $c1 . '" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' . $c1 . '&type=card" />
	<input id="compcardright" type="image" name="cardClicked" onerror="checkIfCardImagesLoaded()" onclick="refreshCards(this.value,\''.$set.'\')" value="' . $c2 . '" src="http://gatherer.wizards.com/Handlers/Image.ashx?multiverseid=' . $c2 . '&type=card" />';

?>