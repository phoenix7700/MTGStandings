<!DOCTYPE html>
<?php
//
//
// Used to update card database when new sets come out.
// Must update one set at a time.
//
echo "Updating...";
ini_set("allow_url_fopen", 1);
include 'connect.php';
$json = file_get_contents('http://mtgjson.com/json/KLD.json'); // Put URL for JSON file here
$set = json_decode($json, true);
//var_dump($set);
$size = 247; // number of cards in set this should be changed to be dynamic but im lazy
echo $size;


if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
} else {
	if(!($statement = $conn->prepare("INSERT INTO card (id, name, manaCost, cmc, colors, type, text, number, power, toughness, flavor,collection,rarity) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"))){
	    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
	}
	if(!$statement->bind_param("ississsssssss",$pid,$pname,$pmanacost,$pcmc,$pcolor,$ptype,$ptext,$pnumber,$ppower,$ptoughness,$pflavor,$pset,$prarity)){
	     echo "Binding parameters failed: (" . $statement->errno . ") " . $statement->error;
	}
	
	
for($i = 0; $i < $size; $i++){ //Wont work right if size isn't changed per set
	//Get Colors as string
	$allcolors = "";
	$j =0;
	foreach ($set['cards'][$i]['colors'] as $c){
			if ($j>0){
				$allcolors .= ",";
			}
			$allcolors .= $c;
			$j++;
		}
	$pid 		= $set['cards'][$i]['multiverseid']; 
	$pname		= $set['cards'][$i]['name'];
	$pmanacost	= $set['cards'][$i]['manaCost'];
	$pcmc		= $set['cards'][$i]['cmc'];
	$pcolor		= $allcolors;
	$ptype		= $set['cards'][$i]['type'];
	$ptext		= $set['cards'][$i]['text'];
	$pnumber	= $set['cards'][$i]['number'];
	$ppower		= $set['cards'][$i]['power'];
	$ptoughness	= $set['cards'][$i]['toughness'];
	$pflavor	= $set['cards'][$i]['flavor'];
	$pset		= $set['code'];
	$prarity	= $set['cards'][$i]['rarity'];
	unset($allcolors);
	echo "Attempt #" . $i . "</br>";
	if (!$statement->execute()) {
        echo "Execute failed: (" . $statement->errno . ") " . $statement->error;
    }
 }
 $statement->close();
}
$conn->close();
?>
