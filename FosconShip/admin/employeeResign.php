<?php 
require_once '../connection/usersconfig.php';

$employeeResign = new users($connection);


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['resign'])){

        $IDReign = $_POST['IDReign'];

        $result = $employeeResign->employeeResigned($IDReign);

        if($result){
            echo json_encode(array('success' => 'Successfully this employee Resigned'));
        }else{
            echo json_encode(array('failed' => 'Error resigned'));
        }
    }

}


?>