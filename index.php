<?php
	require("common.php");
	if(!(empty($_SESSION['user']))){
		header("Location: home.php");
		die("Redirecting to home.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>The Buzz</title>
		<link href="favicon.ico" rel="icon" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-7">
					<h1>welcome to:</h1>
          <h1 class="display-1"><b>The Buzz.</b></h1>
          <h4>an open source social network designed for coders, designers, bugfixers, and everything in between</h4>
					<div class="row">
						<div class="col-sm-4">
							<a class="btn btn-secondary btn-block" href="about.php"><span class="fa fa-book"></span> About The Buzz</a>
						</div>
						<div class="col-sm-4">
							<a class="btn btn-secondary btn-block" href="team.php"><span class="fa fa-users"></span> About the Team</a>
						</div>
						<div class="col-sm-4">
							<a class="btn btn-secondary btn-block" href="https://github.com/FYCompsci/TwitHalf"><span class="fa fa-github"></span> View on GitHub</a>
						</div>
					</div>
        </div>
        <div class="col-sm-5">
          <h2><span class="fa fa-user-plus"></span> Register</h2>
          <form action="register.php" method="post">
            Username: <input class="form-control" type="text" name="username" value="" id='reg-username' required=""/>
						</br>
            Email: <input class="form-control" type="text" name="email" value="" required=""/>
						</br>
            Password: <input class="form-control" type="password" name="password" value="" required=""/>
						</br>
            <input class="btn btn-primary-outline" type="submit" value="Register" />
          </form>
					<h3>Already have an account? <a href="login_page.php">Log in.</a></h3>
        </div>
      </div>
    </div>
		<?php include_once ('footer.php'); ?>
		<script src="js/jquery2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
		// a not that secure way of only allowing alphanumeric in username input
		$(function(){
			$("#reg-username").keypress(function(event){
					var ew = event.which;
					if(48 <= ew && ew <= 57)
							return true;
					if(65 <= ew && ew <= 90)
							return true;
					if(97 <= ew && ew <= 122)
							return true;
					return false;
			});
		});
		</script>
  </body>
</html>
