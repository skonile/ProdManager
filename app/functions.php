<?php
declare(strict_types = 1);

namespace App\Functions;

/**
 * checks of a string a is empty or 
 * contains an empty string.
 * 
 * @param string $str The string to be checked
 * @return bool True is it is empty or false otherwise
 */
function isStrEmpty(string $str): bool{
    $str = trim($str);
    if(strlen($str) == 0) return true;
    return false;
}

/**
 * Filters an array and returns an array if strings only.
 * 
 * @param array $arr The array to be filtered
 * @return array<string> The string array
 */
function getArrayOfStrings(array $arr){
    $temp = [];
    foreach($arr as $element){
        if(is_string($element)){
            $temp[] = $element;
        }
    }
    return $temp;
}

/**
     * Check if all the elements in the array are valid URLs
     * 
     * @param array $arr The array to check
     * @return bool True if all elements are valid URLs 
     *              or false otherwise
     */
    function hasValidURLs(array $arr): bool{
        foreach($arr as $element){
            if(!isValidURL($element)) return false;
        }
        return true;
    }

    /**
     * Check if a string is a valid URL.
     * 
     * @param string $url The string to check
     * @return bool True if it is a valid url or false otherwise
     */
    function isValidURL(string $url): bool{
        if(\filter_var($url, FILTER_VALIDATE_URL) === false){
            return false;
        }
        return true;
    }