<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: application/json');
include 'db.php';
session_start();

$loginResponse = array();

/*CONNECT TO DB*/
$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("SELECT firstName,lastName,userName,passWord,accessLevel from drOfficeUsers WHERE userName=? AND passWord=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ss", $_POST['userName'], $_POST['passWord'])) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$rF=$rL=$rU=$rP=$rA = NULL;
if (!$stmt->bind_result($rF,$rL,$rU,$rP,$rA)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->store_result();
$stmt->fetch();
if($stmt->num_rows==1){ //user exists in table, store variables in session global
  $loginResponse['loggedIn'] = $_SESSION['loggedIn'] = TRUE;
  $loginResponse['accessLevel'] = $_SESSION['accessLevel'] = $rA;
  $_SESSION['firstName'] = $rF;
  $_SESSION['lastName'] = $rL;
  $_SESSION['userName'] = $rU;
  $_SESSION['passWord'] = $rP;
} else { //user does not exist in table, not logged in
  $loginResponse['loggedIn']=FALSE;
  session_unset();
  session_destroy();
}
echo json_encode($loginResponse);

$stmt->close();

?>