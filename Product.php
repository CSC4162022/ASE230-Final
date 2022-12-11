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
}
