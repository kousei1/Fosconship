<?php
require '../connection/dbconfig.php';
require_once '../connection/usersconfig.php';

 $getData = new users($connection);


 if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(isset($_POST['getdata'])){

        $employee = $_POST['employee'];

        $results = $getData->employeeInformation($employee);
       //print_r($results);
        if(!empty($results)){
            $getInformation = array();
            foreach($results as $employeeData){
                $employeeList = array();

                if(isset($employeeData['empID'])){
                    $employeeList['empID'] = $employeeData['empID'];
                    $employeeList['firstname'] = $employeeData['firstname'];
                    $employeeList['lastname'] = $employeeData['lastname'];
                    $employeeList['middlename'] = $employeeData['middlename'];
                    $employeeList['email'] = $employeeData['email'];
                    $employeeList['birthday'] = $employeeData['birthday'];
                    $employeeList['hired_date'] = $employeeData['hired_date'];
                    $employeeList['address'] = $employeeData['address'];
                    $employeeList['contact'] = $employeeData['contact'];
                    $employeeList['image'] = base64_encode($employeeData['image']);
                    $employeeList['Gender'] = $employeeData['Gender'];
                    $employeeList['name'] = $employeeData['name'];
                    $employeeList['salaryEmp'] = $employeeData['salaryEmp'];

                    $getInformation[] = $employeeList;

                }

            }
            header('Content-Type: application/json; charset=utf-8');
            // Echo the JSON-encoded data outside the loop
            echo json_encode($getInformation);
            

        }

       
        
    }


 }

?>