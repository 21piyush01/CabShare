<?php
// Your database info
$db_host = 'localhost' ;
$db_user = 'root' ;
$db_pass = 'APR*1802_nd' ;
$db_name = 'cabshare' ;

if(!isset($_GET['id']))
{
  echo 'No ID was given...';
  exit;
}

$con = new mysqli($db_host, $db_user, $db_pass, $db_name);
if($con->connect_error)
{ die('Connect Error('.$con->connect_errno.')'.$con->connect_error) ; }
$sql = "DELETE FROM TravelInfo WHERE id = ?" ;
if(!$result = $con->prepare($sql))
{ die('Query failed : ('.$con->errno.' ) '.$con->error); }
if(!$result->bind_param('i', $_GET['id']))
{ die('Binding parameters failed : ( '.$result->errno.' ) '.$result->error) ; }

if(!$result->execute())
{ die('Execute failed : ( '.$result->errno.' ) '.$result->error) ; }
if($result->affected_rows > 0)
{ header("location: table.php") ; }
else
{ echo "Couldn't delete the ID." ; }
$result->close();
$con->close();