<?php

class User
{
    //private properties
    private $firstName;
    private $lastName;
    private $email;


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
    //if no session return false, otherwise set user properties
    public function setUserFromSession($SESSION) {
        if(!isset($SESSION)) return false;
        if(isset($SESSION['email']) && isset($SESSION['password'])) {
            $this->email = $SESSION['email'];
            $this->password = $SESSION['password'];
            return true;
        }
        return false;
    }
}