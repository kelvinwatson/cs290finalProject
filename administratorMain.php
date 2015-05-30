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
if (!($stmt = $mysqli->prepare("SELECT apptID,userName,apptDateTime,reason,doctor,approved FROM drOfficeAppts ORDER BY apptDateTime ASC"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
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
    <title>Administrator Portal (ClinicAssist)</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">\
    <link rel="stylesheet" href="css/sortable-theme-bootstrap.css">    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
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
            <li><a href="administratorMain.php">Manage Appointments</a></li>
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
          <h1>Welcome to the Administrator Portal</h1>
          <section>
            <h2>Approve or Reject Pending Appointments</h2>
            <p>As a medical office assistant, you have administrator privileges. This means you can approve or reject patient appointments.</p>
            <p><strong>NOTE: Your approval and rejection decisions will be <span style="color:#008000">shared</span> with patients who requested the appointments.</strong></p>
          </section>

          <section id="pendingAppointments">
            <h2>Appointments Pending Approval</h2>
            <?php if(empty($pendingAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> There are no pending appointments to approve or reject.
              </div>
            <?php else: ?>
            <div id="pendingTableContainer">
              <div id="pendingTableDiv" class="table-responsive">
                <table class="sortable-theme-bootstrap" data-sortable>
                  <caption>Approve or reject these patient appointment requests. (Table is sortable by date/time or healthcare practitioner)</caption>
                  <thead>
                    <tr>
                      <th data-sorted="true" data-sorted-direction="ascending">Appointment Date and Time</th>
                      <th>Healthcare Provider</th>
                      <th data-sortable="false">Reason for Appointment</th>
                      <th data-sortable="false">Approve</th>
                      <th data-sortable="false">Reject</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php foreach($pendingAppointmentsArr as $assocArray): ?> 
                      <tr>
                        <td><?php echo date("l,F j, Y, g:i A", strtotime($assocArray['apptDateTime'])); ?></td>
                        <td><?php echo $assocArray['doctor']; ?></td>
                        <td><?php echo $assocArray['reason']; ?></td>
                        <td><button id="<?php echo $assocArray['apptID']; ?>" value="approve" type="button" class="btn btn-success" onclick="return approveReject(this)">Approve</button></td>
                        <td><button id="<?php echo $assocArray['apptID']; ?>" value="reject" type="button" class="btn btn-warning" onclick="return approveReject(this)">Reject</button></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endif; ?>          
          </section>
          <section id="approvedAppointments">
            <h2>Appointments Approved by Medical Office Assistants</h2>
            <?php if(empty($approvedAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> There are no approved appointments.
              </div>
            <?php else: ?>            
              <div id="approvedTableContainer" >
                <div id="approvedTableDiv" class="table-responsive">
                  <table class="sortable-theme-bootstrap" data-sortable>
                    <caption>These appointments were approved by Medical Office Assistants. (Table is sortable by date/time or healthcare practitioner)</caption>
                    <thead>
                      <tr>
                        <th data-sorted="true" data-sorted-direction="ascending">Appointment Date and Time</th>
                        <th>Healthcare Provider</th>
                        <th data-sortable="false">Reason for Appointment</th>
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
          
          <section id="rejectedAppointments">
            <h2>Appointments Rejected by Medical Office Assistants</h2>
            <?php if(empty($rejectedAppointmentsArr)): ?>
              <div class="alert alert-info">
                <span class="glyphicon glyphicon-info-sign"></span> There are no rejected appointments.
              </div>
            <?php else: ?>
            <div id="rejectedTableContainer">
              <div id="rejectedTableDiv" class="table-responsive">
                <table class="sortable-theme-bootstrap" data-sortable>
                  <caption>These appointments were rejected by Medical Office Assistants. (Table is sortable by date/time or healthcare practitioner)</caption>
                  <thead>
                    <tr>
                      <th data-sorted="true" data-sorted-direction="ascending">Appointment Date and Time</th>
                      <th>Healthcare Provider</th>
                      <th data-sortable="false">Reason for Appointment</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <?php foreach($rejectedAppointmentsArr as $assocArray): ?> 
                      <tr>
                        <td><?php echo date("l, F j, Y, g:i A", strtotime($assocArray['apptDateTime'])); ?></td>
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
        </div>

<?php $stmt->close(); ?>
        <div class="col-md-4">
          <h2>Your Information</h2>
          <ul>
            <li>Patient: <?php echo "$_SESSION[firstName] $_SESSION[lastName]" ?></li>
            <li>You have:</li>
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
  <script src="js/administrator.js"></script>
  <script src="js/sortable.min.js"></script>
  <script language="javascript">
    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
      });
  </script>    
  </body>
</html>