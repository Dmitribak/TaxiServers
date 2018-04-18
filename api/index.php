<?php
header('Content-type: text/html; charset=UTF-8');
$jsonError = null;

if (count($_REQUEST)>0) {
    //TODO: Проверка токена, Проверка на пользователя


    require_once 'Controllers/ApiEngine.php';
    $type = $_SERVER['REQUEST_METHOD'];
    $url = $_SERVER['REQUEST_URI'];

    $ApiEngine = new APIEngine($url, $type);
    $ApiEngine->callApiFunction();

} else
{
    require_once 'Configuration/ConstantAPI.php';
    $jsonError->error='No function called';
    $jsonError->error_num=ConstantAPI::$ERROR_NUM;
    echo json_encode($jsonError);
}

// ПРИМЕР: HTTP://название сервера/api/название контроллера/название метода?параметры