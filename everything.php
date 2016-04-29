<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="author" content="">
		<title>Everything Feed | The Buzz</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <style>
			.full-nav > li > .dropdown-menu { min-width: 300px;}
    </style>
	</head>
	<body>
		<?php include_once ('navbar.php'); ?>
    <div class="container" style="padding-top:70px;">
			<h1 class="display-2">Everything.</h1>
			<h4>That's right, <b>everything.</b></h4>
				<div id="feed-container">
			</div>
    </div>
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
				for (var i=0; i<=arr.length; i++) {
					date = new Date(arr[i][3]*1000);
					date = date.getFullYear() + "/" + date.getMonth()+1 + "/" + date.getDate();
					$("#feed-container").append('<div class="card"><div class="card-block"><h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+date+'</small></span></h4><p class="card-text">'+arr[i][2]+'</p></div></div>');
					/*
					Here's the non-minified version of the template of each "buzz". Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
					<div class="card">
						<div class="card-block">
							<h4 class="card-title">@'+'arr[i][1]'+' <span class="text-muted"><small>'+'arr[i][3]'+'</small></span></h4>
							<p class="card-text">'arr[i][2]'</p>
						</div>
					</div>
					*/
			  }
			}
			$( document ).ready(function() {
			    buildPosts();
			});
		</script>
  </body>
</html>
