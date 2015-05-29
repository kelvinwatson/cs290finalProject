<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: application/json');
include 'db.php';

/*CONNECT TO DB*/
$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} //else echo "connected to DB!";

/*Check to see if a username exists*/
if (!($stmt = $mysqli->prepare("SELECT userName from drOfficeUsers WHERE userName=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
} //else echo 'PREPARE to check acct info VS DB success';

if (!$stmt->bind_param("s", $_POST['userName'])) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
} //else echo 'BIND to check acct info VS DB success';

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
} //else echo "EXECUTE to check acct info VS DB success\n";

$responseUName = NULL;
if (!$stmt->bind_result($responseUName)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
} //else echo "BIND to check acct info success!!!\n"; //returns NULL if userName does not exist in DB

$stmt->store_result();
$stmt->fetch();
if($stmt->num_rows==0){
}
//var_dump($stmt->num_rows);
//var_dump($responseUName);
$stmt->close();

/*IF USERNAME NOT IN DB, ADD USER TO DB*/

$accountCreate = array();

if(is_null($responseUName)) {
  if (!($stmt = $mysqli->prepare("INSERT INTO drOfficeUsers(firstName,lastName,userName,passWord,accessLevel) VALUES (?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
  } //else echo 'prepare success!';
  
  $fN = $_POST['firstName'];
  $lN = $_POST['lastName'];
  $uN = $_POST['userName'];
  $pW = $_POST['passWord'];
  $aL = $_POST['accessLevel'];
  if (!$stmt->bind_param("ssssi", $fN,$lN,$uN,$pW,$aL)) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } //else echo 'INSERT BIND acct info success!';
  
  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  } //else echo 'INSERT executed successfully!';

  $accountCreate['success'] = TRUE;
  echo json_encode($accountCreate);
  
  $stmt->close();

} else {
  $accountCreate['success'] = FALSE;
  echo json_encode($accountCreate);
}
?>