<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: application/json');
include 'db.php';

/*CONNECT TO DB*/
$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
/*Check if a username exists*/
if (!($stmt = $mysqli->prepare("SELECT userName from drOfficeUsers WHERE userName=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("s", $_POST['userName'])) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$responseUName = NULL;
if (!$stmt->bind_result($responseUName)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->store_result();
$stmt->fetch();
$stmt->close();

/*IF USERNAME NOT IN DB, ADD USER TO DB*/
$accountCreate = array();

if(is_null($responseUName)) {
  if (!($stmt = $mysqli->prepare("INSERT INTO drOfficeUsers(firstName,lastName,userName,passWord,accessLevel) VALUES (?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  $fN = $_POST['firstName'];
  $lN = $_POST['lastName'];
  $uN = $_POST['userName'];
  $pW = $_POST['passWord'];
  $aL = $_POST['accessLevel'];
  if (!$stmt->bind_param("ssssi", $fN,$lN,$uN,$pW,$aL)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }
  $accountCreate['success'] = TRUE;
  echo json_encode($accountCreate);
  $stmt->close();
} else {
  $accountCreate['success'] = FALSE;
  echo json_encode($accountCreate);
}
?>