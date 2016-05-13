<?php
  // general posts functions, covers creation, deletion, liking, and rebuzzing
  function getHashtags($string) {
    // this function gets all the hashtags from a string (so in this case, a buzz), using a regex expression
    $hashtags= false;
    preg_match_all("/(#\w+)/u", $string, $matches);
    if ($matches) {
        $hashtagsArray = array_count_values($matches[0]);
        $hashtags = array_keys($hashtagsArray);
    }
    return $hashtags;
  }
  require("common.php");
  if (isset($_GET['delete'])){
    // runs if deleting a post
    $query = "
      DELETE FROM posts WHERE id=:post;
    ";
    $query_params = array(
      ':post' => $_GET['delete']
    );
    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    header("Location: home.php?alert=delete");
    die("Redirecting to home.php?alert=delete");
  }
  else if (isset($_GET['like'])){
    // runs if we're liking a post
    $query = "
        SELECT * FROM posts WHERE id=:id
    ";

    $query_params = array(
        ':id' => $_GET['like']
    );

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    $row = $stmt->fetch();

    if($row)
    {
        $likers = $row['liked'];
    }
    if (!(in_array($_SESSION['user']['username'],explode(",",$likers)))){ // only runs if the user hasn't liked the post yet
      $query = "
        UPDATE posts SET liked=:likers WHERE id=:post
      ";
      $query_params = array(
        ':post' => $_GET['like'],
        ':likers' => $likers.",".$_SESSION['user']['username'] // adds user to list of who has liked the post
      );
      try
      {
          $stmt = $db->prepare($query);
          $result = $stmt->execute($query_params);
      }
      catch(PDOException $ex)
      {
          die("Failed to run query: " . $ex->getMessage());
      }
    }
    header("Location: home.php?alert=like");
    die("Redirecting to home.php?alert=like");
  }
  else if (isset($_GET['unlike'])){
    // runs on unlike
    $query = "
        SELECT * FROM posts WHERE id=:id
    ";

    $query_params = array(
        ':id' => $_GET['unlike']
    );

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    $row = $stmt->fetch();

    if($row)
    {
        $likers = $row['liked'];
    }
    if (in_array($_SESSION['user']['username'],explode(",",$likers))){ // only runs if user has liked post already
      // removes user from list of people who have liked the post
      $comma_string = ",".$_SESSION['user']['username'];
      $likers = str_replace($comma_string, '', $likers);
      $likers = str_replace($_SESSION['user']['username'], '', $likers);
      $query = "
        UPDATE posts SET liked=:likers WHERE id=:post
      ";
      $query_params = array(
        ':post' => $_GET['unlike'],
        ':likers' => $likers
      );
      try
      {
          $stmt = $db->prepare($query);
          $result = $stmt->execute($query_params);
      }
      catch(PDOException $ex)
      {
          die("Failed to run query: " . $ex->getMessage());
      }
    }

    header("Location: home.php?alert=unlike");
    die("Redirecting to home.php?alert=unlike");
  }
  else if (isset($_GET['retweet'])){
    // runs on rebuzz
    $current_time = time();
    $query = "
        SELECT * FROM posts WHERE id=:id
    ";

    $query_params = array(
        ':id' => $_GET['retweet']
    );

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }
    $row = $stmt->fetch();
    $query_params = array(
        ':author' => $row['author'],
        ':content' => $row['content'],
        ':timestamp' => $current_time,
        ':retweet' => $_SESSION['user']['username'],
        ':hashtag' => $row['hashtag'],
        ':liked' => $_SESSION['user']['username']
    );

    $query = "
        INSERT INTO posts (
            author,
            content,
            timestamp,
            retweet,
            hashtag,
            liked
        ) VALUES (
            :author,
            :content,
            :timestamp,
            :retweet,
            :hashtag,
            :liked
        )
    ";

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    header("Location: home.php?alert=retweet");
    die("Redirecting to home.php?alert=retweet");
  }
  else if(!empty($_POST)){
    // runs on post creation
    $retweet = "false";
    $current_time = time(); // gets current unix timestamp
    if (getHashtags($_POST['content']) == false){
      $hashtag_final = "none";
    }
    else{
      $hashtag_final = implode(",", getHashtags($_POST['content'])); // gets all hashtags from post
    }
    $legit_content = filter_var($_POST['content'], FILTER_SANITIZE_STRING); // this is super important! this stops people from using xml injections, so our users are safe and sound
    $query_params = array(
        ':author' => $_SESSION['user']['username'],
        ':content' => $legit_content,
        ':timestamp' => $current_time,
        ':retweet' => $retweet,
        ':hashtag' => $hashtag_final,
        ':liked' => $_SESSION['user']['username']
    );

    $query = "
        INSERT INTO posts (
            author,
            content,
            timestamp,
            retweet,
            hashtag,
            liked
        ) VALUES (
            :author,
            :content,
            :timestamp,
            :retweet,
            :hashtag,
            :liked
        )
    ";

    try
    {
        $stmt = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        die("Failed to run query: " . $ex->getMessage());
    }

    header("Location: home.php?alert=post");
    die("Redirecting to home.php?alert=post");
  }
?>
