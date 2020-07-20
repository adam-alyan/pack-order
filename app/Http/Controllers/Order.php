<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Order extends Controller
{
    //

    public $products = array(
        21 => array( 'name' => 'Widget', 'packs' => array(250, 500, 1000, 2000, 5000)),
        22 => array( 'name' => 'Pencil', 'packs' => array(250, 300))
    );

    public function show($productId) {
        if($this->getPackSizes($productId)){
            return view(
                'order', 
                ['packSizes' => $this->getPackSizes($productId), 
                'productName' => $this->getProductName($productId),
                'productId' => $productId,
                'noItems' => false]
            );
        } else {
            return redirect('/');
        }
    }

    public function store($productId, Request $request){
        if($this->getProductName($productId)){
            $noItems = intval($request->input('noItems'));
            if(is_int($noItems) && $noItems > 0){    
                $packsToSend = $this->packOrder($noItems, $this->getPackSizes($productId));            
                return view(
                    'order', 
                    ['packSizes' => $this->getPackSizes($productId), 
                    'productName' => $this->getProductName($productId),
                    'productId' => $productId,
                    'noItems' => $noItems,
                    'packsToSend' => $packsToSend]
                );
            } else {
                return view(
                    'order',
                    ['packSizes' => $this->getPackSizes($productId), 
                    'productName' => $this->getProductName($productId),
                    'productId' => $productId,
                    'error' => "Please enter a number greater than zero!"]
                );
            }              
        } else {
            return redirect('/');
        }
    }

    protected function getProductName($productId){
        if( isset( $this->products[$productId]['name'] ) ){
            return $this->products[$productId]['name'];
        }  else {
            return false;
        }
    }

    protected function getProducts(){
        return array_keys($this->products);
    }

    protected function getPackSizes( $productId ){
        if( isset( $this->products[$productId]['packs'] ) ){
            return $this->products[$productId]['packs'];
        } else {
            return false;
        }
    }
    
    private function packOrder($requestNoItems, $packsArray){
        arsort($packsArray);
        // if no items return empty array 
        if($requestNoItems <= 0) return array();
        
        // if order is smaller than the smallest pack size, return one of the smallest pack size
        $smallestPack = end($packsArray);
        $largestPack = $packsArray[0];
        if($requestNoItems <= $smallestPack) return array( "$smallestPack" => 1);
        
        
        $initialPack = $this->getInitialPackSize($requestNoItems, $packsArray);
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
}
