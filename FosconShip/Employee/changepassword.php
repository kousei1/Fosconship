<?php
require_once '../connection/usersconfig.php';

$change = new users($connection);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['newPassword'])){

        $newPassword = $_POST['newPassword'];
        $renewpassword = $_POST['renewpassword'];

        if(strlen($newPassword) == 0 && strlen($renewpassword) == 0){
            echo json_encode(array('failed' => 'Fill the form'));
            exit;
        }

        if($newPassword != $renewpassword){
            echo json_encode(array('failed' => 'password doesn\'t match'));
            exit;
        } else {
            // Passwords match
           $result =  $change->updatepassword($_SESSION['empID'], $newPassword);
           if(!$result){
            echo json_encode(array('failed' => 'Cannot use old password'));
            exit;
           }else{
            echo json_encode(array('success' => 'Password changed successfully'));
            exit;
           }
            
        }
    }
} else {
    header("Location: Employee-fosconship.php");
    exit; // Ensure script execution stops after redirection
}
?>
