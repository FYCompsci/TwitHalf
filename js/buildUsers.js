username_regexp = /@([a-zA-Z0-9]+)/g;

function httpGet(theUrl){
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "GET", theUrl, false );
  xmlHttp.send( null );
  return xmlHttp.responseText;
}

function linkUsernames(text) {
    return text.replace(
        username_regexp,
        '<a href="user.php?username=$1">@$1</a>'
    );
}

function compareFollowers(a,b) {
  if (a[4] < b[4])
    return 1;
  else if (a[4] > b[4])
    return -1;
  else
    return 0;
}

function buildUsers(username,method){
  method = method || "single";
  var infoData = JSON.parse(httpGet("info.php?user=" + username));
  var arr = [];

  if (method == single){
    arr = infoData;
  }
  else{
    console.log("Coming soon!");
  }

  if (arr.length === 0){
    $("feed-container").html("This honeycomb is empty. Are you sure you searched for the right bee?")
  }
  else{

    if (method == "followers"){
      arr.sort(compareFollowers);
    }
    for (var i=0; i<arr.length; i++) {
      console.log("Rendering!");
      if (arr[i][1] != page_username){
        if ($.inArray(arr[i][1], userInfoData['following'].split(",")) > -1 ){
          fbutton = "<a class='btn btn-block btn-info-outline' href='follow.php?unfollow="+arr[i][1]+"'><span class='fa fa-check'></span> Following " + arr[i][1] + "</a>";
        }
        else{
          fbutton = "<a class='btn btn-block btn-info-outline' href='follow.php?follow="+arr[i][1]+"'><span class='fa fa-plus'></span> Follow " + arr[i][1] + "</a>";
        }
      }
      else{
        fbutton = "<a class='btn btn-block btn-info-outline' href='user.php?username="+page_username+"'>Queen Bee</a>";
      }
      following = arr[i][3].split(",").length;
      followers = arr[i][4];
      console.log("Printing!");
      $("#feed-container").append('<div class="card"><div class="card-block"><div class="row"><div class="col-sm-2"><img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i][1]+'.png" alt="The drones bees are almost done their work!"></div><div class="col-sm-10"><div class="row"><div class="col-sm-9"><h4 class="card-title">@'+arr[i][1]+'</h4><h6>' + following + ' Following, ' + followers + ' Followers</div><div class="col-sm-3">' + fbutton + '</div></div></div></div></div></div>');
      /*
      Here's the non-minified version of the template of each user. Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
      <div class="card">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-2">
              <img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i][1]+'.png" alt="The drones bees are almost done their work!">
            </div>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-9">
                  <h4 class="card-title">@'+arr[i][1]+'</h4>
                  <h6>' + following + ' Following, ' + followers + ' Followers
                </div>
                <div class="col-sm-3">
                  ' + fbutton + '
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      */
      console.log("Finished!");
    }
  }
}
