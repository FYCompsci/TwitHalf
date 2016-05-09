<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
	if (isset($_GET['alert'])){
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
                    <textarea class="form-control" id="submitTextarea" name="content" rows="3" required="" placeholder="Give us your two cents!" maxlength="256"></textarea>
                  </div>
                  <div class="col-sm-3">
                    <input class="btn btn-block btn-primary" type="submit" value="Buzz" />
										</br>
										<span id="textarea-count">256</span>
                    <!--<button class="btn btn-block btn-danger" type="button" onclick="clearTextBox('#submitTextarea')">Clear</button>-->
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
		<script>
			var page_username = "<?php echo $_SESSION['user']['username']; ?>";
			$( document ).ready(function() {
				buildPosts("all", "all", 1);
				$('.card').each(function() {
						$(this).html(linkHashtags($(this).html()));
						$(this).html(linkUsernames($(this).html()));
				});
			});
			var userInfoData = JSON.parse(httpGet("info.php?user=" + page_username));
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
			$('#submitTextarea').keyup(function() {
        var text_length = $('#submitTextarea').val().length;
        $('#textarea-count').html(256 - text_length);
    	});
			/*
			function clearTextBox(container){
				$(container).attr("value", "");
			}
			*/
		</script>
  </body>
</html>
