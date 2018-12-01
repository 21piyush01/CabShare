<?php
    // Initialize the session
    session_start(); 
    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("location: welcome.php");
        exit;
    }
    // Include config file
    require_once "config.php"; 
    // Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";     
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Check if username is empty
        if(empty(trim($_POST["username"])))
        { $username_err = "Please enter username." ; } 
        else
        { $username = trim($_POST["username"]) ; }
        // Check if password is empty
        if(empty(trim($_POST["password"])))
        { $password_err = "Please enter your password." ; } 
        else
        { $password = trim($_POST["password"]) ; }
        // Validate credentials
        if(empty($username_err) && empty($password_err))
        {
            // Prepare a select statement
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                // Set parameters
                $param_username = $username;
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    // Store result
                    mysqli_stmt_store_result($stmt);
                    // Check if username exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {                    
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt))
                        {
                            if(password_verify($password, $hashed_password))
                            {
                                // Password is correct, so start a new session
                                session_start();
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;                            
                                // Redirect user to welcome page
                                header("location: welcome.php");
                            } 
                            else
                            {
                                // Display an error message if password is not valid
                                $password_err = "The password you entered was not valid.";
                            }
                        }
                    } 
                    else
                    {
                        // Display an error message if username doesn't exist
                        $username_err = "No account found with that username.";
                    }
                } 
                else
                { echo "Oops! Something went wrong. Please try again later." ; }
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
    <title>Log In | CabShare</title>
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css"  href="css/login.css">
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
          <h2>Login Here</h2>
          <p>Please fill in your credentials to login.</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="field-wrap form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="username" placeholder="Email" id="username" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="field-wrap form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" placeholder="Password" id="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
                <input type="submit" id="submit" class="button button-block" value="LOGIN">
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
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