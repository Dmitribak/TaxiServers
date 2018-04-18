<?php
require_once 'ApiBaseClass.php';

class Users extends ApiBaseClass
{

//TODO: Функция регистрации пользователя, доделать возврат json
    function registrationPhone(){
        $select = null;
        $post = array();
        if (isset($_POST)){
            foreach ($_POST as $key=>$value) {
                $post[$key]=$value;
            }
        }
        $json = new ApiBaseClass();
        $DB =new DataBase();
        $from = 'users';
        $mas = array('phone_users');
        $select = $DB->select_Available($mas,$from, $post);

        if ($select) {
            if ($DB->insertWhat($from, $post)){
                $json->error = "User Add";
                $json->error_num = "0";
            } else{
                $json->error = "User don`t add";
                $json->error = "1";
            }
            return $json;
        } else {
            $json->error = "User find";
            $json->error_num = "1";
            return $json;
        }
    }

}