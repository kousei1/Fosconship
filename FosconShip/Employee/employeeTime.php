<?php
require '../connection/usersconfig.php';

$employeeAttendance = new users($connection);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['timeIn'])){

        $display = $employeeAttendance->employeeTimein($_SESSION['empID']);
        if($display){
            echo json_encode(array('success' => 'Time in Successfully'));
        }else{
            echo json_encode(array('failed' => 'Time in Failed'));
        }

    }

    if(isset($_POST['timeOut'])){
        $empID = $_POST['empID'];
        $result = $employeeAttendance->employeeTimeOut($_SESSION['empID'], $empID);
        if($result){
            echo json_encode(array('success' => 'Time out Successfully'));
        }else{
            echo json_encode(array('failed' => 'Time out Failed'));
        }
    }


   



}



?>