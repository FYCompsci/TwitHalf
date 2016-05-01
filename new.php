<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>New | The Buzz</title>
		<link href="favicon.ico" rel="icon" />
		<link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
		<link href="css/style.css" rel="stylesheet" />
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
      <div class="container">
			<h1 class="display-2">What's new?</h1>
			<h4>Find about what everybody is <b>buzzing about.</b></h4>
				<div id="feed-container">
			</div>
    </div>
    <?php include_once ('footer.php'); ?>
		<script src="js/jquery2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
		<script>
			function httpGet(theUrl){
				var xmlHttp = new XMLHttpRequest();
				xmlHttp.open( "GET", theUrl, false );
				xmlHttp.send( null );
				return xmlHttp.responseText;
			}
			var feedData = JSON.parse(httpGet("feed.php"));
			function compareTimestamp(a,b) {
				if (a[3] < b[3])
					return 1;
				else if (a[3] > b[3])
					return -1;
				else
					return 0;
			}
			function buildPosts(){
				var arr = [];
				for (var key in feedData){
					arr.push(feedData[key]);
				}
				arr.sort(compareTimestamp);
				for (var i=0; i<arr.length; i++) {
					date = new Date(arr[i][3]*1000);
					date = date.getFullYear() + "/" + (1 + Number(date.getMonth())) + "/" + date.getDate();
					$("#feed-container").append('<div class="card"><div class="card-block"><h4 class="card-title"><a href="user.php?username='+arr[i][1]+'">@'+arr[i][1]+'</a> <span class="text-muted"><small>'+date+'</small></span></h4><p class="card-text">'+arr[i][2]+'</p></div></div>');
					/*
					Here's the non-minified version of the template of each "buzz". Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
					<div class="card">
						<div class="card-block">
							<h4 class="card-title"><a href="user.php?username='+arr[i][1]+'">@'+arr[i][1]+'</a> <span class="text-muted"><small>'+'arr[i][3]'+'</small></span></h4>
							<p class="card-text">'arr[i][2]'</p>
						</div>
					</div>
					*/
			  }
			}
			hashtag_regexp = /#([a-zA-Z0-9]+)/g;
			function linkHashtags(text) {
          return text.replace(
              hashtag_regexp,
              '<a class="hashtag" href="hashtag.php?hashtag=$1">#$1</a>'
          );
      }
			$( document ).ready(function() {
			    buildPosts();
          $('.card').each(function() {
              $(this).html(linkHashtags($(this).html()));
          });
			});
		</script>
  </body>
</html>
