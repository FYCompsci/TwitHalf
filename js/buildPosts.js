hashtag_regexp = /#([a-zA-Z0-9]+)/g;
username_regexp = /@([a-zA-Z0-9]+)/g;

function httpGet(theUrl){
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "GET", theUrl, false );
  xmlHttp.send( null );
  return xmlHttp.responseText;
}
function compareTimestamp(a,b) {
  if (a[3] < b[3])
    return 1;
  else if (a[3] > b[3])
    return -1;
  else
    return 0;
}
function buildPosts(username,hashtag,following){
  following = following || 0;
  var feedData = JSON.parse(httpGet("feed.php"));
  var infoData = JSON.parse(httpGet("info.php?user=" + page_username));
  var arr = [];
  if (following != 0){
    for (var key in feedData){
      if ($.inArray(feedData[key][1], feedData[key][5].split(",")) > -1 || hashtag == "all"){
        arr.push(feedData[key]);
      }
    }
  }
  else{
    for (var key in feedData){
      if (feedData[key][1] == username || username == "all"){
        if ($.inArray(hashtag, infoData["following"].split(",")) > -1){
          arr.push(feedData[key]);
        }
      }
    }
  }
  arr.sort(compareTimestamp);
  for (var i=0; i<arr.length; i++) {
    date = new Date(arr[i][3]*1000);
    date = date.getFullYear() + "/" + (1 + Number(date.getMonth())) + "/" + date.getDate();
    if (arr[i][5] != "none"){
      hashtags = arr[i][5].split(",");
      hashtag_label = "</br>";
      for (j = 0; j < hashtags.length; j++){
        hashtag_label = hashtag_label + '<span class="label label-pill label-warning">'+hashtags[j]+'</span> ';
      }
    }
    else{
      hashtag_label = "";
    }
    bar = '';
    if ($.inArray(page_username, arr[i][6].split(",")) > -1){
      bar = bar + '<a href="post.php?unlike=' + arr[i][0] +'"><span class="fa fa-heart" style="color:red;"></span></a> <span style="color:red;">' + arr[i][6].split(",").length + '</span>';
    }
    else{
      bar = bar + '<a href="post.php?like=' + arr[i][0] +'"><span class="fa fa-heart-o" style="color:red;"></span></a> <span style="color:red;">' + arr[i][6].split(",").length + '</span>';
    }
    bar = bar + '  <span class="fa fa-reply"></span>  <span class="fa fa-retweet"></span>';
    if (arr[i][1] == page_username || infoData['admin'] == 1){
      bar = bar + '  <a href="post.php?delete=' + arr[i][0] +'"><span class="fa fa-close" style="color:red;"></span></a>';
    }
    bar = bar + '  <span class="fa fa-exclamation-triangle"></span>';

    $("#feed-container").append('<div class="card"><div class="card-block"><div class="row"><div class="col-sm-2"><img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i][1]+'.png" alt="The drones bees are almost done their work!"></div><div class="col-sm-10"><h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+date+'</small></span></h4><p class="card-text">'+arr[i][2]+ hashtag_label +'</p><p class="card-text">'+bar+'</p></div></div></div></div>');
    /*
    Here's the non-minified version of the template of each "buzz". Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
    <div class="card">
      <div class="card-block">
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
function linkHashtags(text) {
    return text.replace(
        hashtag_regexp,
        '<a class="hashtag" href="hashtag.php?hashtag=$1">#$1</a>'
    );
}
function linkUsernames(text) {
    return text.replace(
        username_regexp,
        '<a class="hashtag" href="user.php?username=$1">@$1</a>'
    );
}
