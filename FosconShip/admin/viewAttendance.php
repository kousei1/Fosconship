<?php
require_once '../connection/usersconfig.php';

$getAttend = new users($connection);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['Id'])){
        $empID = $_POST['empID'];

   // Get attendance data for the employee
    $resultsAttendance = $getAttend->DisplayAttendance($empID);
    $employeeAttendance = array();

    if (!empty($resultsAttendance)) {
        foreach ($resultsAttendance as $attendanceData) {
            $attendance = array();

            $attendance['firstname'] = $attendanceData['firstname'];
            $attendance['lastname'] = $attendanceData['lastname'];
            $attendance['middlename'] = $attendanceData['middlename'];
            $attendance['date'] = $attendanceData['date'];
            $attendance['in_time'] = $attendanceData['in_time'];
            $attendance['out_time'] = $attendanceData['out_time'];
            $attendance['in_status'] = $attendanceData['in_status'];
            $attendance['out_status'] = $attendanceData['out_status'];

            // Add the attendance data to the array
            $employeeAttendance[] = $attendance;
        }
    }

    // Set the appropriate Content-Type header
    header('Content-Type: application/json');

    // Echo the JSON-encoded attendance data
    echo json_encode($employeeAttendance);

        


    }
}

?>