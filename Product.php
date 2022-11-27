<?php


class Product
{
    //private properties
    private $productID;
    private $description;
    private $category;
    private $price;
    private $availableQuantity;
    private $visible;
    private $orderQuantity;
    private $cart;
    private $products=[];

    //magic get/setters
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    //set products array from database
    /*
    public function setProducts() {
        $dbo = new DB();
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
                array_push($this->products, $product);
            }
            return $this->products;
        } catch (Exception $ex) {
            die("Error: ".$ex->getMessage());
        }
    }
    */

    //return array of products belonging to specified category
    public function getProductsByCategory($cat) {
        $_productsOfCat = array();
        foreach ($this->products as $row)
        {
            //print_r($key);
            //print_r($row);
            //$[$key] = $row[$cat];
        }
    }
}
