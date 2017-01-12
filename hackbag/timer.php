<?php

include '../parse.php';
use Parse\ParseUser;
use Parse\ParseQuery;

include 'funcs.php';

if (isset($_GET['action']) && $_GET['action']) {
  $action = $_GET['action'];
  if ($action == "activate") {
    $currentUser = ParseUser::getCurrentUser();
    activateTransaction();
  } else if ($action == 'complete') {
    $currentUser = ParseUser::getCurrentUser();
    deactivateTransaction();
    header('Location: http://' . URL . 'hackbag/');
  }
}

$user = ParseUser::getCurrentUser();
$type = getUserType($user);
if ($user->get('ownsBag')) {
  // LENDER
  $trans = $user->get('currentTransaction');
  $trans->fetch();
  $start = $trans->get('startTime');
  $end = $trans->get('endTime');
  $otherUser = $trans->get('borrower');
  $otherUser->fetch();
  $name = $otherUser->get('fullName');

  $msg = "Success! You've made a hacker's night, thank you! Return to receive your bag at $end.";

  $action = "Lending to";

  $acceptMsg = 'Mark Bag As Received';
} else {
  // BORROWER
  $trans = $user->get('currentTransaction');
  $trans->fetch();
  $start = $trans->get('startTime');
  $end = $trans->get('endTime');
  $otherUser = $trans->get('lender');
  $otherUser->fetch();
  $name = $otherUser->get('fullName');

  $msg = "Success! Enjoy your HackBag, and be sure to return at $end.";

  $action = "Borrowing from";

  $acceptMsg = 'Mark Bag As Returned';

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> HackBag - Hack and Sleep Responsibly </title>

    <!-- Bootstrap -->
    <link href="https://bootswatch.com/cosmo/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Style -->
    <link href="style.css" rel="stylesheet" type="text/css" />



    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="font-size:14px">
    <div class="container">

       <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" style="font-size:30px; margin-top:5px">HackBag</a>
        </div>


        <p class="navbar-text navbar-right" style="margin-top:5px; margin-bottom:5px"> <img src="http://st2.depositphotos.com/2571355/7122/v/110/depositphotos_71222099-Sleeping-bag-flat-square-icon-with-long-shadows..jpg" class="img-rounded" alt="Cinque Terre" style="border-radius:50%; width: 50px; height:50px; margin-right: 10px"> </p>




      </div><!-- /.container-fluid -->
    </nav>


      <div class="row">
        <div class="pad-top-lg col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
          <div class="header text-center">
            <div class="profile-content">


              <h1 class="name hidden-xs"> <img src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h1>
              <h2 class="name visible-xs"> <img width="50px" height="50px" src="https://s3.amazonaws.com/assets.mlh.io/events/logos/000/000/136/thumb/0_mlh_citrushacks_logo.png?1441815149"> Citrus Hack </h2>


                <h5> <?php echo $action . ' ' . $name . '.'; ?>  </h5>
                <h3 id="successMsg" class="desc pad-top-sm"> <?php echo $msg; ?> </h3>

                <!--
                <h3 id="cnt-dwn-title" class="desc pad-top-sm" style="font-weight: 700"> Time until <?php //echo $endTime; ?> </h3>
                <h4 id="cnt-dwn">
                  <span id ="hoursBox" class="timeBox"></span>
                  <span>:</span>
                  <span id ="minsBox" class="timeBox"></span>
                  <span>:</span>
                  <span id ="secsBox" class="timeBox"></span>
                  <p> <br> </p>
                </h4>
              -->
                  <div class="row">
                    <div class="col-md-6">
                      <h5 class="text-left">Request time: </h5>
                     <h5 class="text-left">End time:</h5>
                    </div>
                    <div class="col-md-6" style="font-weight: 0;">
                      <h5 id="start"> <?php echo $start ?></h5>
                      <h5 id="end"> <?php echo $end ?></h5>
                    </div>
                  </div>

                  <button id="accept" type="button" class="btn btn-primary btn-lg btn-block"><?php echo $acceptMsg; ?></button>

                  <?php
                    if ($action == 'Borrowing from') {
                      echo ' <button id="accept" type="button" class="btn btn-success btn-lg btn-block" style="margin-top:15px"><i class="fa fa-cc-visa" style="margin-right:5px"></i> Kudos $1 to ' . $name . '</button>';
                    }
                  ?>

          </div>
        </div>
      </div>


    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>







    <script type="text/javascript">
  function myFunction(startTime, duration)
  {
    var currentTime = new Date().getTime() / 1000;// get current time in seconds
    var endTime = (startTime / 1000) + (duration * 60 * 60); // Sin seconds

    var secondsUntilEnd = endTime - currentTime; // in seconds

    console.log(secondsUntilEnd);

    if (secondsUntilEnd <= 0)
    {
      $('#cnt-dwn-title').fadeOut();
      $('#cnt-dwn').fadeOut();
      //document.getElementById("status").innerHTML = "already finished!";
      return;
    }
    else
    {

      var hours = Math.floor(secondsUntilEnd / 3600);
      var mins = Math.floor((secondsUntilEnd/60) - (hours*60));
      var temp = (hours * 3600) + (mins * 60);
      var secs = Math.floor(secondsUntilEnd - temp);

      function pad(d) {
            return (d < 10) ? '0' + d.toString() : d.toString();
      }


      document.getElementById("hoursBox").innerHTML = pad(hours);
      document.getElementById("minsBox").innerHTML = pad(mins);
      document.getElementById("secsBox").innerHTML = pad(secs);
    }
  }

  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.js"></script>





<script type="text/javascript">
var startTime = new Date().getTime() - 3600*1000; // 1 hours ago in ms
var startH = startTime / 3600000;
var startM = (startTime / 60000) - (startH * 60);

var duration = 1.002; // in hour
var endTime = (startTime / 1000) + (duration * 3600);
var endH = endTime / 3600;
var endM = startH;
setInterval(function() {
  myFunction(startTime, duration);
  }, 1000);

var start = moment(startTime).format("hh:mm:ss a");
var end = moment(endTime*1000).format("hh:mm:ss a"); // in ms

//document.getElementById("start").innerHTML = start;
//document.getElementById("end").innerHTML = end;

if(endTime < (5*1000*60))
{
  $('#cnt-dwn').fadeOut();
}


$(document).ready(function() {
      $('#accept').on('click', function(e) {
          e.preventDefault();
          window.location.href = 'timer.php?action=complete';
      });
    });
</script>
  </body>
</html>
