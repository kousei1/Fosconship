<?php
require_once './connection/usersconfig.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $userLogin = new users($connection);
    
    if(isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

       $result = $userLogin->loginuser($username, $password);

       if($result == 'incorrectpassword'){
        $_SESSION['validationlogin'] = '<div class="alert alert-danger" role="alert">
        Incorrect username or password
        </div>';
        header("Location: fosconship-login.php");
        exit;
       }elseif($result == 'Nouserfound'){
        $_SESSION['validationlogin'] = '<div class="alert alert-danger" role="alert">
        this username not found
        </div>';
        header("Location: fosconship-login.php");
        exit;
       }else{
        echo $result;
       }
    }
    


}


?>