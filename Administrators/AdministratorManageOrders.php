<?php


Class AdministratorManageOrders {

    static function createOrder() {

    }

    static function updateOrder() {

    }

    static function deleteOrders() {

    }

    static function display($dbo) {
        $host = '127.0.0.1';
        $db = 'FooCommerce';
        $user = 'root';
        $pass = '';
        $port = 3306;
        $charset = 'utf8mb4';
        $dbo::connect($host,$db,$user,$pass,$port,$charset);
        $orders=$dbo->getOrders();
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
                            for($i=0; $i<count($orders); $i++) {
                                ?>
                                <a class="list-group-item list-group-item-action" href="<?='./CartItemDetail.php?index='.$i.'&description='.$orders[$i]['description']?>">
                                    <?=$orders[$i]['description'] . ' $' . $orders[$i]['price']?></a>
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