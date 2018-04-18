<?php

require_once '../api/Configuration/ConfigurationApi.php';
class DataBase extends ConfigurationApi
{
    private $DB_host;
    private $DB_name;
    private $DB_user;
    private $DB_password;
    private $DB;


    function __construct()
    {
        $this->DB_host = ConfigurationApi::DBHost;
        $this->DB_name = ConfigurationApi::DBName;
        $this->DB_user = ConfigurationApi::DBUser;
        $this->DB_password = ConfigurationApi::DBPassword;
        $this->openConnection();
    }


    public function openConnection(){
        try
        {
            $this->DB = new PDO('mysql:host='.$this->DB_host.';dbname='.$this->DB_name,$this->DB_user,$this->DB_password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DB->exec("set names utf8");
            return $this->DB;
        } catch (PDOException $e){
            echo 'Not Connect'. $e->getMessage();
        }
    }

    //Закрывает соеденение
    public function closeDataBase(){
        $DB = null;
    }

    //Вставка данных
    //$where - название таблицы
    //$what - массив с параметрами
    public function insertWhat($where, $what){
        $DB = $this->DB;
        $query = 'INSERT INTO '.$where;
        $cols = array();
        $values = array();

        foreach ($what as $key => $val){
            array_push($cols, $key);
            array_push($values, "'".$val."'");
        }
        $query = $query.'('.join(',', $cols).') VALUES('.join(',', $values).')';
        $stmt = $DB->prepare($query);
        if ($stmt->execute())
            return 1;
        else
            return 0;
    }


    //Проверяет наличие того или иного элемента в таблице
    //$what - что искать
    //$from - где искать
    //$where - условие для поиска
    public function select_Available($what=array(), $from, $where=null){
        $DB = $this->DB;
        $cols = "";
        if ($what!='*'){
            for ($i=0; $i<count($what);$i++){
                $cols=$cols.", `".$what[$i]."`";
            }
            $cols = substr($cols,1);
        }

        if ($where){
            $values = "";
            $k = count($where);

                if ($k>1) {
                    foreach ($where as $key => $val){
                        $values = $values." AND ".$key."=".$val;
                    }
                    $values = substr($values,4);
                } else {
                    foreach ($where as $key => $val) {
                        $values = "`".$key."`=".$val;
                }
            }
            $zap = 'SELECT '.$cols.' FROM `'.$from.'` WHERE ';
            $zap = $zap.$values;
        } else {
            $zap = 'SELECT'.$cols.'FROM'.$from;
        }
        $stmt = $DB->prepare($zap);
        $stmt->execute();
        $name = $stmt->fetchColumn();
        if ($name)
            return 0;
        else
            return 1;
    }




    //Получить список чего либо
    //$what - что получить
    //$from - откуда получить
    public function getListWhat($what = '*', $from, $where_stolb = null, $where_value = null){
        $DB = $this->DB;
        if ($where_stolb) {
            $stmt = $DB->prepare('SELECT `'.$what.'`FROM`'.$from);
        }else {
            $stmt = $DB->prepare('SELECT `'.$what.'`FROM`'.$from.'`WHERE`'.$where_stolb.'`=`'.$where_value);
        }



        $stmt = $DB->prepare('SELECT `'.$what.'`FROM`'.$from.'`WHERE`'.$where_stolb.'`=`'.$where_value);
    }


    private function params_array($what = array()){
        $stolbec = null;
        $stolb_name = null;
        foreach ($what as $key => $value){
            $stolbec = $stolbec.','.$key;
            $stolb_name = $stolb_name.','.$value;
        }
        $stolbec = substr($stolbec,1);
        $stolb_name = substr($stolb_name, 1);
    }
}