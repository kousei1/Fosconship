<?php
require '../connection/usersconfig.php';
if(!isset($_SESSION['empID'])){
  header("Location: ../fosconship-login.php");
}

$displayProfile = new users($connection);
$profile = $displayProfile->employeeInformation($_SESSION['empID']);
$attendance = $displayProfile->DisplayAttendance($_SESSION['empID']);
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

  <title>Employee Homepage</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/fosconLogo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="d-flex align-items-center">
      <img src="../assets/img/fosconLogo.png" class="img-thumnail" alt="" style="height: 75px;">
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
              <h6><?= $lastname. ' '. $firstname ?></h6>
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

   


 


    </ul>

  </aside>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Profile</h1>
    
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <img src="data:image/png;base64,<?= $image ?>" alt="Profile" class="rounded-circle">
              <h2><?= $lastname. ', '. $firstname ?></h2>
             
            </div>
          </div>

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
            <?php 
                  // Get the current date
                  $today = date('Y-m-d');
                  // Prepare and execute the query to check attendance status for the current employee and date
                  $TimeEmployee = $connection->prepare("SELECT id FROM attendanceemp WHERE employee_id = ? ORDER BY id DESC LIMIT 1");
                  $TimeEmployee->bind_param('i', $_SESSION['empID']);
                  $TimeEmployee->execute();
                  $TimeEmployee->store_result();

                  if($TimeEmployee->num_rows > 0){
                    $TimeEmployee->bind_result($attendanceID);
                    $TimeEmployee->fetch();
                  //  echo $attendanceID;
                    $getStatus = $connection->prepare("SELECT in_status, out_status FROM attendanceemp WHERE id = ? ");
                    $getStatus->bind_param('i', $attendanceID);
                    $getStatus->execute();
                        $getStatus->store_result();
  
                        // Check if there is at least one attendance record for today
                        if ($getStatus->num_rows > 0) {
                            $getStatus->bind_result($inStatus, $out_status);
                            $getStatus->fetch();
                            $getStatus->close();
                            // Check if the employee has already clocked out
                            if ($inStatus !== '' && $out_status == '' ) { ?>
                                <button class="btn btn-danger w-100 p-4 fs-3 empoutTime" id='<?= $attendanceID ?>'>Time out</button>
                                
                            <?php } elseif($out_status !== '' && $inStatus !== '') { ?>
                              <button class="btn btn-success w-100 p-4 fs-3" id='empTime'>Time In</button>
                        <?php } } ?>
                         
                        <?php }else{ ?>
                    <button class="btn btn-success w-100 p-4 fs-3" id='empTime'>Time In</button>
                  <?php } ?>

            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Attendance Details</button>
                </li>

                <!-- <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                </li> -->

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                    <h5 class="card-title">Profile Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                    <div class="col-lg-9 col-md-8"><?= $lastname. ', '. $firstname. ' '. $middlename ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Gender</div>
                    <div class="col-lg-9 col-md-8"><?= $gender ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Birthday</div>
                    <div class="col-lg-9 col-md-8"><?= $birthday ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Address</div>
                    <div class="col-lg-9 col-md-8"><?= $address ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Contact</div>
                    <div class="col-lg-9 col-md-8"><?= $contact ?></div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8"><?= $email ?></div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                      <div style="height: 625px; overflow-y: auto;">
                      <table class="table" >
                        <thead>
                          <th>Date</th>
                          <th>Time In</th>
                          <th>In Status</th>
                          <th>Time out</th>
                          <th>Out Status</th>
                        </thead>
                        <tbody>
                          <?php 
                          if(!empty($attendance)){
                          foreach($attendance as $display) : ?>
                            <tr>
                              <td><?= $display['date'] ?></td>
                              <td><?= $display['in_time'] ?></td>
                              <td><?= $display['in_status'] ?></td>
                              <td><?= $display['out_time'] ?></td>
                              <td><?= $display['out_status'] ?></td>
                            </tr>
                          <?php endforeach;  
                          } ?>
                        </tbody>
                      </table>
                      </div>

                </div>
                

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                 <span id="alertmsg"></span>
                  <form method="post">
                    <div class="row mb-3">
                      <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="newpassword" type="password" class="form-control" id="newPassword">
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                      <div class="col-md-8 col-lg-9">
                        <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      </div>
                    </div>

                    <div class="text-center">
                      <button type="button" id="changePasswordBtn" class="btn btn-primary">Change Password</button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->


 <!-- Vendor JS Files -->
 <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>
  <script src="./assets/employeeTime.js"></script>
  <script src="./assets/passwordchange.js"></script>
  <!-- Template Main JS File -->

</body>

</html>