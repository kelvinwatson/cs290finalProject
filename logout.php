<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
header('Content-Type: text/html; charset=utf-8');
include 'db.php';
session_start();
session_unset();
session_destroy();
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
              <li><a href="#about">Manage Appointments</a></li>
              <li><a href="#contact">Order Supplies</a></li>
              <li><a href="#contact">Help</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="index.php"><span class="glyphicon glyphicon-user"></span> Log in</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </nav>
    

    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <h1>Logged Out</h1>
          <div id="loggedOutMessage">
            <p>You are now logged out. Thank you for using ClinicAssist.</p>
          </div>
        </div>

        <aside class="col-md-4">
          <h2>Features of ClinicAssist</h2>
          <ul>
            <li>Patients</li>
            <li>Medical Office Assistants</li>
          </ul>
          <p><a href="#" class="btn btn-info">More &raquo;</a></p>
        </aside>
      </div>
    </div>


      
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/create.js"></script>
  <script language="javascript">
    $('.dropdown-toggle').dropdown();
    $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
      });
  </script>    
  </body>
</html>