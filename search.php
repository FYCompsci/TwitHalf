<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
  if (isset($_GET['searchType'])){
		// gets search type
    $page_searchType = $_GET['searchType'];
  }
  else{
  	$page_searchType = "user";
  }
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
        <div class="col-sm-4">
					<div class="row">
						<div class="col-sm-9">
							<input type="text" class="form-control" id="searchBar" placeholder="Search by @username or #hashtag">
						</div>
						<div class="col-sm-3">
							<button class="btn btn-block btn-warning" onclick="search()" style="text-align:center;"><span class="fa fa-search"></span></button>
						</div>
					</div>
					</br>
					<ul class="nav nav-pills nav-stacked">
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=users' id="searchType-users"><span class="fa fa-users"></span> Search Users</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=user' id="searchType-user"><span class="fa fa-user"></span> Search Buzzes by User</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=hashtag' id="searchType-hashtag"><span class="fa fa-hashtag"></span> Search Buzzes by Hashtag</a>
					  </li>
					</ul>
				</div>
				<div class="col-sm-8">
					<div id="feed-container">
						<h1>You haven't searched anything yet.</h1>
						<h3>You can search by @username or by #hashtag</h3>
					</div>
				</div>
      </div>
    </div>
    <?php include_once ('footer.php'); ?>
    <script src="js/jquery2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/buildPosts.js"></script>
		<script src="js/buildUsers.js"></script>
		<script src="js/linkify.min.js"></script>
		<script src="js/linkify-jquery.min.js"></script>
		<script>
      var page_username = "<?php echo $_SESSION['user']['username']; ?>"; // gets current user's username
			var searchType = "<?php echo $page_searchType; ?>"; // gets pages search type
			var userInfoData = JSON.parse(httpGet("info.php?user=" + page_username)); // gets data about the current user
			$("#searchType-" + searchType).addClass("active"); // renders active component on the correct navbar thing
			function search(){
				$('#feed-container').html(""); // resets feed container
				var query = $('#searchBar').val(); // gets what was put in the search bar
				if (searchType == "user" || searchType == "users"){
					// correctly formats usernames
					if (query.charAt(0) == "@"){
						query = query.substring(1);
					}
				}
				else if (searchType == "hashtag"){
					// correctly formats hashtags
					if (query.charAt(0) != "#"){
						query = "#" + query;
					}
				}
				// this next part just builds the cards
				if (searchType == "users"){
					buildUsers(query);
				}
				else if (searchType == "user"){
					buildPosts(query, "all");
				}
				else if (searchType == "hashtag"){
					buildPosts("all", query);
				}
				$('.card').each(function() {
					$(this).html(linkHashtags($(this).html())); // links hashtags
					$(this).html(linkUsernames($(this).html())); // links usernames
				});
				$('.card').linkify(); // links 
			}
		</script>
  </body>
</html>
