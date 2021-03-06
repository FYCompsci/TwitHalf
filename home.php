<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
	if (isset($_GET['alert'])){
		// we get what action directed users to this page, which triggers what kind of alert that we use
    $action = $_GET['alert'];
  }
  else{
    $action = "default";
  }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>Home | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
    <div class="container">
      <div class="row">
        <div class="col-sm-8">
					<div class="alert alert-dismissable fade" role="alert" id="home-alert">
		        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		          <span class="fa fa-close"></span>
		         </button>
		        <div id="home-alert-content">
		        </div>
		      </div>
          <div class="card card-inverse bg-warning">
            <form action="post.php" method="post">
              <div class="card-block">
                <div class="row">
                  <div class="col-sm-9">
                    <textarea class="form-control" id="submitTextarea" name="content" rows="3" required="" placeholder="" maxlength="256"></textarea>
                  </div>
                  <div class="col-sm-3">
                    <input class="btn btn-block btn-primary" type="submit" value="Buzz" />
										</br>
										<div id="textarea-count">256</div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div id="feed-container">
					</div>
        </div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-block">
              <h2 class="card-title">Recent Buzzes</h2>
              <p class="card-text">
                <ul class="list-unstyled">
                  <li><a href="#">#Bootstrap</a><li>
                  <li><a href="#">#TheBuzz</a><li>
                  <li><a href="#">#GitHub</a><li>
                  <li><a href="#">#Atom</a><li>
                </ul>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once ('footer.php'); ?>
		<script src="js/jquery2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/buildPosts.js"></script>
		<script src="js/linkify.min.js"></script>
		<script src="js/linkify-jquery.min.js"></script>
		<script>
			var page_username = "<?php echo $_SESSION['user']['username']; ?>"; // we get the current user's name
			$( document ).ready(function() {
				buildPosts("all", "all","time", 1); // build posts
				$('.card').each(function() {
						$(this).html(linkHashtags($(this).html())); // link hashtags
						$(this).html(linkUsernames($(this).html())); // link usernames
				});
				$('.card').linkify(); // link http links
				var beePuns = JSON.parse(httpGet("buzzwords.json")); // gets the bee puns
				var buzzWord = beePuns[Math.floor(Math.random()*beePuns.length)]; // gets a random bee pun out of our list of bee puns
				$('#submitTextarea').attr("placeholder", buzzWord); // adds bee pun to the text area placeholder (so the little help text)
				$('#submitTextarea').keyup(function() {
					// this function deals with the text limit
	        var text_length = $('#submitTextarea').val().length;
					var text_left = 256 - text_length;
	        $('#textarea-count').html(text_left);
	    	});
			});
			var userInfoData = JSON.parse(httpGet("info.php?user=" + page_username)); // here we get user data, including their following list, follower list, etc.
			// this next blurb just gives you the action alert, which would depend on what page sent you to home
			if ("<?php echo $action; ?>" == "delete"){
        $('#home-alert').addClass("alert-info");
        $('#home-alert').addClass("in");
        $('#home-alert-content').html("You just <strong>successfuly</strong> deleted a post.");
      }
			else if ("<?php echo $action; ?>" == "like"){
        $('#home-alert').addClass("alert-info");
        $('#home-alert').addClass("in");
        $('#home-alert-content').html("You just <strong>successfuly</strong> liked a post.");
      }
			else if ("<?php echo $action; ?>" == "unlike"){
        $('#home-alert').addClass("alert-info");
        $('#home-alert').addClass("in");
        $('#home-alert-content').html("You just <strong>successfuly</strong> unliked a post.");
      }
      else{
				if (userInfoData['following'] == page_username){
					$('#home-alert').addClass("alert-success");
					$('#home-alert').addClass("in");
					$('#home-alert-content').html("<strong>Welcome!</strong> You should follow some people! Check out the <a class='alert-link' href='new.php'>new page</a> to find some people to follow!");
				}
				else{
	        $('#home-alert').addClass("alert-success");
	        $('#home-alert').addClass("in");
	        $('#home-alert-content').html("<strong>Welcome back!</strong> Here's a honeycomb of the latest buzzes.");
				}
      }
		</script>
  </body>
</html>
