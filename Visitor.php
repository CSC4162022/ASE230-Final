<?php
require 'Product.php';
require 'auth/AuthDisplayHelper.php';

include_once './dbo.php';
$charset = 'utf8mb4';
$port = 3306;
$host = '127.0.0.1';
$db = 'FooCommerce';
$user = 'root';
$pass = '';

//initialize products from the database

DB::connect($host,$db,$user,$pass,$port,$charset);

Class Visitor {

    //display the products list and sign in form
    static function displayVisitorsPage() {

        $products = new Product();
        $products->setProducts();

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="assets/css/index.css" />
            <title><?= 'Visitors' ?></title>
        </head>
        <body>
            <div class="container text-center">
                <div class="d-flex align-items-center justify-content-between mt-1">
                    <?php

                        //if (isset($_GET['failedSignin'])) {
                        //    AuthDisplayHelper::displayFailedSignIn();
                        //}
                        //display sign in form
                        AuthDisplayHelper::displaySignInForm();
                    ?>
                    <ul class="list-group">
                        <?php
                        //display products list to visitor
                        foreach ($products->products as $product) {
                            ?>
                            <li class="list-group-item"><i class=""></i><?=$product->description . ' $' . $product->price?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        </body>
        </html>
        <?php
    }
}