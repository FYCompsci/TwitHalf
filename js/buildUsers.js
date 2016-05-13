/*
  This is our build users function. It gets the user's information, then renders a card with a following button and their picture.
*/

// username regex
username_regexp = /@([a-zA-Z0-9]+)/g;

function httpGet(theUrl){
  // this is our xml request code, we use it to make requests to other page's information
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.open( "GET", theUrl, false );
  xmlHttp.send( null );
  return xmlHttp.responseText;
}

function linkUsernames(text) {
  // runs our regex expression and links usernames to their respective page
  return text.replace(
      username_regexp,
      '<a href="user.php?username=$1">@$1</a>'
  );
}

function compareFollowers(a,b) {
  // sorts users by follower count
  if (a[4] < b[4])
    return 1;
  else if (a[4] > b[4])
    return -1;
  else
    return 0;
}

function buildUsers(username,method){
  method = method || "single"; // method is an optional parameter, but defaults to single
  var infoData = JSON.parse(httpGet("info.php?user=" + username)); // get info about user that we're searching for
  var userInfoData = JSON.parse(httpGet("info.php?user=" + page_username)); // get info about current user
  var arr = []; // building posts queue
  if (method == "single"){
    arr[0] = infoData; // adds the info that we got to queue
  }

  if (arr.length === 0){
    // if nothing is found, just add an error message
    $("feed-container").html("This honeycomb is empty. Are you sure you searched for the right bee?");
  }
  else{
    if (method == "followers"){
      // if we're sorting by followers, sort it by followers
      arr.sort(compareFollowers);
    }
    for (var i=0; i<arr.length; i++) { // for every user in queue
      if (arr[i]["username"] != page_username){
        // if the user isn't the current user
        if ($.inArray(arr[i]["username"], userInfoData['following'].split(",")) > -1 ){ // if the current user is following the user. display a following button with an unfollow option
          fbutton = "<a class='btn btn-block btn-info-outline' href='follow.php?unfollow="+arr[i]["username"]+"'><span class='fa fa-check'></span> Following</a>";
        }
        else{ // if the current user isn't following the user, add an option to follow
          fbutton = "<a class='btn btn-block btn-info-outline' href='follow.php?follow="+arr[i]["username"]+"'><span class='fa fa-plus'></span> Follow</a>";
        }
      }
      else{
        // if the user is yourself, just add a little flair
        fbutton = "<a class='btn btn-block btn-info-outline' href='user.php?username="+page_username+"'>Queen Bee</a>";
      }
      following = arr[i]["following"].split(",").length; // gets amount of people user is following
      followers = arr[i]["followers"]; // gets amount of followers user has
      // renders minified version of user card
      $("#feed-container").append('<div class="card"><div class="card-block"><div class="row"><div class="col-sm-2"><img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i]["username"]+'.png" alt="The drones bees are almost done their work!"></div><div class="col-sm-10"><div class="row"><div class="col-sm-9"><h4 class="card-title">@'+arr[i]["username"]+'</h4><h6>' + following + ' Following, ' + followers + ' Followers</div><div class="col-sm-3">' + fbutton + '</div></div></div></div></div></div>');
      /*
      Here's the non-minified version of the template of each user. Unfortunately, JS variables don't support newlines, so we need to condense it before it is appended to the container.
      <div class="card">
        <div class="card-block">
          <div class="row">
            <div class="col-sm-2">
              <img class="img-fluid img-thumbnail center-block" src="https://api.adorable.io/avatars/64/'+arr[i]["username"]+'.png" alt="The drones bees are almost done their work!">
            </div>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-9">
                  <h4 class="card-title">@'+arr[i]["username"]+'</h4>
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
    }
  }
}
