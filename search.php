<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>Search | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-3">
					<form>
						<div class="input-group">
							<input type="form" class="form-control" id="searchBar" placeholder="Search by @username or #hashtag">
							<div class="input-group-addon" onclick="search()"><span class="fa fa-search"></span></div>
						</div>
					</form>
					<div class="list-group">
					  <button type="button" class="list-group-item"><span class="fa fa-user"></span> Sort by User</button>
					  <button type="button" class="list-group-item"># Sort by Hashtag</button>
					</div>
				</div>
				<div class="col-sm-9">
					<div id="feed-container"></div>
				</div>
      </div>
    </div>
    <?php include_once ('footer.php'); ?>
    <script src="js/jquery2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
