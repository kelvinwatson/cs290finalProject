<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: application/json');
include 'db.php';
session_start();

$appointmentDateTime = date("Y-m-d H:i:s",strtotime("$_POST[date] $_POST[time]"));

$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else echo 'connected!';
if (!($stmt = $mysqli->prepare("SELECT userName,apptDateTime from drOfficeAppts WHERE userName=? AND apptDateTime=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
} else echo ' and prepped!!';
if (!$stmt->bind_param("ss", $_SESSION['userName'], $appointmentDateTime)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
} echo ' and bound!';
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->store_result();
$stmt->fetch();
var_dump($stmt->num_rows);
$pendingAppointments = array();

if($stmt->num_rows==0){ //appointment does not exist, insert it!
  if (!($stmt = $mysqli->prepare("INSERT INTO drOfficeAppts(userName,apptDateTime,reason,doctor) VALUES (?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  } else echo 'prepare success!';
  
  $aUser = $_SESSION['userName'];
  $aDate = $appointmentDateTime;
  $aReason = $_POST['reason'];
  $aDoctor = $_POST['doctor'];
  if (!$stmt->bind_param("ssss", $aUser,$aDate,$aReason,$aDoctor)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } else echo 'INSERT BIND acct info success!';
  
  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } else echo 'INSERT executed successfully!';

  $pendingAppointments['success'] = TRUE;
  echo json_encode($pendingAppointments);
  
  $stmt->close();

} else { //appointment already in table, do not insert
  $pendingAppointments['success'] = FALSE;
  echo json_encode($pendingAppointments);
}

?>