<?php
require_once '../connection/usersconfig.php';

$updateEmployee = new users($connection);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if(isset($_POST['viewEmpID'])){
        $viewEmpID = $_POST['viewEmpID'];
        $viewlname = $_POST['viewlname'];
        $viewfname = $_POST['viewfname'];
        $viewmname = $_POST['viewmname'];
        $viewbirthday = $_POST['viewbirthday'];
        $viewgender = $_POST['viewgender'];
        $viewaddress = $_POST['viewaddress'];
        $viewemail = $_POST['viewemail'];
        $viewcontact = $_POST['viewcontact'];
        $viewhireddate = $_POST['viewhireddate'];
        $viewdeptID = $_POST['viewdeptID'];
        $viewsalaryEmp = $_POST['viewsalaryEmp'];

     
        
        if(isset($_FILES['NewImage'])){

            $file_data = $_FILES['NewImage']['name'];
            $image_size = $_FILES['NewImage']['size'];
            $img_err = $_FILES['NewImage']['error'];


            if($img_err === 0){
                if($image_size > 3145728) //3mb.
                {
                    echo json_encode(array('failed' => 'This image you have submitted is too large.'));
                  exit();
                }else{
                    $image_ex = pathinfo($file_data, PATHINFO_EXTENSION);
                    $image_ex_lc = strtolower($image_ex);
        
                    $allow_exs = array('jpg', 'jpeg', 'png');
                    if(in_array($image_ex_lc, $allow_exs))
                    {
                        $image = $_FILES['NewImage']['tmp_name'];
                        
                        $imgContent = file_get_contents($image);

                        $programConfirmation = $updateEmployee->updateEmployeeWithImage($viewEmpID, $viewdeptID, $viewfname, $viewlname,
                        $viewmname, $viewbirthday, $viewhireddate, $viewaddress, $viewcontact, $viewsalaryEmp, $viewgender,
                        $viewemail, $imgContent);

                            if($programConfirmation === 'notupdated'){
                                echo json_encode(array('failed' => 'Employee failed to update'));
                                exit;
                            }elseif($programConfirmation === 'Deptnotfound'){
                                echo json_encode(array('failed' => 'Employee failed to update department not found'));
                                exit;
                            }elseif($programConfirmation === 'notfound'){
                                echo json_encode(array('failed' => 'Employee failed to update employee not found'));
                                exit;
                            }elseif($programConfirmation){
                                echo json_encode(array('success' => 'Success fully updated'));
                                exit;
                            }

                    }
                    else
                    {
                        echo json_encode(array('failed' => 'Image format must be Jpg, jpeg and png'));
                        exit();
                    }
                }
    
            }else{
                 echo json_encode(array('failed' => 'Image error'));
                exit();
            }
        }else{
            $getResult = $updateEmployee->updateEmployeewithoutImage($viewEmpID, $viewdeptID, $viewfname, $viewlname,
                                                                        $viewmname, $viewbirthday, $viewhireddate, $viewaddress, $viewcontact, $viewsalaryEmp, $viewgender,
                                                                        $viewemail);
            if($getResult === 'notupdated'){
                echo json_encode(array('failed' => 'Employee failed to update'));
                exit;
            }elseif($getResult === 'Deptnotfound'){
                echo json_encode(array('failed' => 'Employee failed to update department not found'));
                exit;
            }elseif($getResult === 'notfound'){
                echo json_encode(array('failed' => 'Employee failed to update employee not found'));
                exit;
            }elseif($getResult){
                echo json_encode(array('success' => 'Success fully updated'));
                exit;
            }
        }

    }

}
?>