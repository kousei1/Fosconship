<?php
require 'dbconfig.php';
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class users{

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function loginuser($username, $password){

        $logincmd = $this->connection->prepare("SELECT * FROM user_account WHERE username = ?");
        $logincmd->bind_param('s', $username);
        $logincmd->execute();

        $loginresult = $logincmd->get_result();
        if($loginresult->num_rows > 0){

            $getdataresult = $loginresult->fetch_assoc();
            $hashedPasswordFromDB = $getdataresult['password'];
            if(password_verify($password, $hashedPasswordFromDB)){

                $role = $getdataresult['role'];
                
                $rolecmd = $this->connection->prepare("SELECT name FROM user_role WHERE roleID = ?");
                $rolecmd->bind_param('i', $role);
                $rolecmd->execute();
                $rolecmd->store_result();
                if ($rolecmd->num_rows > 0) {
                    $rolecmd->bind_result($name);
                    $rolecmd->fetch();
                    switch ($name) {
                        case "Admin":
                            $this->userinfo($username);
                            header("Location: ./admin/");
                            exit(); // Make sure to exit after redirecting
                            break;
                        // Add other cases as needed
                        case "Employee":
                            $this->userinfo($username);
                            header("Location: ./Employee/");
                            
                            exit();
                        break;
                        default:
                        header("Location ../fosconship-login.php");
                        exit;
                            // Handle unexpected roles
                            break;
                    }
                }
                // Close the prepared statement
                $rolecmd->close();
            }else{

                return "incorrectpassword";
            }
            
        }else{
            return "Nouserfound";
        }
    }

    public function userinfo($username) {
        $userdata = $this->connection->prepare("SELECT employee_id, role FROM user_account WHERE username = ?");
        $userdata->bind_param('s', $username);
        $userdata->execute();
        $userdata->store_result();
        
        if ($userdata->num_rows > 0) {
            $userdata->bind_result($employeeID, $userRole);
            $userdata->fetch();
            $userdata->close();
            
            $getdata = $this->connection->prepare("SELECT * FROM employeetbl WHERE empID = ?");
            $getdata->bind_param('i', $employeeID);
            $getdata->execute();
            
            $userResult = $getdata->get_result();
            
            if ($userResult->num_rows > 0) {
                $userInformation = $userResult->fetch_assoc(); // Fetch user information
                $getdata->close();
                
                $getroleName = $this->connection->prepare("SELECT name FROM user_role WHERE roleID = ?");
                $getroleName->bind_param('i', $userRole);
                $getroleName->execute();
                $getroleName->bind_result($roleName);
                
                if ($getroleName->fetch()) {
                    $_SESSION['empID'] = $userInformation['empID']; 
                    $_SESSION['useRole'] = $roleName;
                }
                
                $getroleName->close();
            }
        }
    }
    
    public function createNewEmployee($lastname, $firstname, $middlename, $birthday, $address, $email, $contact, $gender, $image, $hiredDate, $deptID, $salary, $empuser, $emppass, $Roleemp){
        $userAccountcmd = $this->connection->prepare("SELECT * FROM user_account WHERE username = ?");
        $userAccountcmd->bind_param('s', $empuser);
        $userAccountcmd->execute();

        $AccountResult = $userAccountcmd->get_result();

        if($AccountResult->num_rows > 0){
            return 'usernameexist';
        }else{
            $getemail = $this->connection->prepare("SELECT email FROM employeetbl WHERE email = ? ");
            $getemail->bind_param('s', $email);
            $getemail->execute();
            $getresult = $getemail->get_result();
    
            if(!$getresult->num_rows > 0){
                $addemployeecmd = $this->connection->prepare("INSERT INTO employeetbl (firstname, lastname, middlename, email, birthday, hired_date, address, contact, image, Gender, department_id, salaryEmp) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $addemployeecmd->bind_param('ssssssssssss', $firstname, $lastname, $middlename, $email, $birthday, $hiredDate, $address, $contact, $image, $gender, $deptID, $salary);
               
        
                if($addemployeecmd->execute()){
                    $lastemployehired = $this->connection->insert_id;
                    $hashedPassword = password_hash($emppass, PASSWORD_DEFAULT);
                    $employeeAccountCMD = $this->connection->prepare("INSERT INTO user_account (employee_id, username, password, role) VALUES (?, ?, ?, ?)");
                    $employeeAccountCMD->bind_param('issi', $lastemployehired, $empuser, $hashedPassword, $Roleemp);
    
                    if($employeeAccountCMD->execute()){
                        //send email here
                        $this->sendEmail($email, $empuser, $emppass);
                        return 'success';
                    }
    
                    
                }else{
                    return false;
                }
    
            }else{
                return "emailexists";
            }
        }
        
    }

    public function displayemployee(){
        //Get the list of employee with account
        $getlistcmd = $this->connection->prepare("SELECT emp.*, TIMESTAMPDIFF(YEAR, emp.birthday, CURDATE()) -
        (CURDATE() < DATE_FORMAT(birthday, '%Y-%m-%d')) AS age, role.name FROM employeetbl AS emp 
        RIGHT JOIN user_account as userAcc ON emp.empID = userAcc.employee_id 
        RIGHT JOIN user_role AS role ON userAcc.role = role.roleID WHERE userAcc.accID");
        $getlistcmd->execute();
        $results = $getlistcmd->get_result();
        $getresult = [];

        if($results->num_rows > 0){
            $getresult[] = $results->fetch_all(MYSQLI_ASSOC);
        }

        return[
            'employeelist' => $getresult
        ];
        
    }

    public function departmentlist(){
        $deptcmd = $this->connection->prepare("SELECT * FROM department");
        $deptcmd->execute();
        $deptResult = $deptcmd->get_result();
      
        if($deptResult->num_rows > 0){
            $getlistdept = $deptResult->fetch_all(MYSQLI_ASSOC);
        }else{
            $getlistdept = [];
        }
            return [
                'deptlist' => $getlistdept
            ];
      
    }

    public function employeerole(){
        $rolecmd = $this->connection->prepare("SELECT * FROM user_role");
        $rolecmd->execute();
        $roleResult = $rolecmd->get_result();
        $getlistrole = [];
        if($roleResult->num_rows > 0){
            $getlistrole = $roleResult->fetch_all(MYSQLI_ASSOC);
            return[
                'rolelist' => $getlistrole
            ];
        }else{
            return [
                'rolelist' => $getlistgetlistroledept
            ];
        }
    }


    public function employeeInformation($userID){

        $employeeData = array();

        $employeeIDcmd = $this->connection->prepare("SELECT emp.*, emp.image, dept.name AS name FROM employeetbl AS emp 
        RIGHT JOIN department AS dept ON emp.department_id = dept.id WHERE emp.empID = ?");
        $employeeIDcmd->bind_param('i', $userID);
        $employeeIDcmd->execute();
            
        $employeeResult = $employeeIDcmd->get_result();
    
        if ($employeeResult->num_rows > 0) {
            //get data of employee
            while ($row = $employeeResult->fetch_assoc()) {
                $employeeData[] = $row;
            }
        } else {
            return false; // No results found
        }
        return $employeeData;
    }

    public function DisplayAttendance($userID){
        $Attendance = array();

        $EmployeeAttend = $this->connection->prepare("SELECT attnd.date, attnd.in_time, attnd.out_time, attnd.in_status, attnd.out_status, emp.firstname, emp.lastname, emp.middlename FROM attendanceemp AS attnd
        RIGHT JOIN employeetbl AS emp ON attnd.employee_id = emp.empID WHERE employee_id = ? ORDER BY id DESC");
        $EmployeeAttend->bind_param('i', $userID);
        $EmployeeAttend->execute();

        $getResultAttend = $EmployeeAttend->get_result();

        if($getResultAttend->num_rows > 0){

            while($rowData = $getResultAttend->fetch_assoc()){
                $Attendance[] = $rowData;
            }
            return $Attendance;
        }
    }

    public function updateEmployeeWithImage($userID, $dept,  $fname, $lname, $mname, $birth, $hired, $address, $cont, $salary, $gender, $email, $image){
        $getinfocmd = $this->connection->prepare("SELECT * FROM employeetbl WHERE empID = ?");
        $getinfocmd->bind_param('i', $userID);
        $getinfocmd->execute();
        $getResult = $getinfocmd->get_result();
        
        if($getResult->num_rows > 0 ){

            $getdeptcmd = $this->connection->prepare("SELECT id FROM department WHERE name = ?");
            $getdeptcmd->bind_param('s', $dept);
            $getdeptcmd->execute();
            $getdeptcmd->store_result();
            

            if ($getdeptcmd->num_rows > 0) {
                $getdeptcmd->bind_result($deptId);
                $getdeptcmd->fetch();
                $getdeptcmd->close();
      
                $updatecmd = $this->connection->prepare("UPDATE employeetbl SET firstname = ?, lastname = ?, middlename = ?, email = ?, birthday = ?, hired_date = ?, address = ?, contact = ?, image = ?, Gender = ?, department_id = ?, salaryEmp = ? WHERE empID = ?");
                $updatecmd->bind_param('ssssssssssssi', $fname, $lname, $mname, $email, $birth, $hired, $address, $cont, $image, $gender, $deptId, $salary, $userID);
                
                if($updatecmd->execute()){
                    $updatecmd->close();
                    return true;
                }else{
                    return 'notupdated';
                }

            } else {
                // Department not found, handle this case accordingly
                return "Deptnotfound";
            }
        }else{
            return 'notfound';
        }
    }

    public function updateEmployeewithoutImage($userID, $deptId, $fname, $lname, $mname, $birth, $hired, $address, $cont, $salary, $gender, $email){

        $getinfocmd = $this->connection->prepare("SELECT * FROM employeetbl WHERE empID = ?");
        $getinfocmd->bind_param('i', $userID);
        $getinfocmd->execute();
        $getResult = $getinfocmd->get_result();

        if($getResult->num_rows > 0 ){

            //get department
            $getdeptcmd = $this->connection->prepare("SELECT id FROM department WHERE name = ?");
            $getdeptcmd->bind_param('s', $deptId);
            $getdeptcmd->execute();
            $getdeptcmd->store_result();
            if ($getdeptcmd->num_rows > 0) {

                $getdeptcmd->bind_result($dept);
                $getdeptcmd->fetch();
                $getdeptcmd->close();
               // echo $dept;
              
                // Department found, $deptId contains the ID of the department
                // Proceed with the rest of your code here
                $updatecmd = $this->connection->prepare("UPDATE employeetbl SET firstname = ?, lastname = ?, middlename = ?, email = ?, birthday = ?, hired_date = ?, address = ?, contact = ?, Gender = ?, department_id = ?, salaryEmp = ? WHERE empID = ?");
                $updatecmd->bind_param('sssssssssssi', $fname, $lname, $mname, $email, $birth, $hired, $address, $cont, $gender, $dept, $salary, $userID);
                
                // Execute the statement
                if ($updatecmd->execute()) {
                    // Query executed successfully
                    return true;
                } else {
                   return 'notupdated';
                }
                // Close the statement
                $updatecmd->close();
            } else {
                // Department not found, handle this case accordingly
                return "Deptnotfound";
            }
        }else{
            return 'notfound';
        }
    }

    public function employeeResigned($userID){

        $resigncmd = $this->connection->prepare("SELECT * FROM employeetbl WHERE empID = ?");
        $resigncmd->bind_param('i', $userID);
        $resigncmd->execute();
        $result = $resigncmd->get_result();

        if($result->num_rows > 0){

            $deleteAccount = $this->connection->prepare("DELETE FROM user_account WHERE employee_id = ?");
            $deleteAccount->bind_param('i', $userID);
            $deleteAccount->execute();
            
            $attendanceID = $this->connection->prepare("SELECT * FROM attendanceemp WHERE employee_id = ?");
            $attendanceID->bind_param('i', $userID);
            $attendanceID->execute();
            $results = $attendanceID->get_result();

            if($results->num_rows > 0){
                $deleteAttend = $this->connection->prepare("DELETE FROM attendanceemp WHERE employee_id = ?");
                $deleteAttend->bind_param('i', $userID);
                $deleteAttend->execute();
            }

            $getdata = $this->connection->prepare("DELETE FROM employeetbl WHERE empID = ? ");
            $getdata->bind_param('i', $userID);
            $getdata->execute();

            return true;

        }else{
            return false;
        }


    }


    private function sendEmail($email, $username, $password){

            require 'mailer/vendor/autoload.php';
            $mail = new PHPMailer(true); // Enable PHPMailer
            $mail->isSMTP(); // Use SMTP
            $mail->Host = "smtp.gmail.com"; // Gmail's SMTP server
            $mail->SMTPAuth = true; // Enable SMTP authentication

            $mail->Username = 'refe.s.bsinfotech@gmail.com'; // Your Gmail username
            $mail->Password = 'xazamwvynlrtesxv'; // Your Gmail app password


            $mail->SMTPSecure = "tls"; // TLS (Transport Layer Security)
            $mail->Port = 587;

            $mail->setFrom('noreply@gmail.com', 'FosconShip');// Sender's email address
            $mail->addAddress($email); // Recipient's email address


            $mail->Subject = "FosconShip";

            $mail->isHTML(true); // Enable HTML email
            
            $mail->Body = "<html>
          <p>Congrats you're hired in our company and here's your account information to login and proceed to your daily attendance record
           </p>
           
           <p> Username: $username<p>
           <p>Password: $password<p>
           </htmL>
            ";
            try {
                $mail->send(); // Send the email
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    }

    public function employeeTimein($userID){
        // Get the current time in 12-hour format with AM/PM
        date_default_timezone_set('Asia/Manila');
        $current_time = date('h:i A');

        $currentDate = date('Y-m-d');
    
        // Set the in-time
        $inTime = "08:00 AM";
    
        // Convert time strings to Unix timestamps for accurate comparison
        $current_timestamp = strtotime($current_time);
        $inTime_timestamp = strtotime($inTime);

        $timeInStatus = '';
        // Compare times
       if ($inTime_timestamp == $current_timestamp) {
            $timeInStatus = "On Time";
        } elseif($current_timestamp < $inTime_timestamp) {
            $timeInStatus = "Early Time";
        }else{
            $timeInStatus = "Late";
        }

        //getDept
        $getdept = $this->connection->prepare("SELECT department_id FROM employeetbl WHERE empID = ?");
        $getdept->bind_param('i', $userID);
        $getdept->execute();
        $getdept->store_result();

        if($getdept->num_rows > 0){
            $getdept->bind_result($deptID);
            $getdept->fetch();
            $getdept->close();
            $attendanceCmd = $this->connection->prepare("INSERT INTO attendanceemp (employee_id, dept_id, date, in_time, in_status) VALUES (?, ?, ?, ?, ?)");
            $attendanceCmd->bind_param('issss', $userID, $deptID, $currentDate, $current_time, $timeInStatus);
            $attendanceCmd->execute();
            return true;
        }else{
            return false;
        }


    }

    public function employeeTimeOut($userID, $attendID){
         date_default_timezone_set('Asia/Manila');
         $current_time = date('h:i A');
         $currentDate = date('Y-m-d');

         $OutTime = '05:00 PM';

        $getAttendance = $this->connection->prepare("SELECT * FROM attendanceemp WHERE id = ?");
        $getAttendance->bind_param('i', $attendID);
        $getAttendance->execute();
        
        $getResult = $getAttendance->get_result();

        if($getResult->num_rows > 0){

              $getData = $getResult->fetch_assoc();

              $attendanceID = $getData['id'];

                $current_timestamp = strtotime($current_time);
                $outTime_timestamp = strtotime($OutTime);
                
                $timeOutStatus = '';

                if ($outTime_timestamp == $current_timestamp) {
                    $timeOutStatus = "On Time";
                } elseif($current_timestamp < $outTime_timestamp) {
                    $timeOutStatus = "Early out";
                }else{
                    $timeOutStatus = "Over Time";
                }

                $updateAttendance = $this->connection->prepare("UPDATE attendanceemp SET out_time = ?, out_status = ? WHERE id = ?");
                $updateAttendance->bind_param('ssi', $current_time, $timeOutStatus, $attendanceID);
                $updateAttendance->execute();

                return true;
        }
    }

    public function updatepassword($userID, $password){
        $getold = $this->connection->prepare("SELECT password FROM user_account WHERE employee_id = ?");
        $getold->bind_param('i', $userID);
        $getold->execute();
        $getold->store_result();

        if($getold->num_rows > 0){
            $getold->bind_result($oldpassword);
            $getold->fetch();

            if(password_verify($password, $oldpassword)){
                return false;
            }else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $cpassword = $this->connection->prepare("UPDATE user_account SET password = ? WHERE employee_id = ?");
                $cpassword->bind_param('si', $hashedPassword, $userID);
                
                if($cpassword->execute()){
                    return true;
                }else{
                    return false;
                }
                
            }
        }
    }

   
    


}
?>