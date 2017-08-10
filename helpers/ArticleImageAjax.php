<?php
	
	require_once('../config/config.php');
    require('../core/init.php');

	if ( 0 < $_FILES['file']['error'] ) {
		echo 'ERROR: ' . $_FILES['file']['error'];
	}
	else {

		// array containg the extensions of popular image files
        $allowedExtensions = array("gif", "jpeg", "jpg", "png");

        // make it so i can get the .extension
        $tmp = explode(".", $_FILES['file']['name']);
        
        $extension = end($tmp);

        if ((($_FILES['file']['type'] == "image/gif")
                || ($_FILES['file']['type'] == "image/jpeg")
                || ($_FILES['file']['type'] == "image/jpg")
                || ($_FILES['file']['type'] == "image/png"))
            && ($_FILES['file']['size'] < 2000000)
            && in_array($extension, $allowedExtensions)
        ) {
            
            // get the end part of the file
            $newfilename = hash('sha256', reset($tmp) . time()) . "." . $extension;

            move_uploaded_file($_FILES['file']['tmp_name'], dirname( __FILE__ ) . '../../images/article_images/' . $newfilename);
        
            echo $newfilename;
        }
        else {
            echo "ERROR:There is an issue with the file you uploaded";
        }
		
	}

	
	
	
?>			