<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
session_start();


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
     <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">ClinicAssist Portal</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.html">Home</a></li>
              <li><a href="#about">Request Appointments</a></li>
              <li><a href="#contact">Help</a></li>
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
          <section id="approvedAppointments">
            <h2>Your Approved Appointments</h2>
            <div id="approvalAlert" role="alert">
              <?php if(isset($_SESSION['haveApprovedAppts']) && $_SESSION['haveApprovedAppts']==true):?>
              <!--<table>
              </table>-->
              <?php else: ?>
                <div class="alert alert-info">
                  <span class="glyphicon glyphicon-info-sign"></span> You do not have any approved appointments.
                </div>
                <div class="alert alert-info">
                  <span class="glyphicon glyphicon-info-sign"></span> Note: all requested appointments must be approved by a medical office assistant. Please check back again later.
                </div>
              <?php endif ?>
            </div>
          </section>
          <section id="pendingAppointments">
            <h2>Your Pending Appointments</h2>
            <div id="pendingAlert" role="alert">
              <?php if(isset($_SESSION['havePendingAppts']) && $_SESSION['havePendingAppts']==true):?>
                <p>These appointments are still pending approval by a medical office assistant. Please check back later.</p>
              <!--<table>
              </table>-->
              <?php else: ?>
                <div class="alert alert-info">
                  <span class="glyphicon glyphicon-info-sign"></span> You do not have any pending appointments.
                </div>
                <?php echo '***TEST: PRINTING SESSION ARRAY: '; var_dump($_SESSION);?>
              <?php endif ?>
            </div>
          </section>
          <section id="request">
            <h2>Request an Appointment</h2>
      
            <p>Please note that your appointments will not appear until they have been approved by a medical office assistant.</p>
          <section>
            <div id="status">
              <div id="allMessages"></div>
            </div>
          </section>
        
            <div id="reqFormContainer">  
              <form class="form-horizontal" action="#" method="post" onSubmit="return submitForm()" role="form">
                <fieldset>
                  <legend>Request an Appointment Time</legend>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="aDate">Date</label>
                    <div class="col-sm-6"><input name="apptDate" class="form-control" id="aDate" type="text" placeholder="mm/dd/yyyy" required></div>
                    <div class="col-sm-4">(mm/dd/yyyy)</div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="aTime">Time</label>
                    <div class="col-sm-6"><input name="apptTime" class="form-control" id="aTime" type="text" placeholder="--:--" required></div>
                    <div class="col-sm-4">(e.g. 8:05am or 14:15)</div>
                  </div>
                </fieldset>
                <fieldset>
                  <legend>Which healthcare provider do you need an appointment with?</legend>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="aDoctor">Healthcare professional</label>
                    <div class="col-sm-6">
                      <select name="apptDoctor" class="form-control" id="aDoctor" required>
                        <option selected="selected" disabled="disabled" value="">Please select a provider</option>
                        <option value="kBrewster">Brewster, Kate NP (Nurse Practitioner)</option>
                        <option value="jConnor">Connor, John MD (Family Practice Physician)</option>
                        <option value="sConnor">Connor, Sarah RD (Registered Dietician) </option>
                        <option value="kReese">Reese, Kyle DC (Chiropractic Doctor)</option>
                      </select>
                    </div>
                    <div class="col-sm-4"></div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="aReason">Reason for appointment</label>
                    <div class="col-sm-6"><textarea name="apptReason" class="form-control" name="reason" rows="5" id="aReason" required></textarea></div>
                    <div class="col-sm-4"></div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="createUser"></label>
                    <div class="col-sm-6 controls"><input type="Submit" id="submitReq" name="submitReq" class="btn btn-primary"></div>
                    <div class="col-sm-4">
                    </div>
                  </div>
                </fieldset>
              </form>
            </div><!--form container-->
          </section>

        </div>

        <div class="col-md-4">
          <h2>Your Information</h2>
          <ul>
            <li>Patient: <?php echo "$_SESSION[firstName] $_SESSION[lastName]" ?></li>
            <li>Age: 32</li>
          </ul>
          <p><a href="#" class="btn btn-info">More &raquo;</a></p>
        </div>    
      </div><!--row-->


      
  </div> <!--container-->
      
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/patient.js"></script>
  <script language="javascript">
    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
      });
  </script>    
  </body>
</html>