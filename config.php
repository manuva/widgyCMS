<?php

/*********************************
 * DATABASE Configuration File
 * 
 * 
 * 
 * 
 * 
 * 
 */

    ini_set("display_errors", true); //causes error messaged to be displayed in the browser. 
                                     //should be set to false once live.
    date_default_timezone_set("America/Detroit"); //tell php our server's timezone.
    define("DB_DSN", "mysql:host=localhost;dbname=cmsdb"); //tell php where to find our db.
    define("DB_USERNAME", "fufanux"); //mysql username
    define("DB_PASSWORD", "boober"); //mysql password
    define("CLASS_PATH", "classes"); //path to class files
    define("TEMPLATE_PATH", "templates"); //tell script where to look for html
    define("HOMEPAGE_NUM_ARTICLES", 5); //controls the max number of article headlines to display
    define("ADMIN_USERNAME", "admin"); //login details for cms admin user
    define("ADMIN_PASSWORD", "mypass");//password
    require(CLASS_PATH."/Article.php"); //include article class
    
    
    //function to handle any PHP exceptions, displays a generic error message
    //and logs the exception message to error log.
    //Note: This needs to be updated to try ... catch blocks..
    function handleException($exception) {
        echo "sorry, a problem occurred. Please try again later.";
        error_log($exception->getMessage() );
        
    }
    
    
    set_exception_handler('handleException');
   
    
    

?>
