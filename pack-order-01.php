<?php

  // php code is wrapped in <?php tags

// array holding pack sizes
$arrayPacks = [250, 500, 1000, 2000, 5000];
// sorting array desc
arsort($arrayPacks);

// test
$tests = [1, 250, 251, 501, 12001, 15251];

foreach($tests as $test){
  $packsToSend = efficientPackSizes($test, $arrayPacks);
  echo "Testing: " . $test . "\n";
  print_r($packsToSend);
  echo "\n \n";
}

// Main function 
function efficientPackSizes($requestNoWidgets, $arrayPacks = array()){
  $packsToSend = array();
  $remainingWidgets = $requestNoWidgets;
    
  while($remainingWidgets > 0){
    
     $nextpacksize = getNextPackSize($remainingWidgets, $arrayPacks);
     $lastpacksize = $nextpacksize;
     $remainingWidgets = $remainingWidgets - $nextpacksize;
     $packsToSend[] =  $nextpacksize;
  } 
  
  return $packsToSend;
  
}


function getNextPackSize($remainingWidgets, $arrayPacks){
  foreach($arrayPacks as $key => $size){
    $onePackSmallerKey = $key-1;
    $twoPackSmallerKey = $key-2;
    //if ( $onePackSmallerKey > 0 && $twoPackSmallerKey >=0 ){
    if ( $onePackSmallerKey >= 0 ){
      $onePackSmallerSize = $arrayPacks[$onePackSmallerKey];
      $packDiff = $size - $onePackSmallerSize;
      if ( $twoPackSmallerKey >= 0 ){
        $twoPackSmallerSize = $arrayPacks[$twoPackSmallerKey];
        $SumOfSizeOfTwoSmallerPacks = $onePackSmallerSize + $twoPackSmallerSize;
        
        if($remainingWidgets <= $size && $remainingWidgets > $packDiff && $remainingWidgets < $SumOfSizeOfTwoSmallerPacks ){
          echo "1st if \n";
          return $onePackSmallerSize;
        }
              
      }

      
      
      if($remainingWidgets <= $size && $remainingWidgets > $packDiff){
        echo "2nd if \n";
        return $size;
      }
    }    
    
    // $smallerPackKey = $key-1;    
    // if ( $smallerPackKey>= 0){
    //   $smallerPackSize = $arrayPacks[$smallerPackKey];
    //   $packDiff = $size - $smallerPackSize;
    //   if($remainingWidgets <= $size && $remainingWidgets > $packDiff ){
    //     echo "1st if \n";
    //     return $size;
    //   }
    // }
    
    if($remainingWidgets >= $size){
      echo "3rd if \n";
      return $size;
    }
    
    $leastPackSize = $size;
  }
  return $leastPackSize;
}






?>
