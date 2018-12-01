<?php
    include "config.php";
    // Initialize the session
    session_start();
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php") ;
        exit ;
    }        
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cab Details | Cabshare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/taxi1.png">
<!-- Bootstrap -->    
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">
<!-- Stylesheet
    ================================================== -->
    <link rel="stylesheet" type="text/css"  href="css/table.css">
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
        <li><a href="welcome.php" class="page-scroll">Book More</a></li>
        <li><a href="calender.php">Calender</a></li>
        <li><a href="101.php#contact-section" class="page-scroll">Contact Us</a></li>
        <li><a href="reset-password.php" class="page-scroll">Reset Password</a></li>
        <li><a href="logout.php" class="page-scroll">Log Out</a></li>  
      </ul>
    </div>
  </div>
  </nav>
<!-- Header -->
  	<div class="intro-text">

  	</div>
<!-- Table -->  		
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table101">
					<table>
						<thead>
					      <tr class="table101-head">
					        <th class="column1">   NAME                </th>
				    	    <th class="column2">   PHONE               </th>
                  <th class="column3">   CAB ROUTE           </th>
				          <th class="column4">   FLIGHT/TRAIN NO.    </th>
                  <th class="column4">   DATE                </th>
                  <th class="column6">   DEPARTURE TIME      </th>
                  <th class="column7">   CAB ARRIVAL TIME    </th>
                  <th class="column8">   DELETE BOOKING       </th>
 					        </tr>
      					</thead>
      					<tbody>  
					      <?php 
					        // FINDING USER ROUTE AND DATE
					        $me = $_SESSION["username"];
					        $q1 = "SELECT * FROM TravelInfo WHERE username = '$me' ORDER BY tdate, ttime ";
					        $r1 = $link->query($q1);
					      ?>	
						  <?php  
					    	// PRINTING USER BOOKINGS
					      	$sql2 = "SELECT * FROM TravelInfo WHERE username = '$me' ORDER BY tdate, ttime ";
        					$result2 = $link->query($sql2);
        					if($result2->num_rows > 0) 
        					{
          					  while($row2 = $result2->fetch_assoc()) 
          						{ ?>
    					        	<tr class="table101-row">
	    					          <td class="column1"> <?php echo $row2["username"] ; ?>  </td>
    						          <td class="column2"> <?php echo $row2["phone"] ; ?>     </td>
                          <td class="column3"> <?php echo $row2["troute"] ; ?>    </td>
    						          <td class="column4"> <?php echo $row2["vno"] ; ?>       </td>
                          <td class="column5"> <?php echo $row2["tdate"] ; ?>     </td>
                          <td class="column6"> <?php echo $row2["dtime"] ; ?>     </td>
                          <td class="column7"> <?php echo $row2["ttime"] ; ?>     </td>
                          <td class="column8"> <a href="delete.php?id= <?php echo $row2['id']; ?> "> Delete </a> </td>
    						        </tr> 
    					    	<?php } 
    					    } 
    					    else 
    					    { 
    					      ?>
    					      <div class="starter">
						        <p> <?php echo "You have no bookings at present." ; ?>  </p>
							  </div>  
							  <?php 
    					  	}
    					  ?>
    					</tbody>   
      				</table>
      				<br> <br>
 					<!-- PRINTING SIMILAR TRAVELLERS -->
 			<?php
 			  	if($r1->num_rows > 0) 
        		{
          			// output data of each row
          			while($ans = $r1->fetch_assoc()) 
          			{ 
          				$given_route = $ans["troute"] ;
          				$given_date = $ans["tdate"] ;
          			  ?>
    					    	
      				<table>
						<thead>
					      <tr class="table100-head">
					        <th class="column1">   NAME                </th>
                  <th class="column2">   PHONE               </th>
                  <th class="column3">   CAB ROUTE           </th>
                  <th class="column4">   FLIGHT/TRAIN NO.    </th>
                  <th class="column4">   DATE                </th>
                  <th class="column6">   DEPARTURE TIME      </th>
                  <th class="column7">   CAB ARRIVAL TIME    </th>
                  </tr>
                </thead>
      					<tbody>  
					      <?php 
					        $sql = "SELECT * FROM TravelInfo WHERE username != '$me' AND troute = '$given_route' AND tdate = '$given_date' ORDER BY ttime ";
        					$result = $link->query($sql);
        					if ($result->num_rows > 0) 
        					{
          					  ?>
    					      <div class="starter">
						        <p> <?php echo $result->num_rows." results found for ".$given_route." on ".$given_date ; ?>  </p>
							  </div>  
							  <?php
          						// output data of each row
          						while($row = $result->fetch_assoc()) 
          						{ ?>
    					        	<tr class="table100-row">
	    					          <td class="column1"> <?php echo $row["username"] ; ?>  </td>
                          <td class="column2"> <?php echo $row["phone"] ; ?>     </td>
                          <td class="column3"> <?php echo $row["troute"] ; ?>    </td>
                          <td class="column4"> <?php echo $row["vno"] ; ?>       </td>
                          <td class="column5"> <?php echo $row["tdate"] ; ?>     </td>
                          <td class="column6"> <?php echo $row["dtime"] ; ?>     </td>
                          <td class="column7"> <?php echo $row["ttime"] ; ?>     </td>
                        </tr> 
    					    	<?php } 
    					    } 
    					    else 
    					    {
    					      ?>
    					      <div class="starter">
						        <p> <?php echo "0 results found for ".$given_route." on ".$given_date ; ?>  </p>
							  </div>  
							  <?php 
    					  	}
    					  ?>
    					</tbody>   
      				</table>
      				<br> <br>
      				
      				<?php } 
    			} 
    		?>
      			</div>
      		</div>		
      	</div>
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