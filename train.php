<?php
    // Initialize the session
    session_start();
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php") ;
        exit ;
    }
    // Include config file
    require "config.php"; 
    // Define variables and initialize with empty values
    $tno = $phone = $tdate = "" ;
    $tno_err = $phone_err = $tdate_err = "" ;
    $naam = $_SESSION["username"];    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Check if train no. is empty
        if(empty(trim($_POST["tno"])))
        { $tno_err = "Please enter train number." ; } 
        else
        { $tno = trim($_POST["tno"]) ; }
        // Check if phone number is empty
        if(empty(trim($_POST["phone"])))
        { $phone_err = "Please enter your phone number." ; } 
        else
        { $phone = trim($_POST["phone"]) ; }
        // Check if date is empty
        if(empty(trim($_POST["tdate"])))
        { $tdate_err = "Please enter the date." ; } 
        else
        { $tdate = trim($_POST["tdate"]) ; }
        // Validate credentials
        // Check input errors before inserting in database
        if(empty($tno_err) && empty($phone_err) && empty($tdate_err))
        {
            // Prepare an insert statement
            $sql = "INSERT INTO TrainInfo (username, phone, tno, tdate) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_naam, $param_phone, $param_tno, $param_tdate);
                // Set parameters
                $param_naam = $naam;
                $param_phone = $phone;
                $param_tno = $tno;
                $param_tdate = $tdate;
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // Redirect to success page
                    header("location: traint.php");
                } 
                else
                {
                    echo "Something went wrong. Please try again later.";
                }
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        // Close connection
        mysqli_close($link);
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Train | Cabshare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/train.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800,600,300' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/modernizr.custom.js"></script>
</head>
<body>
<!-- Navigation ==========================================-->
  <nav id="menu" class="navbar navbar-default navbar-fixed-top">
  <div class="container"> 
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="101.php"><i class="fa fa-car"></i>CabShare<strong></strong></a> 
    </div>    
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="table.php" class="page-scroll">My Bookings</a></li>
        <li><a href="bus.html" class="page-scroll">Bus</a></li>
        <li><a href="welcome.php" class="page-scroll">Cab</a></li>
        <li><a href="flight.php" class="page-scroll">Flight</a></li>
        <li><a href="101.php#contact-section" class="page-scroll">Contact Us</a></li>
        <li><a href="reset-password.php" class="page-scroll">Reset Password</a></li>
        <li><a href="logout.php" class="page-scroll">Log Out</a></li>  
      </ul>
    </div>
  </div>
  </nav>
<!-- Header -->
    <div class="starter">
        <p> Enter your Train below. </p>
        <p> This helps you to find people on the same train as yours. </p>
    </div>        
<!-- FORM -->            
    <div class="form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <p class="sue-storm">  .   </p>
            <div class="form-group <?php echo (!empty($tno_err)) ? 'has-error' : ''; ?>">
                <i>TRAIN NO : </i><br>
                <input type="text" name="tno" class="form-control" value="<?php echo $tno; ?>">
                <span class="help-block"><?php echo $tno_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <i>PHONE NUMBER : </i><br>
                <input type="text" name="phone" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($tdate_err)) ? 'has-error' : ''; ?>">
                <i>DATE OF FLIGHT : </i><br>
                <input type="date" name="tdate" class="form-control" value="<?php echo $tdate; ?>">
                <span class="help-block"><?php echo $tdate_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="button button-block" value="Submit">
                <p>Look for previous train matches ?? <a href="traint.php"> Click Here !! </a></p>
            </div>
        </form>
        <p class="sue-storm">  .   </p>
    </div>
<!-- Footer -->
    <div id="social-section">
        <div class="social">
         <ul>
            <li><i class="fa fa-facebook"></i></li>
            <li><i class="fa fa-twitter"></i></li>
            <li><i class="fa fa-github"></i></li>
            <li><i class="fa fa-instagram"></i></li>
            <li><i class="fa fa-linkedin"></i></li>
         </ul>
        </div>
    </div>
    <div id="footer">
        <div class="fnav">
            <p>Copyright &copy; CabShare. Designed by 1601CS30 & 1601CS02.</p>
        </div>
    </div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script type="text/javascript" src="js/jquery.1.11.1.js"></script> 
<!-- Javascripts
    ================================================== --> 
<script type="text/javascript" src="js/main.js"></script>    
</body>
</html>