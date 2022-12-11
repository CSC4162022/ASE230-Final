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

    static function createAdministrator($admin) {
        try{
            return self::insert('INSERT INTO administrators (firstName,lastName,email) VALUES(?,?,?)',[$admin->firstName, $admin->lastName, $admin->email]);;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function deleteProduct($productID) {
        try{
            $stmt=  self::$connection->prepare('DELETE FROM products WHERE productID = ?');
            return $stmt->execute([$productID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function updateProduct($product) {
        try{
            $sql = "UPDATE products SET description=?, price=?,category=?, visible=?, availableQuantity = ? WHERE productID = ?";
            return self::$connection->prepare($sql)->execute([$product->description, $product->price, $product->category, $product->visible,
                $product->availableQuantity, $product->productID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function deleteOrderProductsByProductID($productID) {
        try{
            $sql = "DELETE FROM orderproducts WHERE orderproducts.productID = ?";
            return self::$connection->prepare($sql)->execute([$productID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function deleteOrdersWithProduct($productID) {
        try{
            $stmt=  self::$connection->prepare('DELETE FROM orders WHERE orders.productID = ?');
            $stmt->execute([$productID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    //get all orders with specified productID
    static function getOrdersWithProductID($productID) {
        try{
            if (!isset($productID)) return;
            $stmt = self::$connection->prepare('SELECT DISTINCT orderID FROM orderproducts WHERE productID = :productID');
            $stmt->execute(['productID' => $productID]);
            return $stmt->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    //update order status
    static function updateOrderStatus($orderID, $status) {
        try{
            $sql = "UPDATE orders SET orderStatus = ? WHERE orderID = ?";
            return self::$connection->prepare($sql)->execute([$status, $orderID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function getProducts() {
        try {
            $result = self::query('SELECT * FROM products');
            return $result->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function getProduct($productID) {
        try {
            if (!isset($productID)) return;
            $stmt = self::$connection->prepare('SELECT * FROM products WHERE productID = :productID');
            $stmt->execute(['productID' => $productID]);
            return $stmt->fetch();;
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    function getUsers() {
        try {
            $result = self::query('SELECT * FROM users');
            return $result->fetchall();
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }

    static function getUserID($email) {
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
    //delete all order products with order ID
    static function deleteOrderProducts($orderID) {
        try{
            $sql = "DELETE FROM orderproducts WHERE orderID = ?";
            return self::$connection->prepare($sql)->execute([$orderID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
    }
    //delete all order products with order ID
    static function deleteOrder($orderID) {
        try{
            $sql = "DELETE FROM orders WHERE orderID = ?";
            return self::$connection->prepare($sql)->execute([$orderID]);
        } catch (Exception $ex) {
            die("Error: " . $ex->getMessage());
        }
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