<?php

function validateTaks($string, $repeat, $frequency, $algoritm_hash, $sol){
    if ($string == null or $repeat == null or $frequency == null
    or $algoritm_hash == null or $sol == null){
        return 1;
    }
    if (gettype($repeat) != 'integer' or gettype($frequency) != 'integer' 
           or $repeat <= 0 or $frequency <= 0){
        return 2;
    }
    if (in_array($algoritm_hash, hash_algos()) == false){
        return 3;
    }
    // if (strlen($name) >= 100){ algoritm соответсвтие в бд писать, не писать
    //     return true;
    // }
    return 0;

}



?>