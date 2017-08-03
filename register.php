<?php
/*
 * this may need to be changed to return JSON, pending Sarah's comments..
 */
require('core/init.php');


// use the template..
$template = new Template('views/registeruser.php');
$articles = new Article();
$user = new User();
$validator = new Validation();

// check if form been submitted
if (isset($_POST['register'])) {
    $data = array();
    $data['name'] = $_POST['name'];
    $data['email'] = $_POST['email'];
    $data['username'] = $_POST['username'];
    $data['password'] = $_POST['password'];
    $data['password2'] = $_POST['password2'];
    $data['membership'] = 3;

    // required fields from register form
    $req_fields = ['name', 'email', 'username', 'password', 'password2'];
    // ok lets register a user... this might get ugly..
    if($validator->registrationCheck($req_fields)) {
        if($validator->checkEmail($data['email'])){
            if($validator->checkPasswords($data['password'], $data['password2'])) {
                if ($validator->usernameLength($data['username'])) {
                    if ($user->uploadAvatar()) {
                        $data['avatar'] = $_FILES['avatar']['name'];
                    } else {
                        //if they don't have one then we'll give them one..
                        $data['avatar'] = "default.png";
                    }
                    // lets register a user..
                    if ($user->register($data)) {
                        redirect('index.php', 'Oh Yeah! Your registration was successful!', 'success');
                    } else {
                        redirect('register.php', 'Something went wrong! We like you we really do, could you try it again?', 'error');
                    }
                }else {
                    redirect('register.php', 'usernames can only be 15 characters long!', 'error');
                }
            } else {
                redirect('register.php', 'Your passwords don\'t match!', 'error');
            }
        }else {
            redirect('register.php', 'Please use a valid email address', 'error');
        }
    }else {
        redirect('register.php', 'Please fill in all non optional fields!', 'error');
    }
}





// to display the template..
echo $template;
