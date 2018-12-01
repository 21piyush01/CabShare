<?php
// Set your timezone
date_default_timezone_set('Asia/Tokyo');
// Get prev & next month
if(isset($_GET['ym'])) 
{ $ym = $_GET['ym'] ; } 
else 
{
    // This month
    $ym = date('Y-m');
}
// Check format
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) 
{
    $ym = date('Y-m') ;
    $timestamp = strtotime($ym . '-01') ;
}
// Today
$today = date('Y-m-j', time()) ;
// For H3 title
$html_title = date(' Y / m ', $timestamp) ;
// Create prev & next month link     mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp))) ;
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp))) ;
// You can also use strtotime!
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));
// Number of days in the month
$day_count = date('t', $timestamp) ;
// 0:Sun 1:Mon 2:Tue ...
$str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp))) ;
//$str = date('w', $timestamp);
// Create Calendar!!
$weeks = array() ;
$week = '' ;
// Add empty cell
$week .= str_repeat('<td></td>', $str) ;
for($day = 1; $day <= $day_count; $day++, $str++) 
{     
    $date = $ym.'-'.$day ;
    
    if($today == $date) 
    { $week .= '<td class="today"><strong>' . $day ; }  
    else 
    { $week .= '<td><strong>' . $day ; }
    
    include "config.php" ;
    $data = array() ;
    $sql = "SELECT * FROM TravelInfo WHERE tdate='$date' ORDER BY ttime " ;
    $result = $link->query($sql);
                            
    if($result->num_rows > 0) 
    {
        while($row = $result->fetch_assoc()) 
        { 
            switch($row["troute"]) 
            {
                case "Clg to Airport":
                    $nids = "R1" ;
                    break;
                case "Clg to Danapur":
                    $nids = "R2" ;
                    break;
                case "Clg to Patna":
                    $nids = "R3" ;
                    break;
                case "Patna to Clg":
                    $nids = "R4" ;
                    break;
                case "Danapur to Clg":
                    $nids = "R5" ;
                    break;
                case "Airport to Clg":
                    $nids = "R6" ;
                    break;        
            }
            $week .= "</strong><br><i>".$nids." : ".$row["ttime"]." : ".$row["username"]." : ".$row["phone"]."</i>" ; 
        }
    }
    

    $week .= '</td>' ;
    // End of the week OR End of the month
    if($str%7 == 6 || $day == $day_count) 
    {
        if($day == $day_count) 
        {
            // Add empty cell
            $week .= str_repeat('<td></td>', 6 - ($str % 7));
        }
        $weeks[] = '<tr>' . $week . '</tr>' ;
        // Prepare for new week
        $week = '' ;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Calendar | CabShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/calender.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800,600,300' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="js/modernizr.custom.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
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
        <li><a href="login.php" class="page-scroll">Log In</a></li>  
        <li><a href="101.php#contact-section" class="page-scroll">Contact Us</a></li>
      </ul>
    </div>
  </div>
  </nav>
<!-- Header -->
    <div class="starter">
        <p> Look for when others are travelling. </p>
        <p> <strong> FORMAT => </strong> Route Index : Travel Time : Name : phone </p>
        <center>
          <table class="simple">
            <tr>
                <th>Index</th>
                <th>Route</th>
            </tr>
            <tr>
                <td>Clg to Airport</td>
                <td>R1</td>  
            </tr>
            <tr>
                <td>Clg to Danapur</td>
                <td>R2</td>  
            </tr>    
            <tr>
                <td>Clg to Patna</td>
                <td>R3</td>  
            </tr>
            <tr>
                <td>Patna to Clg</td>
                <td>R4</td>  
            </tr>
            <tr>
                <td>Danapur to Clg</td>
                <td>R5</td>  
            </tr>
            <tr>
                <td>Airport to Clg</td>
                <td>R6</td>  
            </tr>
          </table>
        </center> 
        <br> 
        <p> Hope it helps !! </p>
        
    </div>                  
    <div class="container cal">
        <h3><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
        <table class="table table-bordered">
            <tr>
                <th>S</th>
                <th>M</th>
                <th>T</th>
                <th>W</th>
                <th>T</th>
                <th>F</th>
                <th>S</th>
            </tr>
            <?php
                foreach($weeks as $week) 
                { echo $week ; }
            ?>
        </table>
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
