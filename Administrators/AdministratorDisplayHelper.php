<?php
if(!isset($_SESSION)) session_start();
require 'Administrator.php';
require 'AdministratorDBUtility.php';
require_once 'AdministratorManageOrders.php';
require_once 'AdministratorManageProducts.php';

$db = new AdministratorDBUtility();
$admin = new Administrator();
$admin->setAdminFromSession($_SESSION);
$adminDH = new AdministratorDisplayHelper();
//if new sign in display
if (isset($_GET['newSession'])) $adminDH->display();

if (isset($_POST['manageOrders'])) $adminDH->displayOrderManagement($db);
if (isset($_POST['manageUsers'])) $adminDH->displayProductManagement($db);

Class AdministratorDisplayHelper {

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
    function displayOrderManagement($db) {
        AdministratorManageOrders::display($db);
    }
    function displayProductManagement($db) {
        AdministratorManageProduct::display($db);
    }
    function display() {
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
        <div class="container">
            <h5><?='Administrator Management Panel'?></h5>
            <div class="row">
                <div clas="col-sm">
                    <form method="POST" class="form-outline" action="AdministratorDisplayHelper.php">
                        <div class="form-group">
                            <div class="form-outline mb-4">
                                <input type="hidden" name="<?='manageOrders'?>" value="<?='manageOrders'?>"/>
                                <input type="submit" value="<?='Manage Orders'?>" name = "<?= 'Manage Orders' ?>" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div clas="col-sm">
                    <form method="POST" class="form-outline" action="AdministratorDisplayHelper.php">
                        <div class="form-group">
                            <div class="form-outline mb-4">
                                <input type="hidden" name="<?='manageProducts'?>" value="<?='manageProducts'?>"/>
                                <input type="submit" value="<?='Manage Products'?>" name = "<?= 'Manage Products' ?>" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="form-outline mb-4 col-sm">
                    <p><a href="../auth/SignOut.php"><?= 'Sign Out' ?></a></p>
                    <p><a href="./AdministratorDisplayHelper.php"><?= 'Back' ?></a></p>
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