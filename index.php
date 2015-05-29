<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
session_start();
if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
  if($_SESSION['accessLevel']==0){
    header('Location: http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/patientMain.php');
  } else if($_SESSION['accessLevel']==1){
    header('Location: http://web.engr.oregonstate.edu/~watsokel/cs290/finalProject/administratorMain.php');
  }
} 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ClinicAssist (CS290)</title>
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
            <a class="navbar-brand" href="index.php">ClinicAssist Portal</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="#about">What is ClinicAssist?</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="index.php"><span class="glyphicon glyphicon-user"></span> Log in</a></li>
            </ul>
          </div>
        </div>
    </nav>
  
    <div class="container"> 
      <div class="row">
        <div class="col-md-8 jumbotron">
          <h1 class="">Welcome to ClinicAssist</h1>
          <h3 class="">Your online doctor's office appointment solution.</h3> 
          <p><a href="#" class="btn btn-default">Learn More &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <div id="loginForm">
            <form action="#" method="post" onSubmit="return submitForm()">
              <fieldset>
                <legend>Login</legend>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>
                      </span>
                      <input id="loginUser" type="text" class="form-control form-group" placeholder="User Name" name="userName" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <span class="glyphicon glyphicon-lock"></span>
                      </span>
                      <input id="loginPW" type="password" class="form-control" placeholder="Password" name="pass" required>
                    </div> 
                  </div>
                <input type="submit" class="btn btn-success" value="Login">
                <a href="createAccount.html" class="btn btn-info">Create Account</a>
              </fieldset>
            </form>
          </div>
          <div id="loginStatus">
            <br>
            <div id="loginMessages"></div>
          </div>    
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h3>Medical Office Assistants</h3>
            <p>You will be granted administrator privileges, which allows you to accomplish the following:</p>
            <ul>
              <li>Approve patient appointments</li>
              <li>Keep patient files organized</li>
            </ul>
          <p>Ready?</p>              
          <p><a href="#" class="btn btn-info">Create an account today! &raquo;</a></p>
        </div>
        <div class="col-md-6">
            <h3>Patients</h3>
            <p>Request doctor's appointments and view your scheduled appointments.</p>
            <p><a href="#" class="btn btn-info">More &raquo;</a></p>
        </div>
      </div>
 
  </div>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/login.js"></script>  
  <script language="javascript">
    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
      });
  </script>    
  </body>
</html>