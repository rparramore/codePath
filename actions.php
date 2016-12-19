<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    // find function to call
    $action = $_POST['action'];
    //var_dump($_POST);
    if(isset($action) && $action === "computeTotal") {
        $subtotal = (double)$_POST['subtotal'];
        $tip = (double)$_POST['tip'] / 100.0;
        if(isset($subtotal) && gettype($subtotal) === "double" && 
           isset($tip) && gettype($tip) === "double") {
            header("content-type:application/json");
            echo json_encode(computeTotal($subtotal, $tip));
        } else {
           echo "Something isn't set";
        }        
    } else if(isset($action) && $action === "splitTotal") {
        
        $subtotal = (double)$_POST['subtotal'];
        $tip = (double)$_POST['tip'] / 100.0;
        $numPeople = (int)$_POST['numPeople'];
        
        //echo $tip.":".$subtotal.":".($subtotal * $tip);
        if(isset($subtotal) && gettype($subtotal) === "double" && 
           isset($tip) && gettype($tip) === "double" && 
           isset($numPeople)) {
            header("content-type:application/json");
            echo json_encode(splitTotal($subtotal, $tip, $numPeople));
        } else {
           echo "Something isn't set";
        }
    } else {
        echo $action." not valid action!";
    }
    
    
    // computes the total of meal
    // assumes that subtotal & tip are set
    function computeTotal($subtotal, $tip) {
        $tipAmount = $subtotal * $tip;
        $result = array('tipAmount' => round($tipAmount, 2), 'total' => round($subtotal + $tipAmount, 2));
        return $result;
    }
    
    // computes the total of meal
    // assumes that subtotal, tip, & numPeople are set
    function splitTotal($subtotal, $tip, $numPeople) {
        $temp = computeTotal($subtotal, $tip);
        $tipAmountSplit = $temp['tipAmount'] / $numPeople;
        $totalSplit = $temp['total'] / $numPeople;
        $result = array('tipAmountSplit' => round($tipAmountSplit, 2), 'totalSplit' => round($totalSplit, 2));
        return array_merge($temp, $result);
    }
   
?>



