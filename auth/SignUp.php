<?php

require 'AuthHelper.php';
//user sign up form submission
if (isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['firstName']) &&
    isset($_POST['lastName']) &&
    (isset($_POST['user']) || isset($_POST['administrator']))) {

    $userType = '';
    //get user type for sign up (admin or user)
    if (isset($_POST['user'])) $userType = $_POST['user'];
    if (isset($_POST['administrator'])) $userType = $_POST['administrator'];
    //check for injection chars
    if (!AuthHelper::checkInjectionChars($_POST['email']) ||
        !AuthHelper::checkInjectionChars($_POST['password'])) {
        header('Location: ./SignUp.php?failedSignUp=true');
    }
    //sign up returns administrator or user then directs to appropriate area
    $userType = AuthHelper::signUp($_POST['email'], $_POST['password'], $userType);
    if($userType=='user') header('Location: ../Users/UserDisplayHelper.php?newUser=true&first='.$_POST['firstName'].'&last='.$_POST['lastName'].'&email='.$_POST['email']);
    else if ($userType=='administrator') header('Location: ../Administrators/AdministratorDisplayHelper.php?newAdministrator=true&first='.$_POST['firstName'].'&last='.$_POST['lastName'].'&email='.$_POST['email']);
    else header('Location: ./SignUp.php?failedSignUp=true');
}
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
            <form method="POST" action="SignUp.php">
                <h4 class="col-sm-4"><?='Sign Up'?></h4>
                <?php
                if(isset($_GET['usernameTaken'])) {
                    ?>
                    <p><?='username taken'?></p>
                    <?php
                }
                if(isset($_GET['failedSignUp'])) {
                    ?>
                    <p><strong><?='Sign up attempt failed'?></strong></p>
                    <?php
                }
                ?>
                <div class="form-group col-sm-3">
                    <label for="email"><?='Email Address'?></label>
                    <input required maxlength="<?='50'?>" type="email" name="<?='email'?>" class="form-control" placeholder="<?='Enter email'?>">
                    <small class="<?='form-text text-muted'?>"><?='This will be your user name.'?></small>
                    <label for="firstName"><?='first name'?></label>
                    <input required maxlength="<?='100'?>" type="text" name="<?='firstName'?>" class="form-control" placeholder="<?='Enter first'?>">
                    <label for="lastName"><?='last name'?></label>
                    <input required maxlength="<?='100'?>" type="text" name="<?='lastName'?>" class="form-control" placeholder="<?='Enter last'?>">
                </div>
                <div class="form-group col-sm-3">
                    <label for="password"><?='Password'?></label>
                    <input type="password" name="<?='password'?>" class="form-control" placeholder="<?='Minimum 8 characters'?>">
                    <small class="<?='form-text text-muted'?>"><?='This will be your user name.'?></small>
                </div>
                <div class="form-check col-sm-2">
                    <input class="form-check-input" type="radio" name="<?='user'?>" value="<?='user'?>" id="<?='userRadioInput'?>">
                    <label class="form-check-label" for="<?='userRadioInput'?>">
                        <?='user'?>
                    </label>
                <div class="form group col-sm-3">
                    <input class="form-check-input" type="radio" name="<?='administrator'?>" value="<?='administrator'?>" id="<?='administratorRadioInput'?>">
                    <label class="form-check-label" for="<?='administratorRadioInput'?>">
                        <?='administrator'?>
                    </label>
                </div>
                <div class="form group">
                    <button type="submit" class="btn btn-primary"><?='Sign Up'?></button>
                    <a href="<?='../Visitor.php'?>" class="btn btn-primary"><?='Back'?></a>
                </div>
            </form>
        </div>
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        </body>
        </html>
<?php
