<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: application/json');
include 'db.php';
session_start();

$adminAction=$_POST['action'];
$appointmentID=$_POST['apptID'];

$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if($adminAction==1){
  if (!($stmt = $mysqli->prepare("UPDATE drOfficeAppts SET approved=1 WHERE apptID=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
}
else if($adminAction==2){
  if (!($stmt = $mysqli->prepare("UPDATE drOfficeAppts SET approved=2 WHERE apptID=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }  
}
if (!$stmt->bind_param("i", $appointmentID)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
  echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$statusArray = array('success'=>true);
echo json_encode($statusArray);

$stmt->close();
?>