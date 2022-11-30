<?php
require 'User.php';
require_once 'UserDBUtility.php';
if(!isset($_SESSION)) session_start();

$userDH = new UserDisplayHelper();
UserDBUtility::connect($userDH::$host,$userDH::$db,$userDH::$user,$userDH::$pass,$userDH::$port,$userDH::$charset);
$db = new UserDBUtility();
$user = new User();
$user->setUserFromSession($_SESSION);

//user selected quantity, add to order
if (isset($_POST['quantity']) && isset($_POST['description'])
    && isset($_POST['price']) && isset($_POST['category']) && isset($_POST['productID'])) {
    $userDH->updateOrder($_POST['quantity'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['productID']);
    $_POST=null;
    $userDH->displayOrder();
}
//user submits order
if (isset($_POST['submitOrder'])) {
    $userDH->submitOrder($db, $user);
    //reset
    $_POST=null;
    $_SESSION['cartItems']=[];
}
//display products
$products = $db->getProducts();
$_SESSION['products']=$products;
$userDH->displayProducts($products);

//update available quantity
//convenience class to display products
Class UserDisplayHelper {
    static $host = '127.0.0.1';
    static $db = 'FooCommerce';
    static $user = 'root';
    static $pass = '';
    static $port = 3306;
    static $charset = 'utf8mb4';
    static $cartItems=[];

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
    //update the shopping cart
    function updateOrder($quantity, $description, $price, $category, $productID) {
        $product = [
            'quantity' => $quantity,
            'description' => $description,
            'price' => $price,
            'category' => $category,
            'productID' => $productID
        ];
        if (!isset($_SESSION['cartItems'])) $_SESSION['cartItems']=[];
        array_push($_SESSION['cartItems'], $product);
    }
    function submitOrder($db, $user) {
        $orderID = $db->insertOrder('ordered',$user->email);
        foreach($_SESSION['cartItems'] as $product) {
            $db->insertOrderProduct($product, $orderID);
            $db->updateAvailableQuantity($product);
        }
    }
    function displayOrder() {
        $cartItems=$_SESSION['cartItems'];
        ?>
        <div class="col-m-4 container text-center">
        <h5><?='Cart'?></h5>
        <div class="d-flex align-items-center justify-content-center">
            <div class="list-group">
                <form method="post" action="./UserDisplayHelper.php">
                    <ul>
                        <?php
                        for($i=0; $i<count($cartItems); $i++) {
                            ?>
                            <p><?=$cartItems[$i]['description']?><?=$cartItems[$i]['price']?></p>
                            <?php
                        }
                        ?>
                    </ul>
                    <input type="submit" value="<?='Submit Order'?>" name="<?='submitOrder'?>" class="btn btn-primary">
                </form>
            </div>
        </div>
            <?php
    }
    function displayProducts($products) {

        //$productCategories = count(array_unique(array_column($products, 'category')));
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
                    <div class="row">
                        <div class="form-outline mb-4 col-sm">
                            <p><a href="../auth/SignOut.php"><?= 'Sign Out' ?></a></p>
                            <p><a href="../index.php"><?= 'Back' ?></a></p>
                        </div>
                        <div class="d-flex align-items-center justify-content-center col-sm">
                            <div class="list-group">
                                <h5><?='Select Items'?></h5>
                                <ul>
                                    <?php
                                    //always sending last row in products table, Fix it
                                    for($i=0; $i<count($products); $i++) {
                                        ?>
                                        <a class="list-group-item list-group-item-action" href="<?='./CartItemDetail.php?index='.$i.'&description='.$products[$i]['description']?>">
                                            <?=$products[$i]['description'] . ' $' . $products[$i]['price']?></a>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
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
