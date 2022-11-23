<?php


class UserDBUtility {

    static $connection=null;

    //create new connection if one doesn't exist
    static function connect($host,$db,$user,$pass,$port,$charset){
        if(isset(self::$connection)) return self::$connection;
        $options=[
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES=>false,
        ];
        try{
            self::$connection=new \PDO("mysql:host=$host;dbname=$db;charset=$charset;port=$port",$user,$pass, $options);
            return true;
        }catch(\PDOException $e){
            throw new \PDOException($e->getMessage(),(int)$e->getCode());
            return false;
        }
        return false;
    }

    static function query($query_string){
        return self::$connection->query($query_string);
    }

    static function prepared_query($query_string,$data=[]){
        $query=self::$connection->prepare($query_string);
        $query->execute($data);
        return $query;
    }

    static function insert($query_string,$data=[]){
        $query=self::$connection->prepare($query_string);
        $query->execute($data);
        return self::$connection->lastInsertId();
    }

    function getProducts()
    {
        $products = array();
        try {
            $result = self::query('SELECT * FROM products');
            $products = $result->fetchall();
            return $products;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
}