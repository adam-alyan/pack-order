<?php

  // php code is wrapped in <?php tags

// array holding pack sizes
$arrayPacks = [250, 500, 1000, 2000, 5000];
// sorting array desc
arsort($arrayPacks);

// test
$tests = [1, 250, 251, 501, 12001, 15251];

foreach($tests as $test){
  $packsToSend = packOrder($test, $arrayPacks);
  echo "Testing: " . $test . "\n";
  print_r($packsToSend);
  echo "\n \n";
}

// Main function 
function packOrder($requestNoWidgets, $arrayPacks){
  $packsToSend = array();
  $remainingWidgets = $requestNoWidgets;
    
  while($remainingWidgets > 0){
    
    $nextpacksize = getNextPackSize2($remainingWidgets, $arrayPacks);
    $lastpacksize = $nextpacksize;
    $remainingWidgets = $remainingWidgets - $nextpacksize;
    
        
    if(array_key_exists($nextpacksize, $packsToSend)) { 
      $packsToSend[$nextpacksize] = $packsToSend[$nextpacksize] +1;
    } else{
      $packsToSend[$nextpacksize] = 1;
    }
  } 
  
  return $packsToSend;
}


// Get the correct next pack size for the order
// Loop through array packs desc. 
// 
function getNextPackSize2($remainingWidgets, $arrayPacks){  
  foreach($arrayPacks as $key => $size){
    echo "size is: " . $size . "\n";
    $onePackSmallerKey = $key-1;
    $twoPackSmallerKey = $key-2;
  
    if ( $onePackSmallerKey >= 0 ){
      $onePackSmallerSize = $arrayPacks[$onePackSmallerKey];
      $packDiff = $size - $onePackSmallerSize;
      
      if ( $twoPackSmallerKey >= 0 ){
        $twoPackSmallerSize = $arrayPacks[$twoPackSmallerKey];
        $SumOfSizeOfTwoSmallerPacks = $onePackSmallerSize + $twoPackSmallerSize;
        
        // check for two many packs
        // if:
        // 1) This size is not the smallest
        // 2) (remaining number) is smaller than current pack size 
        // 3) (remaining number) is bigger than the difference between the (current size) and (the one size below)
        // 4) (remaining number) can be satisfied by the sum of the two smaller packs
        if($remainingWidgets <= $size && $remainingWidgets > $packDiff && $remainingWidgets < $SumOfSizeOfTwoSmallerPacks ){
          echo "1st if \n";
          return $onePackSmallerSize;
        }              
      }    
      
      // check for two many widget & too many packs 
      // if:
      // 1) This size is not the smallest
      // 2) (remaining number) is smaller than current pack size 
      // 3) (remaining number) is bigger than the difference between the (current size) and (the one size below)      
      if($remainingWidgets <= $size && $remainingWidgets > $packDiff){
        echo "2nd if \n";
        return $size;
      }
    }    
    
    // Return the biggest as long as the remaining widgets is bigger than the biggest pack
    if($remainingWidgets >= $size){
      echo "3rd if \n";
      return $size;
    }
    
    // set the size to the smallest pack if all the above conditions are not met
    $leastPackSize = $size;
  }
  
  echo "4th Condition \n";
  return $leastPackSize;
}




// Get the correct next pack size for the order
function getNextPackSize($remainingWidgets, $arrayPacks){
  foreach($arrayPacks as $key => $size){
    
    $onePackSmallerKey = $key-1;
    $twoPackSmallerKey = $key-2;
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
    
    if($remainingWidgets >= $size){
      echo "3rd if \n";
      return $size;
    }
    
    $leastPackSize = $size;
  }
  
  echo "4th Condition \n";
  return $leastPackSize;
}






?>
