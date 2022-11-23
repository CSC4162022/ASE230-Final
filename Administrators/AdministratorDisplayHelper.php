<?php
if(!isset($_SESSION)) session_start();
require 'Administrator.php';
require 'AdministratorDBUtility.php';

$admin = new Administrator();
$admin->setAdminFromSession($_SESSION);
$administratorDisplayHelper = new AdministratorDisplayHelper();
$administratorDisplayHelper->display();;
print_r($admin);

Class AdministratorDisplayHelper {

    private $utilities;

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
    function display() {
        $host = '127.0.0.1';
        $db = 'FooCommerce';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $charset = 'utf8mb4';
        AdministratorDBUtility::connect($host,$db,$user,$pass,$port,$charset);
        $adminDBUtil = new AdministratorDBUtility();
        $products=$adminDBUtil->getProducts();
        //$products=$utilities->getProducts();
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="assets/css/index.css" />
            <title><?= 'Welcome ' . $_SESSION['email']?></title>
        </head>

        <body>
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-between mt-1">
                <?php
                print_r('test');
                print_r($products);
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

?>
