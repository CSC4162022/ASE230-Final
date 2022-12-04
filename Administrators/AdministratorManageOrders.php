<?php
if(!isset($_SESSION)) session_start();
require_once './AdministratorDisplayHelper.php';

$db = new AdministratorDBUtility();
$db::connect();
//admin selected manage orders
if(isset($_GET['manageOrders'])) { AdministratorManageOrders::display($db); $_GET=null; }
//admin selected view order, status change not submitted
if(isset($_POST['orderID']) &&
    !isset($_POST['statusOption']) &&
    !isset($_POST['deleteOrder'])) { AdministratorManageOrders::display($db); $_POST=null; }
//admin submitted order status change
if(isset($_POST['orderID']) &&
    isset($_POST['statusOption'])) { AdministratorManageOrders::updateOrderStatus(($_POST['orderID']), $_POST['statusOption'], $db); }
//admin submitted order deletion
if(isset($_POST['orderID'])
    && isset($_POST['deleteOrder'])
    && !isset($_POST['statusOption'])) { AdministratorManageOrders::deleteOrder($_POST['orderID'], $db); }

Class AdministratorManageOrders {

    static function createOrder() {

    }

    static function updateOrderStatus($orderID, $status, $db) {
        $db->updateOrderStatus($orderID, $status);
        $_POST['statusOption']=null;
        header('Location: ./AdministratorManageOrders.php?manageOrders=true');
    }

    static function deleteOrder($orderID, $db) {
        print_r('TEST DEL');
        //delete all products from order products first
        if($db->deleteOrderProducts($orderID)) {
            if($db->deleteOrder($orderID)) header('Location: ./AdministratorManageOrders.php?manageOrders=true');
        }
    }

    static function display($db) {
        //get user list for order selection
        //$users=$dbo->getUsers();
        $orders=$db->getOrders();

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
                            <h5><?='User Orders'?></h5>
                            <h4><a cla="btn btn-primary" href="<?='../Administrators/AdministratorDisplayHelper.php?newSession=true'?>"><?='Back'?></a></h4>
                            <ul>
                                <?php
                                for($i=0; $i<count($orders); $i++) {
                                    ?>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm">
                                                <p class="list-group-item list-group-item-action">
                                                    <?='OrderID: ' . $orders[$i]['orderID'] . ' Status: ' . $orders[$i]['orderStatus'] . ' Username: ' . $orders[$i]['email']?></p>
                                            </div>
                                            <form method="POST" class="form-outline" action="AdministratorManageOrders.php">
                                                <div class="form-group">
                                                    <div class="form-outline mb-4">
                                                        <input type="hidden" name="<?='orderID'?>" value="<?=$orders[$i]['orderID']?>"/>
                                                        <input type="hidden" name="<?='deleteOrder'?>" value="<?='deleteOrder'?>"/>
                                                        <input type="submit" value="<?='Delete Order'?>" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </form>
                                            <form method="POST" class="form-outline" action="AdministratorManageOrders.php">
                                                <div class="form-group">
                                                    <div class="form-outline mb-4">
                                                        <input type="hidden" name="<?='orderID'?>" value="<?=$orders[$i]['orderID']?>"/>
                                                        <input type="hidden" name="<?='viewOrder'?>" value="<?='viewOrder'?>"/>
                                                        <input type="submit" value="<?='View Order'?>" class="btn btn-primary">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                }
                                //if admin selected the order display it's products
                                if(isset($_POST['orderID'])) {
                                    $statusOption=[];
                                    $orderID=$_POST['orderID'];
                                ?>
                                <div class="container">
                                    <?php
                                        //display order items using orderID
                                         $orderItems=$db->getOrderItems($_POST['orderID']);
                                        if(isset($orderItems)) {
                                            $userName=$orderItems[0]['firstName'].' '.$orderItems[0]['lastName'];
                                            $orderStatus=$orderItems[0]['orderStatus'];
                                            ?>
                                            <p><strong><?='Order for user: '.$userName?></strong></p>
                                            <p><?='orderID: '.$_POST['orderID']?></p>
                                            <?php
                                            foreach($orderItems as $item) {
                                            ?>
                                            <p><?=' Product: '.$item['description'].' status: '.$item['orderStatus']?></p>
                                            <?php
                                            }
                                            if($orderStatus=='ordered') $statusOption = ['canceled', 'in progress'];
                                            if($orderStatus=='in progress') $statusOption = ['shipped'];
                                            if($orderStatus=='shipped') $statusOption = ['delivered'];
                                            //permit status update if not canceled or delivered.

                                            ?>
                                                <form method="POST" class="form-outline" action="AdministratorManageOrders.php">
                                                    <select name="<?='statusOption'?>">
                                                        <?php foreach( $statusOption as $option ): ?>
                                                            <option value="<?=$option?>"><?=$option?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php
                                                    //if(!$orderStatus=='canceled') {
                                                    ?>
                                                        <input type="hidden" name="<?='orderID'?>" value="<?=$_POST['orderID']?>" />
                                                        <input type="submit" value="<?='submit status change'?>" class="btn btn-primary">
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