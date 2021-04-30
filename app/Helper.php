<?php
const BUYER_ID = 1;
const PHOTOGRAPHER_ID = 2;

if (!function_exists('photographerId')) {
    function photographerId():int
    {
        return PHOTOGRAPHER_ID;
    }
}

if (!function_exists('buyerId')) {
    function buyerId():int
    {
        return BUYER_ID;
    }
}

  if (!function_exists('getFile')) {
      function getFile(string $fileString):string
      {
          return  explode(config('app.url'), $fileString)[1];
      }
  }
