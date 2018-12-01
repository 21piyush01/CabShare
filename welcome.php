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
    $troute = $vno = $tdate = $dtime = $ttime = $tquantity = "" ;
    $troute_err = $vno_err = $tdate_err = $dtime_err = $ttime_err = $tquantity_err = "" ;
    $naam = $_SESSION["username"];    
    $q1 = "SELECT * FROM users WHERE username = '$naam' ";
    $r1 = $link->query($q1);
    if($r1->num_rows > 0) 
    {
      while($ans = $r1->fetch_assoc()) 
      { $phone = $ans["phone"] ; }
    }

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Check if route is empty
        if(empty(trim($_POST["troute"])))
        { $troute_err = "Please enter route." ; } 
        else
        { $troute = trim($_POST["troute"]) ; }
        // Check if vno is empty
        if(empty(trim($_POST["vno"])))
        { $vno_err = "Please enter flight/train number." ; } 
        else
        { $vno = trim($_POST["vno"]) ; }
        // Check if date is empty
        if(empty(trim($_POST["tdate"])))
        { $tdate_err = "Please enter the date." ; } 
        else
        { $tdate = trim($_POST["tdate"]) ; }
        // Check if departure time is empty
        if(empty(trim($_POST["dtime"])))
        { $dtime_err = "Please enter the departure time." ; } 
        else
        { $dtime = trim($_POST["dtime"]) ; }
        // Check if cab time is empty
        if(empty(trim($_POST["ttime"])))
        { $ttime_err = "Please enter the cab arrival time." ; } 
        else
        { $ttime = trim($_POST["ttime"]) ; }
        // Validate credentials
        // Check input errors before inserting in database
        if(empty($troute_err) && empty($vno_err) && empty($tdate_err) && empty($dtime_err) && empty($ttime_err))
        {
            // Prepare an insert statement
            $sql = "INSERT INTO TravelInfo (username, phone, troute, vno, tdate, dtime, ttime) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssssss", $param_naam, $param_phone, $param_troute, $param_vno, $param_tdate, $param_dtime, $param_ttime);
                // Set parameters
                $param_naam = $naam;
                $param_phone = $phone;
                $param_troute = $troute;
                $param_vno = $vno;
                $param_tdate = $tdate;
                $param_dtime = $dtime;
                $param_ttime = $ttime;
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // Redirect to success page
                    header("location: table.php");
                } 
                else
                { echo "Something went wrong. Please try again later."; }
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
    <title>Welcome | Cabshare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/welcome.css">
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
        <li><a href="calender.php">Calender</a></li>
        <li><a href="bus.html" class="page-scroll">Bus</a></li>
<!--
        <li><a href="flight.php" class="page-scroll">Flight</a></li>
        <li><a href="train.php" class="page-scroll">Train</a></li>
-->
        <li><a href="101.php#contact-section" class="page-scroll">Contact Us</a></li>
        <li><a href="reset-password.php" class="page-scroll">Reset Password</a></li>
        <li><a href="logout.php" class="page-scroll">Log Out</a></li>  
      </ul>
    </div>
  </div>
  </nav>
<!-- Header -->
    <div class="starter">
        <h2>Welcome !! <br> <?php echo htmlspecialchars($_SESSION["username"]); ?> <br> <?php echo $phone; ?></h2>
        <p> Welcome to CabShare Service. </p>
        <p> Fill in the deatils below to share a cab. </p>
    </div>        
<!-- FORM -->            
    <div class="form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($troute_err)) ? 'has-error' : ''; ?>">
                <i>CAB ROUTE : </i><br>
                <div class="col-md-6 col-sm-6">
                    <input type="radio" name="troute" value="Clg to Airport">Clg to Airport<br>
                    <input type="radio" name="troute" value="Clg to Patna">Clg to Patna<br>
                    <input type="radio" name="troute" value="Clg to Danapur">Clg to Danapur<br>             
                </div>
                <div class="col-md-6 col-sm-6">
                    <input type="radio" name="troute" value="Airport to Clg">Airport to Clg<br>
                    <input type="radio" name="troute" value="Patna to Clg">Patna to Clg<br>
                    <input type="radio" name="troute" value="Danapur to Clg">Danapur to Clg<br>             
                </div>
                <span class="help-block"><?php echo $troute_err; ?></span>
            </div>
            <p class="sue-storm">  .   </p>
            <div class="form-group <?php echo (!empty($vno_err)) ? 'has-error' : ''; ?>">
                <i>FLIGHT/TRAIN NO. : </i><br>
                <input type="text" name="vno" class="form-control" value="<?php echo $vno; ?>">
                <span class="help-block"><?php echo $vno_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($tdate_err)) ? 'has-error' : ''; ?>">
                <i>DATE OF JOURNEY : </i><br>
                <input type="date" name="tdate" class="form-control" value="<?php echo $tdate; ?>">
                <span class="help-block"><?php echo $tdate_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($dtime_err)) ? 'has-error' : ''; ?>">
                <i>FLIGHT/TRAIN DEPARTURE TIME : </i><br>
                <input type="time" name="dtime" class="form-control" value="<?php echo $dtime; ?>">
                <span class="help-block"><?php echo $dtime_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($ttime_err)) ? 'has-error' : ''; ?>">
                <i>CAB ARRIVAL TIME : </i><br>
                <input type="time" name="ttime" class="form-control" value="<?php echo $ttime; ?>">
                <span class="help-block"><?php echo $ttime_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="button button-block" value="Submit">
                <p>Look for previous bookings ?? <a href="table.php"> Click Here !! </a></p>
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