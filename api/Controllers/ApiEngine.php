<?php
require_once ('../api/model/DataBase.php');
require_once ('../api/Configuration/ConstantAPI.php');

class APIEngine {

    private $apiControllersName;
    private $params;
    public  $callJson = "";

    //$apiControllersName - название API и параметры в формате users/123/ad
    //$apiTypeMethod - тип запроса
    function __construct($apiControllersName, $apiTypeMethod) {
        $apiControllersName = preg_split('/\//', $apiControllersName);
        $apiControllersName = array_slice($apiControllersName, 2, count($apiControllersName) - 2);
        $this->params = array_slice($apiControllersName, 1, count($apiControllersName) -1 );
        $this->apiControllersName = $apiControllersName[0];

    }

    //Создаем JSON ответа
    //TODO: Реализовать ответ под каждый контроллер и метод
    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

    function createJson($callJson){
        return json_encode($callJson);
    }

    //Вызов функции по переданным параметрам в конструкторе
    function callApiFunction(){
        $callJson = "";
        $controllerName = ucfirst($this->apiControllersName);//В названии контроллера меняем первую букву
        $path = $controllerName.'Controllers.php';
        $file_name = '../api/Controllers/'.$path;

        header('Content-Type: application/json');

        if (file_exists($file_name)) {
            $apiClass = APIEngine::getApiEngineByName($file_name, $controllerName);

            $methodName = array_shift($this->params);//Получаем имя метода

            if (method_exists($controllerName,$methodName)) {
                $apiClass = $controllerName::$methodName();
                $callJson = $apiClass;
            } else {
                $callJson->error_text = 'Method not found';
                $callJson->error_num = 1;

            }
            echo $this->createJson($callJson);
            return;
        } else {
            $callJson->error_text = 'File Controller not found';
            $callJson->path = $path;
            $callJson->error_num = 1;
        }

        echo $this->createJson($callJson);
        return;
    }

    //Статичная функция для подключения API из других API при необходимости в методах
    static function getApiEngineByName($file_name, $controllerName) {
        require_once $file_name;
        $apiClass = new $controllerName();
        return $apiClass;
    }
}