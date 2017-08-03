<?php
// start the session engine
session_start();

// get the config file for the database
require_once(( realpath( dirname( __FILE__ ) ).'../../config/config.php'));

// helper files grab them as the app starts..
require_once(( realpath( dirname( __FILE__ ) ).'../../helpers/system_helper.php'));
require_once(( realpath( dirname( __FILE__ ) ).'../../helpers/formats_helper.php'));
require_once(( realpath( dirname( __FILE__ ) ).'../../helpers/db_helper.php'));

// pretty simple autoloader, this just autoloads classes, which
// saves us from having to require a class everytime we need it..
// Just remember that a class must have the same name as the file or the 
// loader wont work..
function __autoload($class_name) {
    require_once(( realpath( dirname( __FILE__ ) ).'../../library/' . $class_name . '.php'));
}