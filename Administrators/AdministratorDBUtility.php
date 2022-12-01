<?php

class AdministratorDBUtility {

    static $connection=null;

    //create new connection if one doesn't exist
    static function connect(){
        $host = '127.0.0.1';
        $db = 'FooCommerce';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $charset = 'utf8mb4';
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
        try {
            $result = self::query('SELECT * FROM products');
            return $result->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function getUsers()
    {
        try {
            $result = self::query('SELECT * FROM users');
            return $result->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function getUserID($email)
    {
        try {
            if (!isset($email)) return;
            $stmt = self::$connection->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();
            return $user['userID'];
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function getOrder($orderID) {
        try{
            if (!isset($orderID)) return;
            print_r($orderID);
            $stmt = self::$connection->prepare('SELECT * FROM orders WHERE orderID = :orderID');
            $stmt->execute(['orderID' => $orderID]);
            return $stmt->fetch();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function getOrderItems($orderID) {
        try {
            $stmt = self::$connection->prepare('SELECT orders.orderID, orders.orderStatus, products.description, users.email, users.firstName, users.lastName FROM users INNER JOIN orders ON orders.userID = users.userID INNER JOIN orderproducts ON orderproducts.orderID = orders.orderID INNER JOIN products ON products.productID = orderproducts.productID WHERE orders.orderID = :orderID');
            $stmt->execute(['orderID' => $orderID]);
            return $stmt->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function updateOrder($order) {
        /*
        try{
            $sql = "UPDATE orders SET availableQuantity = availableQuantity - ? WHERE productID = ?";
            $stmt=self::$connection->prepare($sql)->execute([$product['quantity'], $product['productID']]);
            return true;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
        */
    }

    //return all orders with their username
    function getOrders() {
        try {
            $result = self::query('SELECT orders.orderID, orders.orderStatus, users.email FROM orders INNER JOIN users ON orders.userID=users.userID;');
            return $result->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

}