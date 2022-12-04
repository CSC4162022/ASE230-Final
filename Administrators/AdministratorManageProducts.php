<?php
if(!isset($_SESSION)) session_start();
require_once './AdministratorManageOrders.php';
require_once '../Product.php';

$db = new AdministratorDBUtility();
$db::connect();

//admin selected manage products
if(isset($_GET['manageProducts'])) { AdministratorManageProducts::display($db); $_GET=null; }
//admin selected view product details
if(isset($_POST['productID']) &&
    isset($_POST['viewProduct']) &&
    !isset($_POST['deleteProduct'])) { AdministratorManageProducts::display($db); $_POST=null; }
//admin submitted product modification
if(isset($_POST['productID']) &&
    isset($_POST['description']) &&
    isset($_POST['price']) &&
    isset($_POST['category']) && isset($_POST['visible'])) {
    $product = new Product();
    $product->productID=$_POST['productID'];
    $product->description=$_POST['description'];
    $product->price=$_POST['price'];
    $product->category=$_POST['category'];
    $product->visible=$_POST['visible'];
    $product->avialbeQuantity=$_POST['availableQuantity'];
    AdministratorManageProducts::updateProduct($product, $db);
}
//admin submitted product deletion
if(isset($_POST['productID']) && isset($_POST['deleteProduct'])) {
    AdministratorManageProducts::deleteProduct($_POST['productID'], $db);
}



Class AdministratorManageProducts {

    static function createOrder($product, $db) {
        $db->createProduct($product);
        $_POST=null;
        header('Location: ./AdministratorManageProducts.php?manageProducts=true');
    }

    static function updateProduct($product, $db) {
        $db->updateProduct($product);
        $_POST=null;
        header('Location: ./AdministratorManageProducts.php?manageProducts=true');
    }

    static function deleteProduct($productID, $db) {
        //check if there are orders with product
        $orderIDs=[];
        $orderIDs=$db->getOrdersWithProductID($productID);
        if (count($orderIDs)>0) {
            //delete the product from all orders
            $db->deleteOrderProductsByProductID($productID);
            //cancel the order if it hasn't shipped or arrived
            foreach($orderIDs as $orderID) {
                $order=$db->getOrder($orderID);
                if (isset($order)) {
                    $status=$order['orderStatus'];
                    if(!$status=='shipped' && !$status=='delivered') $db->updateOrderStatus($orderID['orderID'], 'canceled');
                }
            }
            //delete from products
            if ($db->deleteProduct($productID)) header('Location: ./AdministratorManageProducts.php?manageProducts=true');
            else header('Location: ./AdministratorManageProducts.php?failedDeleteProduct=true');
        }
        //else delete the product
        else {
            if ($db->deleteProduct($productID)) header('Location: ./AdministratorManageProducts.php?manageProducts=true');
            else header('Location: ./AdministratorManageProducts.php?failedDeleteProduct=true');
        }
    }

    static function display($db) {
        //get user list for order selection
        //$users=$dbo->getUsers();
        $products=$db->getProducts();

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
                <div class="d-flex align-items-center justify-content-center col-sm">
                    <div class="list-group">
                        <h5><?='FooCommerce Products'?></h5>
                        <h4><a cla="btn btn-primary" href="<?='../Administrators/AdministratorDisplayHelper.php?newSession=true'?>"><?='Back'?></a></h4>
                        <ul>
                            <?php
                            for($i=0; $i<count($products); $i++) {
                                ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm">
                                            <p class="list-group-item list-group-item-action">
                                                <?='Product: ' . $products[$i]['description'] . ' Price: ' . $products[$i]['price']?></p>
                                        </div>
                                        <form method="POST" class="form-outline" action="AdministratorManageProducts.php">
                                            <div class="form-group">
                                                <div class="form-outline mb-4">
                                                    <input type="hidden" name="<?='productID'?>" value="<?=$products[$i]['productID']?>"/>
                                                    <input type="hidden" name="<?='deleteProduct'?>" value="<?='deleteProduct'?>"/>
                                                    <input type="submit" value="<?='Delete Product'?>" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>
                                        <form method="POST" class="form-outline" action="AdministratorManageProducts.php">
                                            <div class="form-group">
                                                <div class="form-outline mb-4">
                                                    <input type="hidden" name="<?='productID'?>" value="<?=$products[$i]['productID']?>"/>
                                                    <input type="hidden" name="<?='viewProduct'?>" value="<?='viewProduct'?>"/>
                                                    <input type="submit" value="<?='View Product'?>" class="btn btn-primary">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                            }
                            //if admin selected the product, display details
                            if(isset($_POST['productID'])) {
                                ?>
                                <div class="container">
                                    <?php
                                    //display order items using orderID
                                    $product=$db->getProduct($_POST['productID']);
                                    if(isset($product)) {
                                        ?>
                                        <p><strong><?='Product: '.$product['description'];?></strong></p>
                                        <p><?='productID: '.$_POST['productID']?></p>
                                        <?php

                                        ?>
                                        <form method="POST" class="form-outline" action="AdministratorManageProducts.php">
                                            <label for="<?='description'?>"><?='description'?></label>
                                            <input type="text" name="<?='description'?>" value="<?=$product['description']?>"/>
                                            <label for="<?='price'?>"><?='price'?></label>
                                            <input type="text" name="<?='price'?>" value="<?=$product['price']?>"/>
                                            <label for="<?='category'?>"><?='category'?></label>
                                            <input type="text" name="<?='category'?>" value="<?=$product['category']?>"/>
                                            <label for="<?='visible'?>"><?='visible'?></label>
                                            <input type="text" name="<?='visible'?>" value="<?=$product['visible']?>"/>
                                            <label for="<?='availableQuantity'?>"><?='availableQuantity'?></label>
                                            <input type="text" name="<?='availableQuantity'?>" value="<?=$product['availableQuantity']?>"/>
                                            <input type="hidden" name="<?='productID'?>" value="<?=$_POST['productID']?>" />
                                            <input type="submit" value="<?='submit modification'?>" class="btn btn-primary">
                                            <?php
                                            //}
                                            ?>
                                        </form>
                                        <?php

                                    }

                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>

        <?php
    }

}