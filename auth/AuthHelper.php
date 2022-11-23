<?php


Class AuthHelper {

    static private $usersFile = '../data/users.csv';
    static private $administratorsFile = '../data/administrators.csv';
    static private $bannedUsersFile = '../data/banned.csv';

    //validate input, save a new user to the appropriate file and return the user type (admin or user)
    static function signUp($email, $password, $userType)
    {
        if (!isset($userType) || !isset($email) || !isset($password)) return '';
        if (!self::validateSignUpInput($email, $password)) return '';
        //don't inform the visitor the account is banned
        if (self::userIsBanned($email, self::$bannedUsersFile)) return '';
        // check if the username is taken
        if (self::emailExists($email, self::$usersFile) || self::emailExists($email, self::$administratorsFile)) {
            header('Location: ./Visitors.php?usernameTaken=true');
        } else {
            if ($userType=='user') {
                self::saveNewUser(self::$usersFile, $email, self::encryptPassword($password));
                $_SESSION['newEnrolledUser'] = true;
                return $userType;
            }
            else {
                self::saveNewUser(self::$administratorsFile, $email, self::encryptPassword($password));
                $_SESSION['newEnrolledAdministrator'] = true;
                return $userType;
            }
        }
        return '';
    }
    //check if banned, return user type (admin,user) if valid credentials
    static function signIn($email, $password)
    {
        $userType = '';
        if (!isset($email) || !isset($password)) return $userType;
        if (self::userIsBanned($email, self::$bannedUsersFile)) die($email . ' is banned.');
        if (self::isAdministrator($email)) {
            $userType = 'administrator';
            $line = fopen(self::$administratorsFile, 'r');
        }
        else if (self::isUser($email)) {
            $userType = 'user';
            $line = fopen(self::$usersFile, 'r');
        }
        else {
            //user doesn't exist
            return $userType;
        }
        while ( false !== ( $data = fgetcsv($line))) {
            if ($data[0] == $email) {
                $verify = password_verify($password, $data[1]);
                if ($verify) {
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $_SESSION['logged']=true;
                    $_SESSION['userType']=$userType;
                    return $userType;
                }
            }
        }
        //will return to users or admin page
        if (self::isLogged()) return $userType;
        else {
            $_SESSION['logged'] = false;
            return $userType;
        }
    }

    static function saveNewUser($selectedFile, $email, $passwordHash) {
        $file = new SplFileObject($selectedFile, 'a');
        $file->fputcsv(array($email, $passwordHash));
        $file = null;
    }
    //utility function to encrypt password
    static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    //Utility function to validate length of the password and format of email
    static function validateSignUpInput($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
        if (strlen($password) < 8) return false;
        return true;
    }

    //Utility function to check for SQL injection chars: allow @ and .
    static function checkInjectionChars($input) {
        $illegal = "#$%^&*()+=-[]';,/{}|:<>?~";
        //if no chars present
        if (!strpbrk($input, $illegal)) {
            return true;
        }
        return false;
    }
    //check if a banned user is attempting to authenticate
    static function userIsBanned($email, $bannedUsersFile)
    {
        $bannedUsersLine = fopen($bannedUsersFile, 'r');
        while (false !== ($data = fgetcsv($bannedUsersLine))) {
            if ($data[0] == $email) {
                return true;
            }
        }
        return false;
    }

    //utility function to check if user type is administrator
    static function isAdministrator($email) {
        if (self::emailExists($email, self::$administratorsFile)) return true;
        return false;
    }
    //utility function to check if user type is user
    static function isUser($email) {
        if (self::emailExists($email, self::$usersFile)) return true;
        return false;
    }

    //check if it's an existing user
    static function emailExists($email, $usersFile) {
        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine) ) ) {
            if($data[0] == $email) {
                return true;
            }
        }
        return false;
    }
    //utility function to check if active session
    static function isLogged()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged']==true) {
            return true;
        }
        return false;
    }
    //utility function to sign out
    static function signOut()
    {
        $_SESSION['logged']=false;
        session_destroy();
        header('Location: ./index.php');
    }
}