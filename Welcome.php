<?php
session_start();

// Redirect to login page if the user is not logged in
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    header("location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome dashboard</title>
    <link rel="stylesheet" href="dashstyle.css">
    <script src="https://kit.fontawesome.com/2edfbc5391.js"crossorigin="anonymous"></script>
    <style>
      .far {
        color: #a7abde;
        padding-left: 15px;
      }
      .col-div-3 .box p {
        font-size: 25px;
      }
      .box .fas {
        color: #a7abde;
        position: absolute;
        padding-top: 50px;
      }
      .box .fas .fa-home {
        color: #a7abde;
        padding-left: 20px;
      }
    </style>
</head>
<body>
<input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn"></i>
      </label>
      <div class="left_area">
        <h3>Residential<span>System</span></h3>
      </div>
      <div class="right_area">
        <a href="logout.php" class="logout_btn">Logout</a>
      </div>
    </header>
    <!--header area end-->
    <!--sidebar start-->
    <div class="sidebar">
      <center>
        <img src="Images/download.png" class="profile_image" alt="">
        <h4> <?php echo $_SESSION['email']?> </h4>
      </center>
      <a href="Welcome.php" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
      <a href="noticebrd.php"><i class="fas fa-bullhorn"></i><span>Notice Board</span></a>
      <a href="complaint.php"><i class="fas fa-envelope-open-text"></i><span>Register Complaint</span></a>
      <a href="payment.php"><i class="fas fa-file-invoice-dollar"></i><span>Maintenance Payment</span></a>
      <a href="facilities.php"><i class="fas fa-coffee"></i><span>Facility Booking</a>
    </div>
    <!--sidebar end-->

    <div class="content"> 
      <h1>Welcome to User Dashboard</h1>
      <?php 
      $servername = "localhost";
      $username = "root";
      $password = "";
      $database = "usersregister";
      
      // creating connection
      $conn = mysqli_connect($servername, $username, $password, $database);

      // Check connection
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }

      // Get email from session
      $user_email = $_SESSION['email'];

      // Prepare SQL query to fetch user details
      $sql = "SELECT email, Flatno FROM registration WHERE email = ?";
      $stmt = mysqli_prepare($conn, $sql);

      // Bind the session email to the prepared statement
      mysqli_stmt_bind_param($stmt, "s", $user_email);

      // Execute the query
      mysqli_stmt_execute($stmt);

      // Get the result
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              // Extract the part before @ from email
              $email_username = explode('@', $row['email'])[0];

              echo '<div class="col-div-3">
                      <div class="box">
                          <p>'.$email_username.'<br><span>User</span></p>
                          <i class="far fa-user fa-2x"></i>
                      </div>
                    </div>
                    <div class="col-div-3">
                      <div class="box">
                          <p>'.$row['Flatno'].'<br><span>Your Flat No.</span></p>
                          <i class="fas fa-home fa-2x"></i>
                      </div>
                    </div>
                    <div class="col-div-3">
                      <div class="box">
                          <p>Shubham Vartak<br><span>Society Secretary</span></p>
                          <i class="fas fa-user-tie fa-2x"></i>
                      </div>
                    </div>';
          }
      } else {
          echo "No records found!";
      }

      // Close connection
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      ?>
    </div>
</body>
</html>
