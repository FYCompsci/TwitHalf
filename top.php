<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>Top | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
      <div class="container">
			<h1 class="display-2">The Top.</h1>
			<h4>See what people buzzed about the most.</b></h4>
				<div id="feed-container">
			</div>
    </div>
    <?php include_once ('footer.php'); ?>
		<script src="js/jquery2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script src="js/buildPosts.js"></script>
		<script src="js/linkify.min.js"></script>
		<script src="js/linkify-jquery.min.js"></script>
		<script>
			var page_username = "<?php echo $_SESSION['user']['username']; ?>"; // gets username
			$( document ).ready(function() {
					buildPosts("all", "all", "likes"); // builds posts sorted on likes
					$('.card').each(function() {
							$(this).html(linkHashtags($(this).html())); // links hashtags
							$(this).html(linkUsernames($(this).html())); // links usernames
					});
					$('.card').linkify(); // links http links.
			});
		</script>
  </body>
</html>
