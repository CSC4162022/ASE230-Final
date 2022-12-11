<?php
if(!isset($_SESSION)) session_start();

if (isset($_SESSION['products']) && isset($_GET['index']) && isset($_GET['description'])) {
    $products = $_SESSION['products'];
    $selection = $products[$_GET['index']];
    ProductDetail::display($selection);
}

//display the selected product, prompt user for quantity
Class ProductDetail {

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
        <!-- select the quantity and return to the products list with an updated cart -->
        <div class="container text-center">
            <form method="POST" class="form-outline" action="UserDisplayHelper.php">
                <p><?='please select the desired quantity'?></p>
                <label><?=$selection['description']?></label>
                <label for="quantity"><?='quantity'?></label>
                <input type="text" name="<?='quantity'?>" value="1">
                <input type="hidden" name="<?='productID'?>" value="<?=$selection['productID']?>" />
                <input type="hidden" name="<?='description'?>" value="<?=$selection['description']?>" />
                <input type="hidden" name="<?='price'?>" value="<?=$selection['price']?>" />
                <input type="hidden" name="<?='category'?>" value="<?=$selection['category']?>" />
                <input type="submit" value="<?='Submit'?>" name = "<?= 'submitQuantity' ?>" class="btn btn-primary">
                <a href="<?='./UserDisplayHelper.php?'?>"><button class="btn btn-primary"><?='Back'?></button></a>
            </form>
        </div>
        </body>
        </html>
<?php
    }
}
?>



