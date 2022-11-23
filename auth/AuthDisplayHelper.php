<?php

require "AuthHelper.php";

//sign in returns administrator or user then directs to appropriate area
if (isset($_POST['email']) && isset($_POST['password'])) {

    $userType = AuthHelper::signIn($_POST['email'], $_POST['password']);
    if($userType=='user') header('Location: ../Users/UserDisplayHelper.php');
    else if ($userType=='administrator') header('Location: ../Administrators/AdministratorDisplayHelper.php');
    else header('Location: ../index.php?failedSignIn=true');
}

class AuthDisplayHelper
{

    //sign in and route to correct area
    static function displaySignInForm() {

        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="assets/css/index.css" />
        </head>

        <body>
        <div class="container text-center">
            <div class="media-body order-2 order-lg-1">
                <div class="d-flex align-items-center justify-content-between mt-1">

                </div>
            </div>
            <form method="POST" action="auth/AuthDisplayHelper.php">
                <h4 class="col-sm-4"><?='Sign In'?></h4>
                <div class="form-group col-sm-4">
                    <label for="exampleInputEmail1">Email address</label>
                    <input required maxlength="<?='50'?>" type="email" name="<?='email'?>" class="form-control" aria-describedby="emailHelp" placeholder="<?='Enter email'?>">
                    <small id="emailHelp" class="<?='form-text text-muted'?>"><?='Enter your user name.'?></small>
                </div>
                <div class="form-group col-sm-4">
                    <label for="email"><?='password'?></label>
                    <input type="password" name="<?='password'?>" class="form-control" placeholder="<?='Enter password'?>">
                    <button type="submit" class="btn btn-primary"><?='Sign in'?></button>
                    <a href="<?='auth/SignUp.php'?>" class="btn btn-primary"><?='Sign Up'?></a>
                </div>
            </form>
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
