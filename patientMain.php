<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
session_start();
//call the DB and get the info based on session user name
$mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'watsokel-db', $dbpass, 'watsokel-db');
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("SELECT apptDateTime,reason,doctor,approved,rejectReason from drOfficeAppts WHERE userName=?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("s", $_SESSION['userName'])) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$res = $stmt->get_result();
$pendingAppointmentsArr = array();
$approvedAppointmentsArr = array();
$rejectedAppointmentsArr = array(); 
while($eachRow = $res->fetch_assoc()){
  if($eachRow['approved']==0) $pendingAppointmentsArr[] = $eachRow; //eachRow is an assoc array, each gets appended to indexed array
  elseif($eachRow['approved']==1) $approvedAppointmentsArr[] = $eachRow;
  elseif($eachRow['approved']==2) $rejectedAppointmentsArr[] = $eachRow;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Portal (ClinicAssist)</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">ClinicAssist Portal</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a style="color:#00ffff" href="requestAppointmentForm.php">Request Appointments</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="logout.php"><span class="glyphicon glyphicon-user"></span> Log Out</a></li>
            </ul>
          </div>
        </div>
    </nav>
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <h1>Welcome to the Patient Portal</h1>
          <section>
            <h2>Request an Appointment</h2>
            <p>Click <a href="requestAppointmentForm.php" alt="link to request form" ><strong>here</strong></a> to request an appointment with one of our caring and knowledgeable healthcare providers.</p>
            <p><strong>NOTE: Your information will be <span style="color:#008000">shared</span> with the Medical Office Assistant when you submit an appointment request.</strong></p>
          </section>

          <section id="approvedAppointments">
            <h2>Your Approved Appointments</h2>
            <?php if(empty($approvedAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> You do not have any approved appointments.
              </div>
            <?php else: ?>            
              <div id="approvedTableContainer" >
                <div id="approvedTableDiv" class="table-responsive">
                  <table class="table">
                    <caption>Appointments approved by the Medical Office Assistant will show below</caption>
                    <thead>
                      <tr>
                        <th>Appointment Date and Time</th>
                        <th>Healthcare Provider</th>
                        <th>Reason for Appointment</th>
                      </tr>
                    </thead>
                    <tbody> 
                      <?php foreach($approvedAppointmentsArr as $assocArray): ?> 
                        <tr>
                          <td><?php echo date("l,F j, Y, g:i A", strtotime($assocArray['apptDateTime'])); ?></td>
                          <td><?php echo $assocArray['doctor']; ?></td>
                          <td><?php echo $assocArray['reason']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>
          </section>
          <section id="pendingAppointments">
            <h2>Your Pending Appointments</h2>
            <?php if(empty($pendingAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> You do not have any pending appointments.
              </div>
            <?php else: ?>
            <div id="pendingTableContainer">
              <div id="pendingTableDiv" class="table-responsive">
                <table class="table">
                  <caption>These appointments are still pending approval by a Medical Office Assistant. Please check back later.</caption>
                  <thead>
                    <tr>
                      <th>Appointment Date and Time</th>
                      <th>Healthcare Provider</th>
                      <th>Reason for Appointment</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php foreach($pendingAppointmentsArr as $assocArray): ?> 
                      <tr>
                        <td><?php echo date("l,F j, Y, g:i A", strtotime($assocArray['apptDateTime'])); ?></td>
                        <td><?php echo $assocArray['doctor']; ?></td>
                        <td><?php echo $assocArray['reason']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endif; ?>          
          </section>

          <section id="rejectedAppointments">
            <h2>Your Rejected Appointments</h2>
            <?php if(empty($rejectedAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> You do not have any rejected appointments.
              </div>
            <?php else: ?>
            <div id="rejectedTableContainer">
              <div id="rejectedTableDiv" class="table-responsive">
                <table class="table">
                  <caption>These appointments were rejected by the Medical Office Assistant. See the reasons below:</caption>
                  <thead>
                    <tr>
                      <th>Appointment Date and Time</th>
                      <th>Healthcare Provider</th>
                      <th>Reason for Appointment</th>
                      <th>Reason for Rejection</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php foreach($rejectedAppointmentsArr as $assocArray): ?> 
                      <tr>
                        <td><?php echo date("l,F j, Y, g:i A", strtotime($assocArray['apptDateTime'])); ?></td>
                        <td><?php echo $assocArray['doctor']; ?></td>
                        <td><?php echo $assocArray['reason']; ?></td>
                        <td><?php echo $assocArray['rejectReason']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endif; ?>          
          </section>
        </div>

<?php $stmt->close(); ?>

        <div class="col-md-4">
          <h2>Your Information</h2>
          <ul>
            <li>Patient: <?php echo "$_SESSION[firstName] $_SESSION[lastName]" ?></li>
            <li>You currently have:</li>
            <ul>
                <?php if(!empty($approvedAppointmentsArr)): ?>
                  <li style="color:#008000">approved appointments (<?php $_SESSION['numApproved']=count($approvedAppointmentsArr); echo $_SESSION['numApproved']; ?>)</li>
                <?php endif; ?>
                <?php if(!empty($pendingAppointmentsArr)): ?>
                  <li style="color:#6495ED">pending appointments (<?php $_SESSION['numPending']=count($pendingAppointmentsArr); echo $_SESSION['numPending']; ?>)</li>
                <?php endif; ?>  
                <?php if(!empty($rejectedAppointmentsArr)): ?>
                  <li style="color:red">rejected appointments (<?php $_SESSION['numRejected']=count($rejectedAppointmentsArr); echo $_SESSION['numRejected']; ?>)</li>
                <?php endif; ?>                  
            </ul>
          </ul>
        </div>    
      </div>
  </div>
      
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!--<script src="js/allAppointments.js"></script>-->
  <script language="javascript">
    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
      });
  </script>    
  </body>
</html>