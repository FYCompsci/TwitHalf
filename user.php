<?php
	require("common.php");
	if(empty($_SESSION['user'])){
		header("Location: index.php");
		die("Redirecting to index.php");
	}
  if (isset($_GET['username'])){
    $page_username = $_GET['username'];
  }
  else{
    header("Location: search.php");
    die("Redirecting to search.php");
  }
	if (isset($_GET['action'])){
    $page_action = $_GET['action'];
  }
	else{
		$page_action = "none";
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title><?php echo $page_username; ?> | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
      <div class="container">
				<div class="alert alert-dismissable fade" role="alert" id="user-alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span class="fa fa-close"></span>
					 </button>
					<div id="user-alert-content">
					</div>
				</div>
        <div class="row">
          <div class="col-sm-4">
            <img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/256/<?php echo $page_username ?>.png" alt="The drones bees are almost done their work!">
            <h1>@<?php echo $page_username; ?></h1>
						<div class="row">
							<div class="col-sm-6" id="page-following">
							</div>
							<div class="col-sm-6" id="page-followers">
							</div>
						</div>
						<div id="action-container"></div>
						<h3>Bio:</h3>
						<div id="bio-container"></div>
          </div>
          <div class="col-sm-8">
            <div id="feed-container">
          </div>
        </div>
			</div>
    </div>
		<div class="modal fade" id="bioModal" tabindex="-1" role="dialog" aria-labelledby="bioModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		        <h4 class="modal-title" id="bioModalLabel">Edit your bio!</h4>
		      </div>
					<form action="bio.php" method="post">
			      <div class="modal-body">
			        <textarea class="form-control" id="submitTextarea" name="content" rows="3" required="" placeholder="Tell us about yourself!" maxlength="256"></textarea>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Save changes</button>
			      </div>
				</form>
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
					buildPosts("<?php echo $page_username; ?>", "all");
					$('.card').each(function() {
							$(this).html(linkHashtags($(this).html()));
							$(this).html(linkUsernames($(this).html()));
					});
			});
			var pageuser = "<?php echo $page_username; ?>";
			var infoData = JSON.parse(httpGet("info.php?user=" + pageuser));
			var userInfoData = JSON.parse(httpGet("info.php?user=" + page_username));
			$("#bio-container").html(infoData["bio"]);
			if (pageuser != page_username){
				if ($.inArray(pageuser, userInfoData['following'].split(",")) > -1 ){
					$("#action-container").html("<a class='btn btn-block btn-info-outline' href='follow.php?unfollow="+pageuser+"'><span class='fa fa-check'></span> Following " + pageuser + "</a>");
				}
				else{
					$("#action-container").html("<a class='btn btn-block btn-info-outline' href='follow.php?follow="+pageuser+"'><span class='fa fa-plus'></span> Follow " + pageuser + "</a>");
				}
			}
			else{
				$("#action-container").html("<a class='btn btn-block btn-info-outline' href='#' data-toggle='modal' data-target='#bioModal'><span class='fa fa-edit'></span> Edit Bio</a>");
				$("#submitTextarea").val(infoData["bio"]);
			}
			$("#page-following").html("<h6><b>" + infoData['following'].split(",").length + "</b> <small>Following</small></h6>");
			$("#page-followers").html("<h6><b>" + infoData['followers'] + "</b> <small>Followers</small></h6>");
			if("<?php echo $page_action; ?>" == "bio" ){
				$('#user-alert').addClass("alert-info");
        $('#user-alert').addClass("in");
        $('#user-alert-content').html("You just successfuly <strong>updated your bio</strong>.");
			}
			else if ("<?php echo $page_action; ?>" != "none" ){
        $('#user-alert').addClass("alert-info");
        $('#user-alert').addClass("in");
        $('#user-alert-content').html("You just successfuly <strong><?php echo $page_action; ?>ed</strong> " + pageuser + ".");
			}
			else{
				$('#user-alert').addClass("alert-success");
        $('#user-alert').addClass("in");
        $('#user-alert-content').html("Welcome to " + pageuser + "'s page! Here's what they've been buzzing about.");
			}
		</script>
  </body>
</html>
