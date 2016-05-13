/*
  This is our build posts function. It gets the post information, makes a few calculations and adjusts the post based on the current user, amount of likes, hashtags, etc.; then, it renders the post, and links the hashtags/usernames to on each post to their respective pages.
*/

// regex expressions defining usernames and hashtags. both pretty similar
hashtag_regexp = /#([a-zA-Z0-9]+)/g;
username_regexp = /@([a-zA-Z0-9]+)/g;

function httpGet(theUrl){
  // this is our xml request code, we use it to make requests to other page's information
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "GET", theUrl, false );
  xmlHttp.send( null );
  return xmlHttp.responseText;
}

function linkHashtags(text) {
  // runs our regex expression and links hashtags to their respective page
  return text.replace(
      hashtag_regexp,
      '<a class="hashtag" href="hashtag.php?hashtag=$1">#$1</a>'
  );
}
function linkUsernames(text) {
  // runs our regex expression and links usernames to their respective page
  return text.replace(
      username_regexp,
      '<a href="user.php?username=$1">@$1</a>'
  );
}

function compareTimestamp(a,b) {
  // a js compare function that sorts posts from newest to oldest
  if (a[3] < b[3])
    return 1;
  else if (a[3] > b[3])
    return -1;
  else
    return 0;
}
function compareLikes(a,b){
  // a js compare function that sorts posts from most liked to least liked
  if (a[6].split(",").length < b[6].split(",").length)
    return 1;
  else if (a[6].split(",").length > b[6].split(",").length)
    return -1;
  else
    return 0;
}
var feedData = JSON.parse(httpGet("feed.php")); // gets all posts available from 'posts' table
function buildPosts(username,hashtag,method,following){
  method = method || "time"; // optional method parameter, defaults to sorting by time
  following = following || 0; // optional following parameter, defaults to all
  var infoData = JSON.parse(httpGet("info.php?user=" + page_username)); // gets info of current user
  var arr = []; // list of posts that we're rendering
  if (following !== 0){ // if we're sorting by following
    for (var key in feedData){ // for every post in 'posts'
      if ($.inArray(feedData[key][1], infoData["following"].split(",")) > -1 || $.inArray(feedData[key][4], infoData["following"].split(",")) > -1){ // if the post is authored by somebody who the current user is following
        arr.push(feedData[key]); // add the post to list of posts that we are rendering
      }
    }
  }
  else{ // if we're not sorting by following (default)
    for (var key in feedData){
      if (feedData[key][1] == username || feedData[key][4] == username || username == "all"){ // if the post matches our username query
        if ($.inArray(hashtag, feedData[key][5].split(",")) > -1 || hashtag == "all"){ // if the post matches our hashtag query
          arr.push(feedData[key]); // add the post to list of posts that we are rendering
        }
      }
    }
  }
  if (method == "likes"){
    // if we're sorting by likes, sort them by likes
    arr.sort(compareLikes);
  }
  else{
    // sort by time (default)
    arr.sort(compareTimestamp);
  }
  for (var i=0; i<arr.length; i++) { // for every post that we're rendering
    date = new Date(arr[i][3]*1000); // creates js date object of the timestamp of the post
    date = date.getFullYear() + "/" + (1 + Number(date.getMonth())) + "/" + date.getDate(); // creates human-readable date string out of js date object
    if (arr[i][5] != "none"){ // if there are hashtags
      hashtags = arr[i][5].split(","); // puts each hashtag in an array
      hashtag_label = "</br>";
      for (j = 0; j < hashtags.length; j++){
        // for every hashtag in the post, make a bootstrap label to said hashtag
        hashtag_label = hashtag_label + '<span class="label label-pill label-warning">'+hashtags[j]+'</span> ';
      }
    }
    else{ // if there are no hashtags, render nothing in the hashtags area
      hashtag_label = "";
    }
    retweet = "";
    if (arr[i][4] != "false"){
      // if the post is a retweet, add the retweet header
      retweet = retweet + '<p class="card-text text-muted"><span class="fa fa-retweet" style="color:green;"></span> rebuzzed by @' + arr[i][4] + '</p>';
    }
    bar = ''; // creating the utilities bar
    if ($.inArray(page_username, arr[i][6].split(",")) > -1){ // if the post has been liked by the current user
      bar = bar + '<a href="post.php?unlike=' + arr[i][0] +'"><span class="fa fa-heart" style="color:red;"></span></a> <span style="color:red;">' + arr[i][6].split(",").length + '</span>';
    }
    else{ // if the post has not been liked by the current user
      bar = bar + '<a href="post.php?like=' + arr[i][0] +'"><span class="fa fa-heart-o" style="color:red;"></span></a> <span style="color:red;">' + arr[i][6].split(",").length + '</span>';
    }
    // adds the reply and retweet buttons
    bar = bar + '  <span class="fa fa-reply"></span>  <a href="post.php?retweet=' + arr[i][0] +'"><span class="fa fa-retweet"></span></a>';
    if (arr[i][1] == page_username || arr[i][4] == page_username || infoData['admin'] == 1){
      // if the user owns the post/retweet/ is an admin, add the delete button
      bar = bar + '  <a href="post.php?delete=' + arr[i][0] +'"><span class="fa fa-close" style="color:red;"></span></a>';
    }
    // adds a report button
    bar = bar + '  <a href="report.php?id=' + arr[i][0] + '"<span class="fa fa-exclamation-triangle text-warning"></span></a>';

    // appends the minified version of our card.
    $("#feed-container").append('<div class="card"><div class="card-block">' + retweet + '<div class="row"><div class="col-sm-2"><img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i][1]+'.png" alt="The drones bees are almost done their work!"></div><div class="col-sm-10"><h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+date+'</small></span></h4><p class="card-text">'+arr[i][2]+ hashtag_label +'</p><p class="card-text">'+bar+'</p></div></div></div></div>');
    /*
    Here's the non-minified version of the template of each "buzz". Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
    <div class="card">
      <div class="card-block">
        ' + retweet + '
        <div class="row">
          <div class="col-sm-2">
            <img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i][1]+'.png" alt="The drones bees are almost done their work!">
          </div>
          <div class="col-sm-10">
            <h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+date+'</small></span></h4>
            <p class="card-text">
              '+arr[i][2]+ hashtag_label +'
            </p>
            <p class="card-text">
              '+bar+'
            </p>
          </div>
        </div>
      </div>
    </div>
    */
  }
}
