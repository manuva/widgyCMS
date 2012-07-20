<?php

require("config.php");
session_start(); //starts a session for the user
$action = isset($_GET['action']) ? $_GET['action'] : ""; //store $_GET['action'] param in var $action
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : ""; //same for session and username


//check the user is logged in
if ( $action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

//decide which action to perform

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


//login func to be called when the user needs to log in

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



//logout function to be called when the user wants to log out -- removes the username session key and redirects..

function logout() {
    unset($_SESSION['username']);
    header("Location: admin.php");
}






//function to be called that lets the user create a new article
function newArticle() {
    
    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";
    
    if ( isset($_POST['saveChanges'] ) ) {
        
        //user has posted the edit article form: save the new article
        $article = new Article; //new article object
        $article->storeFormValues($_POST); //store form data in formvalues
        $article->insert(); //insert article into database
        header("Location: admin.php?status=changesSaved"); //redirects
        
    } elseif (isset($_POST['cancel'] ) ) {
        
        //the user has cancelled edits.. return to the article list
        header("Location:admin.php");
        
    } else {
        //user has not poasted new article form yet, create a new empty article object
        $results['article'] = new Article; 
        //use editArticle.php to display the article edit
        require (TEMPLATE_PATH . "/admin/editArticle.php");
        
    }
}

//func to let user edit an existing article
function editArticle() {
    
    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";
    
    if ( isset($_POST['saveChanges'] ) ) {
        
        //User has posted the article edit form: save the article changes
            //when user saves changes, retreive existing article and store
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
        
        //User has not posted the article form yet: display the form.
        $results['article'] = Article::getByID( (int)$_GET['articleId'] );
        require( TEMPLAGE_PATH . "/admin/editArticle.php");
        
    }
    
}




function deleteArticle() {
    
    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header("Location: amdin.php?error=articleNotFound");
        return;
    }
    
    $article->delete();
    header("Location: admin.php?status=articleDeleted");    
}


//displays list of all articles for editing.  
function listArticles() {
    $results = array();
    $data = Article::getList(); //use getList method to retrieve
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "All Articles";
    
    if ( isset($_GET['error'] ) ) {
        if ($_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";   
    }
    
    if ( isset($_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved") $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "articleDeleted") $results['statusMessage'] = "Article deleted.";
    }
    
    require( TEMPLATE_PATH . "/admin/listArticles.php");
}

?>
