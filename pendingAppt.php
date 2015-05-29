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
}
if (!($stmt = $mysqli->prepare("SELECT apptDateTime from drOfficeAppts WHERE apptDateTime=? AND doctor=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
} 
if (!$stmt->bind_param("ss", $appointmentDateTime, $_POST['doctor'])) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->store_result();
$stmt->fetch();
$pendingAppointments = array();

if($stmt->num_rows==0){ //appointment does not exist, insert it!
  if (!($stmt = $mysqli->prepare("INSERT INTO drOfficeAppts(userName,apptDateTime,reason,doctor) VALUES (?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  $aUser = $_SESSION['userName'];
  $aDate = $appointmentDateTime;
  $aReason = $_POST['reason'];
  $aDoctor = $_POST['doctor'];
  if (!$stmt->bind_param("ssss", $aUser,$aDate,$aReason,$aDoctor)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $pendingAppointments['success'] = TRUE;
  echo json_encode($pendingAppointments);
  
  $stmt->close();

} else { //appointment already in table, do not insert
  $pendingAppointments['success'] = FALSE;
  echo json_encode($pendingAppointments);
}

?>