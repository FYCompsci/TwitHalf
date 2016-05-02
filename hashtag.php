<?php
session_start();
if (isset($_GET['hashtag'])){
  $page_hashtag = $_GET['hashtag'];
}
else{
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
		<title><?php echo $page_hashtag; ?> | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
    <div class="container">
			<h1 class="display-2">#<?php echo $page_hashtag; ?></h1>
			<h4>What are people saying about <b>#<?php echo $page_hashtag; ?></b></h4>
				<div id="feed-container">
			  </div>
    </div>
    <?php include_once ('footer.php'); ?>
    <script src="js/jquery2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/buildPosts.js"></script>
		<script>
			$( document ).ready(function() {
					buildPosts("all", "#<?php echo $page_hashtag; ?>");
					$('.card').each(function() {
							$(this).html(linkHashtags($(this).html()));
							$(this).html(linkUsernames($(this).html()));
					});
			});
		</script>
  </body>
</html>
