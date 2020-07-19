<?php

  // php code is wrapped in <?php tags

// array holding pack sizes
$packsArray = [250, 500, 1000, 2000, 5000];
// sorting array desc
arsort($packsArray);

// test
$tests = [1, 250, 251, 501, 12001, 15251];
 
foreach($tests as $test){
  echo "Testing: " . $test . "\n";
  $packsToSend = packOrder($test, $packsArray);
  print_r($packsToSend);
  echo "\n \n";
}


function packOrder($requestNoItems, $packsArray){
  // if no items return empty array 
  if($requestNoItems <= 0) return array();
  
  // if order is smaller than the smallest pack size, return one of the smallest pack size
  $smallestPack = end($packsArray);
  $largestPack = $packsArray[0];
  if($requestNoItems <= $smallestPack) return array( "$smallestPack" => 1);
  
  
  $initialPack = getInitialPackSize($requestNoItems, $packsArray);
  $remainder = $initialPack['numOfPacks'];
  
  $packsToSend = array();
  
  foreach($packsArray as $packSize){ 
    // number of packs of this size
    $numOfPacks = floor($remainder * $initialPack['packSize'] / $packSize );
    // remainder as number of pack sizes
    $remainder = $remainder - $numOfPacks * $packSize / $initialPack['packSize'];
    // add to order array
    if($numOfPacks != 0) $packsToSend[$packSize] = $numOfPacks;    
  }
  return $packsToSend;
}

// Return the packs size & number of packs based on extra items in pack
function getInitialPackSize($noItems, $packs){
  $initialPacks= array();
  foreach($packs as $pack){
    $numOfPacks = ceil($noItems/$pack);
    $extraItemsInPacks = ( $numOfPacks * $pack) - $noItems;
    $initialPacks[$extraItemsInPacks] = array('packSize' => $pack, 'numOfPacks' => $numOfPacks);    
  }
  arsort($initialPacks);
  return end($initialPacks);
}


?>
