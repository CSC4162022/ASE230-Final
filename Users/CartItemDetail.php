<?php

if(!isset($_SESSION)) session_start();

if (isset($_SESSION['products']) && isset($_GET['index'])) {
    $products = $_SESSION['products'];
    $selection = $products[$_GET['index']];
    CartItemDetail::display($selection);
}
else if (isset($_POST['quantity'])) {
    $_SESSION['selectedQuantity']=$_POST['quantity'];
    header('Location: ./UserDisplayHelper.php?selectedQuantity='.$_POST['quantity']);
}


//display the selected product, prompt user for quantity
Class CartItemDetail {

    static function display($selection) {

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="assets/css/index.css" />
            <title><?= 'Product detail'?></title>
        </head>
        <body>
        <div class="container text-center">
            <form method="POST" class="form-outline" action="./CartItemDetail.php">
                <p><?='please select the desired quantity'?></p>
                <label><?=$selection['description']?></label>
                <label for="quantity"><?='quantity'?></label>
                <input type="text" name="<?='quantity'?>" value="1">
                <button class="btn btn-primary" type="submit"><?='Submit'?></button>
                <a href="./UserDisplayHelper.php"><button class="btn btn-primary"><?='Back'?></button></a>
            </form>
        </div>
        </body>
        </html>
<?php
    }
}


?>



