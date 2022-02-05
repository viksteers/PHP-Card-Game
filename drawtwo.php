<?php
session_start();
require 'vendor/autoload.php';
$client = new \GuzzleHttp\Client();
$response = $client->request('GET', 'https://deckofcardsapi.com/api/deck/new/shuffle/?deck_count=1');
$response_data = json_decode($response->getBody(), TRUE);


$response2 = $client->request('GET', 'https://deckofcardsapi.com/api/deck/'.$response_data['deck_id'].'/draw/?count=2');
$response_data2 = json_decode($response2->getBody(), TRUE);

$card_array = $response_data2['cards'];
$card_total = calc_card_total($card_array);
$_SESSION['card_array'] = $card_array;
$_SESSION['deck_id'] = $response_data['deck_id'];

function calc_card_total($card_array1){
    $card_value1=["KING"=>10, "QUEEN"=>10, "JACK"=>10,"ACE"=>1, "2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, "7"=>7, "8"=>8, "9"=>9, "10"=>10 ];
    $card_value2=["KING"=>10, "QUEEN"=>10, "JACK"=>10,"ACE"=>11, "2"=>2, "3"=>3, "4"=>4, "5"=>5, "6"=>6, "7"=>7, "8"=>8, "9"=>9, "10"=>10 ];
    $card_total1 = 0;
    $card_total2 = 0;
    $card_face="";
    foreach($card_array1 as $card){
        $card_face = $card['value'];
        $card_total1 = $card_total1 + $card_value1[$card_face];
        $card_total2 = $card_total2 + $card_value2[$card_face];
    }
    if($card_total2 <= 21){
        return $card_total2;
    } else {
        return $card_total1;
    }
 }
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php foreach($card_array as $card) : ?>
       <img src="<?php echo $card['image'];?>">
   <?php endforeach; ?>
   <h1><?php echo "Your card total is $card_total"; ?></h1>

   <?php if($card_total > 21): ?>
       Sorry your total is above 21
       <a href="index.php">Play Again</a>
   <?php elseif($card_total == 21): ?>
       You win, take a trip to Vegas
       <a href="index.php">Play Again</a>
   <?php else: ?>
    Are you feeling lucky?
       <a href="drawagain.php">Draw again</a>
   <?php endif; ?>

</body>
</html>