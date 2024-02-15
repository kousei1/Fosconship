<?php
require_once '../connection/usersconfig.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$addemployee = new users($connection);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['lname'])) {
        $lastname = $_POST['lname'];
        $firstname = $_POST['fname'];
        $middlename = $_POST['mname'];
        $bday = $_POST['birthday'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $gender = $_POST['gender'];
        $hireddate = $_POST['hireddate'];
        $deptID = $_POST['deptID'];
        $salaryEmp = $_POST['salaryEmp'];

        $empuser = $_POST['empuser'];
        $emppass = $_POST['emppass'];
        $Roleemp = $_POST['Roleemp'];


        if($lastname == '' || $firstname == '' || $bday == '' || $address == '' || $email == '' || $contact == '' || $gender == '' || $hireddate == ''){
            echo json_encode(array('failed' => 'Fillout the form before upload new employee'));
            exit;
        }

        if (isset($_FILES['empImage'])) {

            $file_name = $_FILES['empImage']['name'];
            $file_tmp = $_FILES['empImage']['tmp_name'];
            $image_size = $_FILES['empImage']['size'];
            $img_err = $_FILES['empImage']['error'];

            if($img_err === 0){
                if($image_size > 3145728) //3mb. 256 mb 268435456 
                {
                  $_SESSION['pMsgbox'] = '<div class="alert alert-danger my-2" role="alert">
                  The image you have submitted is too large in terms of file size and surpasses the acceptable limit.
                  </div>';
                  header("Location: Program-Registration.php?program=". $Pd);
                  exit();
                }else{
                    $image_ex = pathinfo($file_name, PATHINFO_EXTENSION);
                    $image_ex_lc = strtolower($image_ex);
        
                    $allow_exs = array('jpg', 'jpeg', 'png');
                    if(in_array($image_ex_lc, $allow_exs))
                    {
                        $image = $_FILES['empImage']['tmp_name'];
                        
                        $imgContent = file_get_contents($image);

                        $results = $addemployee->createNewEmployee($lastname, $firstname, $middlename, $bday, $address, $email, $contact, $gender, $imgContent, $hireddate, $deptID, $salaryEmp, $empuser, $emppass, $Roleemp);
                        
                            if($results == 'emailexists') {
                           
                                echo json_encode(array('failed' => 'Failed to upload email already exists'));
                            //  header("Location: Program-Registration.php?program=". $Pd);
                            } else if($results == 'usernameexist') {
                                echo json_encode(array('failed' => 'Failed to upload username already exists'));
                            }elseif($results == 'success') {
                               
                                echo json_encode(array('success' => 'Successfully upload new employee'));
                                
                              //header("Location: Program-Registration.php?program=". $Pd);
                            }else{
                                echo json_encode(array('failed' => 'Try again to upload new employee'));
                             // header("Location: Program-Registration.php?program=". $Pd);
                            }
                    }
                    else
                    {
                        echo json_encode(array('failed' => 'The image you upload is not allowed image must be JPG, JPEG or PNG'));
                       // header("Location: Program-Registration.php?program=". $Pd);
                  
                    }
                }
    
            }else{
                echo json_encode(array('failed' => 'Error'));
               // header("Location: Program-Registration.php?program=". $Pd);
                exit();
            }
    
        }else{
            echo json_encode(array('failed' => 'Please upload image'));
            exit;
        }

    }
}



?>