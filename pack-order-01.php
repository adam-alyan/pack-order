<?php

$arrayPacks = [250, 500, 1000, 2000, 5000];
arsort($arrayPacks);
$requestNoWidgets = 251;
$packsToSend = efficientPackSizes($requestNoWidgets, $arrayPacks);

print_r($packsToSend);

function efficientPackSizes($requestNoWidgets, $arrayPacks = array()){
  $packsToSend = array();
  $remainingWidgets = $requestNoWidgets;
    
  while($remainingWidgets > 0){
    // $lastpacksize = 500;
    // if($remainingWidgets < $lastpacksize && $remainingWidgets > $arrayPacks[$key-1]){
    // return $size;
    // }
    
   $needBig = putInBigPack($remainingWidgets, $arrayPacks);
    
    if($needBig){
      $packsToSend[] = $needBig;
    
    } else {
    
     $nextpacksize = getPackSize($remainingWidgets, $arrayPacks);
     $lastpacksize = $nextpacksize;
     $remainingWidgets = $remainingWidgets - $nextpacksize;
     $packsToSend[] =  $nextpacksize;
    }
  } 
  
  return $packsToSend;
  
}

function putInBigPack($remainingWidgets, $arrayPacks){
   foreach($arrayPacks as $key => $size){
    if($remainingWidgets <= $size && $remainingWidgets > $arrayPacks[$key-1] ){
      return $size;
    }
   }
  return false;
}
     
function getPackSize($remainingWidgets, $arrayPacks){
  foreach($arrayPacks as $key => $size){

    
    if($remainingWidgets >= $size ){
      return $size;
    }
    $leastPackSize = $size;
  }
  return $leastPackSize;
}

?>