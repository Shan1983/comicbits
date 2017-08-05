<?php

class User {

    // database
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    // handle the avatar upload.. this is gonna be messy..
        // ok here goes nothing...
        public function uploadAvatar()
        {

            if (!empty($_FILES['avatar']['name'])) {


                // array containg the extensions of popular image files
                $allowedExtensions = array("gif", "jpeg", "jpg", "png");

                //echo $_FILES['avatar']['name'];


                // make it so i can get the .extension
                $tmp = explode(".", $_FILES['avatar']['name']);


                // get the end part of the file
                $newfilename = hash('sha256', reset($tmp) . time());

                $extension = end($tmp);

                $_FILES['avatar']['name'] = $newfilename . "." . $extension;
                //echo $_FILES['avatar']['name'];


                // ok here we go.. maybe a switch might be nice?.. meh..
                if ((($_FILES['avatar']['type'] == "image/gif")
                        || ($_FILES['avatar']['type'] == "image/jpeg")
                        || ($_FILES['avatar']['type'] == "image/jpg")
                        || ($_FILES['avatar']['type'] == "image/png"))
                    && ($_FILES['avatar']['size'] < 2000000)
                    && in_array($extension, $allowedExtensions)
                ) {

                    // get the error
                    if ($_FILES['avatar']['error'] > 0) {
                        redirect('register.php', $_FILES['avatar']['error'], 'error');
                    } else {
                        // and finally move the file into the folder
                        move_uploaded_file($_FILES['avatar']['tmp_name'], "images/avatars/" . $_FILES['avatar']['name']);
                        //redirect('index.php', 'Amazing! your all signed up', 'success');
                        $_SESSION['avatar'] = $_FILES['avatar']['name'];
                        return true;
                    }

                } else {
                    // stop and warn the user that the file type is not suppoerted
                    redirect('register.php', 'Invalid File Type! We only accept "gif", "jpeg", "jpg", or "png"', 'error');
                }
            } else {
                //move_uploaded_file($_FILES['avatar']['tmp_name'], SERVER_URI . "images/avatars/" . $_FILES['avatar']['name']);
                return false;
            }

        }

        public function register($data) {
            
            // check email is already in use
            $this->db->query("SELECT email FROM users WHERE email = :email");
            $this->db->bind(':email', $data['email']);
            $result = $this->db->single();
            
            if ($result) {
                redirect('register.php', 'That email is already in use try <a href="login.php">logging in</a>', 'error');
            }else {
            
                // insert the user into the database
                $this->db->query('INSERT INTO users 
                                  (name, email, avatar, username, password, membership)
                                  VALUES (:name, :email, :avatar, :username, :password, :membership)');
                // bind the prepared statements
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':avatar', $data['avatar']);
                $this->db->bind(':username', $data['username']);
                $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
                $this->db->bind(':membership', $data['membership']);
    
                // make it work
                if($this->db->execute()) {
                    return true;
                }else {
                    return false;
                }
            }

            // they should now be registered..
        }

    // user login
    public function login($email, $password) {
        // get the hashed password from database
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $tmp = $this->db->single();
        $hash = $tmp->password;

        // now lets see if it verifies..
        if(password_verify($password, $hash)) {
            $this->db->query('SELECT * FROM users
                                WHERE email = :email
                                AND password = :password');

            // bind the vars to the query
            $this->db->bind(':email', $email);
            $this->db->bind(':password', $hash);

            $result = $this->db->single();

            //die(print_r($result));
            // get em ready for the login
            $this->setUserToLoggedIn($result);
            return true;
        }else {
            return false;
        }
    }

    // store the user in a session to keep em logged in
    public function setUserToLoggedIn($result) {
        // this is dangerous and lazy but will be fine for the assignment.
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $result->id;
        $_SESSION['username'] = $result->username;
        $_SESSION['membership'] = $result->membership;
        $_SESSION['avatar'] = $result->avatar;
        $_SESSION['created_at'] = $result->created_at;
    }

    // logs a user out
    public function logout() {
        unset($_SESSION['is_logged_in']);
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['membership']);
        unset($_SESSION['avatar']);
        unset($_SESSION['created_at']);
        return true;
    }
    
    // make a new admin
    public function makeAdmin($id) {
        $this->db->query("UPDATE users SET membership = '1' WHERE id = :id");
        $this->db->bind(':id', $id);
        if ($this->db->execute()){
            return true;
        }else {
            return false;
        } 
    }
    
    // get all the users data for the profile
    public function fetchUserData($uid) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $uid);
        
        $result = $this->db->single();
        
        return $result;
    }


}