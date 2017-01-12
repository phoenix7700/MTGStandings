<?php 
function getNewRandomCards() {
include 'connect.php';

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

if (isset($_GET['set'])){
	$set = $_GET['set'];
} else {
	$set = 'KLD';
}
$sql = "SELECT id FROM card WHERE collection = ?";
if(!($statement = $conn->prepare($sql))){
   echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
} else {
	if(!$statement->bind_param("s",$set)){
			 echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
		}
	if (!$statement->bind_result($fetchID)){
			echo "Binding result failed: (" . $statement->errno.")" . $statement->error;
	}
	if (!($statement->execute())) {
			echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
		}
}

$cardIDs = $statement->get_result();
//var_dump($cardIDs);
$length = $cardIDs->num_rows;
$rand = rand(0,$length);
$rand2 = rand(0,$length);
while ($rand == $rand2){
	$rand2 = rand(0,$length);
}

$cardIDs->data_seek($rand);
$row = $cardIDs->fetch_assoc();
$cardIDs->data_seek($rand2);
$row2 = $cardIDs->fetch_assoc();

$conn->close();



$_SESSION['leftCard'] = $row['id'];
$_SESSION['rightCard'] = $row2['id'];
}

//K value will slowly decrease as total comparisons goes up to 50
//after 50 comparisons K will be 10.
function calcKValue ($wins,$losses){
$tot = $wins + $losses;
if ($tot >= 50){
	return 10;
}
$dif = ((-0.01 * ($tot*$tot) + 25)) ;
$tot = $dif + 10;
return $tot;
}
?>