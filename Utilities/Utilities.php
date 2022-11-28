<?php
require 'dbo.php';
require 'Product.php';

Class Utilities
{

    public function __construct() {
        $this->setConnection();
    }

    public function setConnection() {
        $host = '127.0.0.1';
        $db = 'FooCommerce';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $charset = 'utf8mb4';
        if(DB::connect($host,$db,$user,$pass,$port,$charset)) {
            return true;
        }
        return false;
    }

    public function __get($property) {
        if (property_exists($this, $property)) return $this->$property;
    }
    public function __set($property, $value) {
        if (property_exists($this, $property)) $this->$property = $value;
    }

    public function getProducts() {
        $dbo = new DB();
        $products = array();
        try {
            $result=$dbo->query('SELECT * FROM products');
            while ($productArr=$result->fetch()){
                $product = new Product();
                $product->productID=$productArr['productID'];
                $product->description=$productArr['description'];
                $product->category=$productArr['category'];
                $product->price=$productArr['price'];
                $product->availableQuantity=$productArr['availableQuantity'];
                $product->visible=$productArr['visible'];
                array_push($products, $product);
            }
            return $products;
        } catch (Exception $ex) {
            die("Error: ".$ex->getMessage());
        }
    }
}
