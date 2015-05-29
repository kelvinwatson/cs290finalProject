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
    <title>Administrator Portal (ClinicAssist)</title>
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
              <li class="active"><a href="logout.php"><span class="glyphicon glyphicon-user"></span> Log Out</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
    </nav>
    

    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <h1>Welcome to the Administrator Portal</h1>
          <!--<div class="col-sm-12" style="border:red; margin:20px" id="status">
            <div id="allMessages"></div>
          </div>-->
          <div id="intro">
            <p>Welcome <?php echo "$_SESSION[firstName] $_SESSION[lastName]" ?>. You are now logged in to ClinicAssist</p>
            <h2>What is ClinicAssist?</h2>
            <p>ClinicAssist is a web application that helps medical office assistants manage patients' doctor appointments. When
            patients request appointments, you can view, approve, or reject their appointments to your
            doctor's office.</p>
            <p><strong>As a Medical Office Assistant, you have administrator privileges.</strong> This means that you can perform the following tasks using ClinicAssist:</p>
            <ul>
              <li>view, approve and reject patients' doctor appointments</li>
              <li>order medical supplies for your doctor's office</li>
            </ul>
            <p>Use the links on the navigation bar </p>
          </div>
        </div>

        <aside class="col-md-4">
          <h2>Your Information</h2>
          <ul>
            <li>Name: <?php echo "$_SESSION[firstName] $_SESSION[lastName]" ?></li>
            <li>Occupation: Medical Office Assistant</li>
            <li>Role: Administrator</li>
            <li>Privileges:
              <ul>
                <li>Approve patient appointments</li>
                <li>Reject patient appointments</li>
                <li>Order medical supplies</li>
              </ul>
            </li>
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