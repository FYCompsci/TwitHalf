hashtag_regexp = /#([a-zA-Z0-9]+)/g;
username_regexp = /@([a-zA-Z0-9]+)/g;

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
function buildPosts(username,hashtag){
  var arr = [];
  for (var key in feedData){
    if (feedData[key][1] == username || username == "all"){
      if (feedData[key][4] == hashtag || hashtag == "all"){
        arr.push(feedData[key]);
      }
    }
  }
  arr.sort(compareTimestamp);
  for (var i=0; i<arr.length; i++) {
    date = new Date(arr[i][3]*1000);
    date = date.getFullYear() + "/" + (1 + Number(date.getMonth())) + "/" + date.getDate();
    hashtags = arr[i][5].split(",");
    hashtag_label = "";
    for (j = 0; j < hashtags.length; j++){
      hashtag_label = hashtag_label + '<span class="label label-pill label-warning">'+hashtags[j]+'</span> ';
    }

    $("#feed-container").append('<div class="card"><div class="card-block"><h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+date+'</small></span></h4><p class="card-text">'+arr[i][2]+'</br>'+ hashtag_label +'</p></div></div>');
    /*
    Here's the non-minified version of the template of each "buzz". Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
    <div class="card">
      <div class="card-block">
        <h4 class="card-title">@'+arr[i][1]+' <span class="text-muted"><small>'+'arr[i][3]'+'</small></span></h4>
        <p class="card-text">
          '+arr[i][2]+'
          </br>
          '+ hashtag_label +'
        </p>
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
