<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    default:
        listArticles();
        
        
}


function login() {
 
  $results = array();
  $results['pageTitle'] = "Admin Login | Widget News";
 
  if ( isset( $_POST['login'] ) ) {
 
    // User has posted the login form: attempt to log the user in
 
    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {
 
      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );
 
    } else {
 
      // Login failed: display an error message to the user
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }
 
  } else {
 
    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }
 
}





function logout() {
    unset($_SESSION['username']);
    header("Location: admin.php");
}







function newArticle() {
    
    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";
    
    if ( isset($_POST['saveChanges'] ) ) {
        
        //user has posted the edit article form: save the new article
        $article = new Article;
        $article->storeFormValues($_POST);
        $article->insert();
        header("Location: admin.php?status=changesSaved");
        
    } elseif (isset($_POST['cancel'] ) ) {
        
        //the user has cancelled edits.. return to the article list
        header("Location:admin.php");
        
    } else {
        //user has not posted an article edit form yet: display the form
        $results['article'] = new Article;
        require (TEMPLATE_PATH . "/admin/editArticle.php");
        
    }
}


function editArticle() {
    
    $results = array();
    $results['pageTitle'] = "Edit Article";
    $resuts['formAction'] = "editArticle";
    
    if ( isset($_POST['saveChanges'] ) ) {
        
        //User has posted the article edit form: save the article changes
        if ( !$article = Article::getByID( (int)$_POST['articleId'] ) ) {
            header("Location: admin.php?error=articleNotFound");
            return;
        }
        
        $article->storeFormValues($_POST);
        $article->update();
        header("Location: admin.php?status=changesSaved");
    } elseif (isset ($_POST['cancel'] ) ) {
        
        //user has cancelled their edits: return to article list
        header ("Location:admin.php");
        
    } else {
        
    }
    
}




function deleteArticle() {
    
}


function listArticles() {
    
}

?>
