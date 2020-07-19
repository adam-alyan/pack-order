<?php

  // php code is wrapped in <?php tags

// array holding pack sizes
$arrayPacks = [250, 500, 1000, 2000, 5000];
// sorting array desc
arsort($arrayPacks);

// test
$tests = [1, 250, 251, 501, 12001, 15251];

foreach($tests as $test){
  echo "Testing: " . $test . "\n";
  //$packsToSend = packOrder_refactored($test, $arrayPacks);
  $packsToSend = packOrder($test, $arrayPacks);
  
  print_r($packsToSend);
  echo "\n \n";
}


function packOrder_refactored($requestNoWidgets, $arrayPacks){
  $packsToSend = array();
  
  // number of widgets smaller than the smallest pack
  $smallestPack = end($arrayPacks);                    
  if($requestNoWidgets <= $smallestPack){
    $packsToSend[$smallestPack] = 1;
    return $packsToSend;
  }
  
  $remainingWidgetsToPack = $requestNoWidgets;
  
  foreach($arrayPacks as $key => $packsize){
    
    // skip if pack size wrongly set to a negative number
    if($packsize <= 0)continue;
    
    // skip if pack is too big
    $remainder = $requestNoWidgets % $packsize;    
    if($remainder === $remainingWidgetsToPack)continue;
    
    
    if(array_key_exists($key-1, $arrayPacks)){
      $diff = $packsize - $arrayPacks[$key-1];
    }
    
//     //check if two smaller packs is more effeicent (too many widgets))      
//     if(array_key_exists($key-2, $arrayPacks)){
//       echo "2nd if \n";
//       $sumOfTwoSmallerPacks = $arrayPacks[$key-1] + $arrayPacks[$key-2];
//       //$diff = $packsize - $arrayPacks[$key-1];
//       if ($remainingWidgetsToPack < $packsize && $remainingWidgetsToPack < $sumOfTwoSmallerPacks  ){
//         $packsToSend = addPack($arrayPacks[$key-1] , $packsToSend);
//         $packsToSend = addPack($arrayPacks[$key-2] , $packsToSend);
//         $remainingWidgetsToPack = $remainingWidgetsToPack - $sumOfTwoSmallerPacks;
//       }
//     }    
    
    if($remainder > 1){
      if($remainingWidgetsToPack > $packsize ){
        echo "division: " . $remainingWidgetsToPack / $packsize . "\n";
        echo "division: " . intdiv($remainingWidgetsToPack, $packsize) . "\n";
        $numOfPackstoAdd = intdiv($remainingWidgetsToPack, $packsize);
        $packsToSend = addPack($packsize , $packsToSend, $numOfPackstoAdd);
        $remainingWidgetsToPack = $remainingWidgetsToPack - ($packsize * $numOfPackstoAdd);
        echo "3rd if add " . $packsize . " x " . $numOfPackstoAdd .  "\n";
        continue;
      }
    }
    
    // add the bigger pack to order if the size is between two packs
    if(array_key_exists($key+1, $arrayPacks)){    
      if($remainingWidgetsToPack > $packsize && $remainingWidgetsToPack < $arrayPacks[$key+1] && $remainingWidgetsToPack > $diff ){      
        $packsToSend = addPack($arrayPacks[$key+1] , $packsToSend);
        $remainingWidgetsToPack = $remainingWidgetsToPack - $arrayPacks[$key+1];
        echo "4th if add " . $arrayPacks[$key+1] . "\n";
        continue;
      }
    }
    
    

 
    
    echo $requestNoWidgets . " % " . $packsize  . " remainder = " . $remainder . "\n";
  }
  return $packsToSend;
}

function addPack($pack, $packsToSend, $number = 1){
  if(array_key_exists($pack, $packsToSend)) { 
    $packsToSend[$pack] = $packsToSend[$pack] +$number;
  } else{
    $packsToSend[$pack] = $number;
  }
  return $packsToSend;
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
    //echo "size is: " . $size . "\n";
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
          //echo "1st if \n";
          return $onePackSmallerSize;
        }              
      }    
      
      // check for two many widget & too many packs 
      // if:
      // 1) This size is not the smallest
      // 2) (remaining number) is smaller than current pack size 
      // 3) (remaining number) is bigger than the difference between the (current size) and (the one size below)      
      if($remainingWidgets <= $size && $remainingWidgets > $packDiff){
        //echo "2nd if \n";
        return $size;
      }
    }    
    
    // Return the biggest as long as the remaining widgets is bigger than the biggest pack
    if($remainingWidgets >= $size){
      //echo "3rd if \n";
      return $size;
    }
    
    // set the size to the smallest pack if all the above conditions are not met
    $leastPackSize = $size;
  }
  
  //echo "4th Condition \n";
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
