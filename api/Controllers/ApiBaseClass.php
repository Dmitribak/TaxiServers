<?php
/**
 * Created by PhpStorm.
 * User: dmitr
 * Date: 19.03.2018
 * Time: 10:41
 */

class ApiBaseClass
{
    function createJson($callJson){
        return json_encode($callJson);
    }
}