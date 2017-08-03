<?php

// super simple validation class, JQuery should also be used on the front end..
// this is just for those worse case scenarios..
class Validation {

    // check all non optional registration fields
    public function registrationCheck($datas) { // takes in array.. in case your interested
        foreach($datas as $data) {
            // if post contains nothing then stop
            if($_POST[''.$data.''] == '') {
                return false;
            }
        }

        return true;
    }

    // check the email is correct format
    public function checkEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }else {
            return false;
        }
    }

    // check the passwords match
    public function checkPasswords($password1, $password2) {
        if($password1 != $password2) {
            return false;
        }else {
            return true;
        }
    }

//    // check the username length must be <= 15 chars
    public function usernameLength($username)
    {
        if (strlen($username) > 15) {
            return false;
        } else {
            return true;
        }
    }
}