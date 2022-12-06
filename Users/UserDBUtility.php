<?php


class UserDBUtility {

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
            return $result->fetchall();;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function createUser($user) {
        try{
            return self::insert('INSERT INTO users (firstName,lastName,email) VALUES(?,?,?)',[$user->firstName, $user->lastName, $user->email]);;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function getUserID($email) {
        try{
            if (!isset($email)) return;
            $stmt = self::$connection->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user=$stmt->fetch();
            return $user['userID'];
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    static function getProductID($description) {
        try{
            if (!isset($description)) return;
            $stmt = self::$connection->prepare('SELECT * FROM products WHERE description = :description');
            $stmt->execute(['description' => $description]);
            $product=$stmt->fetch();
            return $product['productID'];
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    //step 1 of submit cart: get userID and create the order in Orders table
    function insertOrder($status='ordered', $email) {
        try{
            $userID = self::getUserID($email);
            if (!isset($userID)) return false;
            $lastID = self::insert('INSERT INTO orders (orderStatus,userID) VALUES(?,?)',['ordered', $userID]);
            return $lastID;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    //step 2 of submit cart: insert the order items into Order Products table
    function insertOrderProduct($product, $orderID) {
        try{
            $stmt = self::insert('INSERT INTO orderproducts (orderID,productID,quantity) VALUES(?,?,?)',[$orderID, $product['productID'], $product['quantity']]);
            return true;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    //step 3 of submit cart: update the available quantity for the product in Products table
    function updateAvailableQuantity($product) {
        try{
            $sql = "UPDATE products SET availableQuantity = availableQuantity - ? WHERE productID = ?";
            $stmt=self::$connection->prepare($sql)->execute([$product['quantity'], $product['productID']]);
            return true;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
}