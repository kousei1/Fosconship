<?php
session_start();
if(isset($_SESSION['empID'])){
  if($_SESSION['useRole'] == 'Employee'){
    header("Location: ./Employee/");
  }elseif($_SESSION['useRole'] == 'Admin'){
    header("Location: ./admin/");
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/img/fosconLogo.png" rel="icon">
    <title>FosconShip</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/components/logins/login-10/assets/css/login-10.css">
</head>
<body>


<!-- Login 10 - Bootstrap Brain Component -->
<section class="bg-light py-3 py-md-5 py-xl-8">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <div class="mb-5">
          <div class="text-center mb-4">
            <a href="#!">
              <img src="./assets/img/fosconLogo.png" alt="BootstrapBrain Logo" width="200" height="200">
            </a>
          </div>
         
        </div>
        <div class="card border border-light-subtle rounded-4">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <form action="loginuser.php" method="post">
              <?php
              if(isset($_SESSION['validationlogin'])){
                echo $_SESSION['validationlogin'];
                unset($_SESSION['validationlogin']);
              }else{

              }
              ?>
              <div class="row gy-3 overflow-hidden">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="email" placeholder="username">
                    <label for="email" class="form-label">username</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" value="" placeholder="Password">
                    <label for="password" class="form-label">Password</label>
                  </div>
                </div>
            
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn" type="submit" style="background-color: #1c1c84; color: white;">Log in</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
    
      </div>
    </div>
  </div>
</section>
    
</body>
</html>