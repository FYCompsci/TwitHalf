<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
  if (isset($_GET['searchType'])){
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
						<!--
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=all' id="searchType-all"><span class="fa fa-list-alt"></span> Search All</a>
					  </li>
						-->
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=user' id="searchType-user"><span class="fa fa-user"></span> Search by User</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" href='?searchType=hashtag' id="searchType-hashtag"><span class="fa fa-hashtag"></span> Search by Hashtag</a>
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
		<script>
			var searchType = "<?php echo $page_searchType; ?>";
			$("#searchType-" + searchType).addClass("active");
			function search(){
				$('#feed-container').html("");
				var query = $('#searchBar').val();
				if (searchType == "user"){
					if (query.charAt(0) == "@"){
						query = query.substring(1);
					}
				}
				else if (searchType == "hashtag"){
					if (query.charAt(0) != "#"){
						query = "#" + query;
					}
				}
				if (searchType == "user"){
					buildPosts(query, "all");
				}
				else if (searchType == "hashtag"){
					buildPosts("all", query);
				}
				$('.card').each(function() {
					$(this).html(linkHashtags($(this).html()));
					$(this).html(linkUsernames($(this).html()));
				});
			}
		</script>
  </body>
</html>
