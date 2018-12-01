<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$username = $phone = $password = $confirm_password = "";
$username_err = $phone_err = $password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
    // Validate username
    if(empty(trim($_POST["username"])))
    { $username_err = "Please enter a username."; } 
    else
    {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = trim($_POST["username"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                /* store result */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                { $username_err = "This username is already taken."; } 
                else
                { $username = trim($_POST["username"]); }
            } 
            else
            { echo "Oops! Something went wrong. Please try again later."; }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Validate phone number 
    if(empty(trim($_POST["phone"])))
    { $phone_err = "Please enter your phone number." ; } 
    elseif(strlen(trim($_POST["phone"])) < 10)
    { $phone_err = "Phone number must have exactly 10 characters." ; } 
    elseif(strlen(trim($_POST["phone"])) > 10)
    { $phone_err = "Phone number must have exactly 10 characters." ; } 
    else
    { $phone = trim($_POST["phone"]) ; }    
    // Validate password
    if(empty(trim($_POST["password"])))
    { $password_err = "Please enter a password."; } 
    elseif(strlen(trim($_POST["password"])) < 6)
    { $password_err = "Password must have atleast 6 characters."; } 
    else
    { $password = trim($_POST["password"]); }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"])))
    { $confirm_password_err = "Please confirm password."; } 
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password))
        { $confirm_password_err = "Password did not match."; }
    }
    // Check input errors before inserting in database
    if(empty($username_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err))
    {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, phone, password) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_phone, $param_password);
            // Set parameters
            $param_username = $username;
            $param_phone = $phone;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
            {
                // Redirect to login page
                header("location: login.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Reg | CabShare</title>
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/register.css">
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
        <li><a href="101.php#services-section" class="page-scroll">Services</a></li>
        <li><a href="101.php#about-section" class="page-scroll">About</a></li>
        <li><a href="101.php#contact-section" class="page-scroll">Contact Us</a></li>
      </ul>
    </div>
  </div>
  </nav>
<!-- Form -->
    <div class="form">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="email" name="username" placeholder="Your Email" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="phone" placeholder="Your Phone Number" class="form-control" value="<?php echo $phone; ?>">
                <span class="help-block"><?php echo $phone_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="button button-block" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
<!-- Footer -->
    <div id="social-section">
      <div class="container">
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
    </div>
    <div id="footer">
      <div class="container">
        <div class="fnav">
            <p>Copyright &copy; CabShare. Designed by 1601CS30 & 1601CS02.</p>
        </div>
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