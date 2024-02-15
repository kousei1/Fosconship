<?php
require_once '../connection/usersconfig.php';

if(!isset($_SESSION['empID'])){
  header("Location: ../fosconship-login.php");
}else{

  if($_SESSION['useRole'] != 'Admin'){
    header("Location: ../fosconship-login.php");
  }
}


$getlistemployee = new users($connection);

$getlist = $getlistemployee->displayemployee();
$employee = $getlist['employeelist'];

$getdept = $getlistemployee->departmentlist();
$deptlist = $getdept['deptlist'];

$getpos = $getlistemployee->employeerole();
$getposlist = $getpos['rolelist'];

$profile = $getlistemployee->employeeInformation($_SESSION['empID']);
if(!empty($profile)){
  foreach($profile as $row){
    $lastname = $row['lastname'];
    $firstname = $row['firstname'];
    $middlename = $row['middlename'];
    $email = $row['email'];
    $gender = $row['Gender'];
    $image = base64_encode($row['image']);
    $birthday = $row['birthday'];
    $contact = $row['contact'];
    $address = $row['address'];
  }
}else{
  header("Location: ../fosconship-login.php");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Components / Accordion - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/fosconLogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

</head>

<body>
<style>

    .custom-wrapper {
    width: 100% !important;
    overflow-x: auto;
}

  </style>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class=" d-flex align-items-center">
        <img src="../assets/img/fosconLogo.png" class="img-thumnail" alt="" style="height: 75px;">
        <!-- <span class="d-none d-lg-block">FosconShip</span> -->
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

   

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="data:image/png;base64,<?= $image ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $lastname ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?= $lastname. ', '. $firstname ?></h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="profile-information.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="../signout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
<!-- 
      <li class="nav-item">
        <a class="nav-link collapsed" href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li> -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class=" ri-account-pin-box-line"></i>
          <span>Employee</span>
        </a>
      </li><!-- End Contact Page Nav -->




 


    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Employee</h1>
     
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-success my-4" data-bs-toggle="modal" data-bs-target="#addemployee">Add Employee</button>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Employee list</h5>
              <div class="table-container">
              <table id="example" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>age</th>
                                <th>Hired</th>
                                <th>Salary</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        <?php 
                        if(!empty($employee)){
                          foreach($employee[0] as $row) : ?>

                            
                           <tr>
                                <td><?php if(isset($row['image'])){ ?>
                                  <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" style="width: 120px; height: 100px;" alt="">

                                <?php } ?></td>
                                <td><?= $row['firstname']. ', '. $row['lastname']. ' '. $row['middlename'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['department_id'] ?></td>
                                <td><?= $row['age'] ?></td>
                                <td><?= $row['hired_date'] ?></td>
                                <td>&#8369; <?= $row['salaryEmp'] ?></td>
                                <td>
                                <button class="btn btn-success editemp" data-bs-toggle="modal" id="<?= $row['empID'] ?>" data-bs-target="#editemployee"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-danger resignEmp" id="<?= $row['empID'] ?>"><i class="bi bi-trash"></i></button> 
                                <button class="btn btn-primary attendEmp" data-bs-toggle="modal" id="<?= $row['empID'] ?>" data-bs-target="#viewAttend"><i class="bi bi-eye"></i></button> 
                                </td>
                            </tr>


                        <?php endforeach; } ?>
                        </tbody>
                </table>
              </div>
            
            </div>
          </div>

        </div>

      </div>
    </section>
  </main><!-- End #main -->

  <div class="modal fade" id="addemployee" aria-hidden="true" data-bs-backdrop="static" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Hired new employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form  method="post" enctype="multipart/form-data" id="uploadnewemployee">
           <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="alert d-none" role="alert" id="alert">
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <h3>Personal Information</h3>
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="">Last Name</label>
                    <input type="text" class="form-control" name="" class="" id="lname">
                </div>
                <div class="col-lg-4 mb-2">
                <label for="">First Name</label>
                    <input type="text" class="form-control" name="" id="fname">
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="">Middle Name</label>
                    <input type="text" class="form-control" name="" id="mname">
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="">Birthday</label>
                    <input type="date" class="form-control" name="" id="birthday">
                </div>
                <div class="col-lg-8  mb-2">
                    <label for="">Address</label>
                    <input type="text" class="form-control" name="" id="address">
                </div>
                <div class="col-lg-6  mb-2">
                    <label for="">Email</label>
                    <input type="email" class="form-control" name="" id="email">
                </div>
                <div class="col-lg-6  mb-2">
                    <label for="">contact</label>
                    <input type="number" class="form-control" name="" id="contact">
                </div>
                <div class="col-lg-3  mb-2">
                    <label for="">Gender</label>
                    <select class="form-select" name="" id="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-lg-3  mb-2">
                    <label for="">Date Hired</label>
                    <input type="date" class="form-control" name="" id="hireddate">
                </div>
                <div class="col-lg-6  mb-2">
                    <label for="">Image</label>
                    <input type="file" name="" id="empImage" class="form-control">
                </div>
                <div class="col-lg-6">
                    <label for="">Department</label>
                    <select class="form-select" name="" id="deptID">
                      <?php 
                      if(!empty($deptlist)){
                        foreach($deptlist as $row) : ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>

                     <?php endforeach; } ?>
                    </select>
                </div>

                <div class="col-lg-6">
                    <label for="">Salary</label>
                    <select class="form-select" name="" id="salaryEmp">
                          <option value="" selected disabled>Select salary</option>
                          <option value="35000">&#8369; 35000</option>
                          <option value="45000">&#8369; 45000</option>
                          <option value="30000">&#8369; 30000</option>
                    </select>
                </div>

                <div class="col-lg-12 text-center my-3">
                    <h4>Account information</h4>
                </div>

                <div class="col-lg-4">
                    <label for="">username</label>
                    <input type="text" class="form-control" name="" id="empuser">
                </div>
                <div class="col-lg-4">
                    <label for="">password</label>
                    <input type="password" class="form-control" name="" id="emppass">
                </div>
                <div class="col-lg-4">
                    <label for="">Position</label>
                    <select name="" class="form-select" id="Roleemp">
                      <?php if(!empty($getposlist)){
                        foreach($getposlist as $row) : ?>
                          <option value="<?= $row['roleID'] ?>"><?= $row['name'] ?></option>


                    <?php endforeach;  } ?>
                        
                    </select>
                </div>



           </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         <button type="submit"  class="btn btn-primary w-25">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="viewAttend" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Attendance Employee</h1>
        <button type="button" id="reloadButton1" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="viewName"></h5>
          <div style="height: 625px; overflow-y: auto;">
            <table class="table" id="attendanceTable">
              <thead>
                <th>Date</th>
                <th>Time In</th>
                <th>Time out</th>
                <th>In Status</th>
                <th>out Status</th>
              </thead>
              <tbody>
              
              </tbody>
            </table>
          </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="reloadButton" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Back</button>
      </div>
    </div>
  </div>
</div>




<!-- Edit Employee -->
<div class="modal fade" id="editemployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Employee Information</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                          
      <form  method="post" enctype="multipart/form-data" id="updateemployee">
           <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="alert d-none" role="alert" id="editalert">
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <h3>Personal Information</h3>
                </div>

                <div class="col-lg-12 mb-2">
                          <div class="d-flex justify-content-center">
                          <span id="Employeeimg"></span>
                          </div>
                          <div class="d-flex justify-content-center">
                          
                          <label for="NewImage" class="btn btn-light mt-2">
                                                UPLOAD
                          <input type="file" id="NewImage"  class="form-control UploadNewImage" style="display: none;">
                          </label>
                          </div>
                   
                    <!-- <input type="file" name="" id="empImage" class="form-control"> -->
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="">Last Name</label>
                    <input type="hidden" id="viewEmpID">
                    <input type="text" class="form-control" name="" class="" id="viewlname">
                </div>
                <div class="col-lg-4 mb-2">
                <label for="">First Name</label>
                    <input type="text" class="form-control" name="" id="viewfname">
                </div>
                <div class="col-lg-4 mb-2">
                    <label for="">Middle Name</label>
                    <input type="text" class="form-control" name="" id="viewmname">
                </div>
                <div class="col-lg-3 mb-2">
                    <label for="">Birthday</label>
                    <input type="date" class="form-control" name="" id="viewbirthday">
                </div>
                <div class="col-lg-3  mb-2">
                    <label for="">Gender</label>
                    <select class="form-select" name="" id="viewgender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                
                <div class="col-lg-6  mb-2">
                    <label for="">Address</label>
                    <input type="text" class="form-control" name="" id="viewaddress">
                </div>

                <div class="col-lg-6  mb-2">
                    <label for="">Email</label>
                    <input type="email" class="form-control" name="" id="viewemail">
                </div>
                
                <div class="col-lg-6  mb-2">
                    <label for="">contact</label>
                    <input type="number" class="form-control" name="" id="viewcontact">
                </div>
                
                <div class="col-lg-3  mb-2">
                    <label for="">Date Hired</label>
                    <input type="date" class="form-control" name="" id="viewhireddate">
                </div>
                
                <div class="col-lg-6">
                    <label for="">Department</label>
                    <select class="form-select" name="" id="viewdeptID">
                      <?php 
                      if(!empty($deptlist)){
                        foreach($deptlist as $row) : ?>
                        <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>

                     <?php endforeach; } ?>
                    </select>
                </div>

                <div class="col-lg-3">
                    <label for="">Salary</label>
                    <select class="form-select" name="" id="viewsalaryEmp">
                    <option value="" selected disabled>Select salary</option>
                          <option value="35000">&#8369; 35000</option>
                          <option value="45000">&#8369; 45000</option>
                          <option value="30000">&#8369; 30000</option>
                    </select>
                </div>

           </div>
           
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         <button type="submit"  class="btn btn-primary w-25">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>




  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
   
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->

  
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="./assets/js/newEmployee.js"></script>
  <script src="./assets/js/viewEmployee.js"></script>
  <script src="./assets/js/viewAttendance.js"></script>
  <script src="./assets/js/updateEmployee.js"></script>
  <script src="./assets/js/employeeResign.js"></script>
  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script>
      new DataTable('#example', {


        "initComplete": function (settings, json) {
            $("#example").wrap('<div class="custom-wrapper"></div>');
            $('.dataTables_filter input[type="search"]').addClass('form-control');
        },
        "language": { "search": "", "searchPlaceholder": "Search..." },
      
    });
    //new DataTable('#example');
  </script>
</body>

</html>