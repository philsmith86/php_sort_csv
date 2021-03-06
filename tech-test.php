<?php

ini_set('memory_limit', '4098M');
ini_set('max_execution_time', '360');

$csvData = array_map('str_getcsv', file('testdata.csv'));

//Check for headers and remove first line - not sure how reliable this is
if (!Is_Numeric($csvData[0][0])){
    array_shift($csvData);
}

function mergeSort(&$arrayToSort)  
{      
    if (sizeof($arrayToSort) <= 1) {
        return $arrayToSort;  
    }

    $leftFrag = array_slice($arrayToSort, 0, (int)(count($arrayToSort)/2));  
    $rightFrag = array_slice($arrayToSort, (int)(count($arrayToSort)/2));  

    $leftFrag = mergeSort($leftFrag);  
    $rightFrag = mergeSort($rightFrag);  
  
    $returnArray = merge($leftFrag, $rightFrag);  
  
    return $returnArray;  
}   

function merge(&$lF, &$rF)  
{  
    $result = array();  
  
    while (count($lF)>0 && count($rF)>0) {  
        if ($lF[0] <= $rF[0]) {  
            array_push($result, array_shift($lF));  
        }  
        else {  
            array_push($result, array_shift($rF));  
        }  
    }  

    array_splice($result, count($result), 0, $lF);  
    array_splice($result, count($result), 0, $rF);  
  
    return $result;  
} 

//Sort the array into ascending order
$csvData = mergeSort($csvData);

//Returns total of values in the array
function getTotal($arr){
    $total = 0;
    for ($i = 0; $i<sizeof($arr); $i++){
        $total += $arr[$i][1];
    }
    return $total;
}

//Returns mean of value passed
function getMean($total, $count){
    return $total/$count;
}

//Returns median of array
function getMedian($arr){
    $l = sizeOf($arr);
    
    if ($l%2 == 0){
        return ($arr[$l*.5][1]+$arr[($l*.5)-1][1])/2;
    } else {
        return $arr[$l*.5][1];
    }
}

//Returns most common occurance in an array
function getMode($arr){
    
    $occurances = array();
    
    for ($i = 0; $i<sizeof($arr); $i++){
        $key = (string)$arr[$i][1];
        if($occurances[$key]){
            $occurances[$key] += 1;
        } else {
            $occurances[$key] = 1;
        }
    }

    //Find most common
    $highestNumber;
    $highestkey;

    foreach ($occurances as $key=>$value){
        if ($value>$highestNumber){
            $highestNumber = $value;
            $highestKey = $key;
        }
    }
    return array($highestKey, $highestNumber);
}

$response = array();

$total = getTotal($csvData);
$mode = getMode($csvData);

$response["total"] = round($total, 2);
$response["mean"] = round(getMean($total,sizeof($csvData)), 2);
$response["modal"] = array(round($mode[0], 2));
$response["frequency"] = $mode[1];
$response["median"] = round(getMedian($csvData), 2);

echo json_encode($response);

?>