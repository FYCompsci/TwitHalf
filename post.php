<?php

  function getHashtags($string) {
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
    $query = "
      UPDATE posts SET liked=:likers WHERE id:post;
    ";
    $query_params = array(
      ':post' => $_GET['like'],
      ':likers' => $likers.$_SESSION['user']['username']
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

    header("Location: home.php?alert=like");
    die("Redirecting to home.php?alert=like");
  }
  else if(!empty($_POST))
  {
    if (isset($_GET['reply'])){
      $reply = $_GET['reply'];
    }
    else{
      $reply = "false";
    }
    $current_time = time();
    if (getHashtags($_POST['content']) == false){
      $hashtag_final = "none";
    }
    else{
      $hashtag_final = implode(",", getHashtags($_POST['content']));
    }
    $legit_content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $query_params = array(
        ':author' => $_SESSION['user']['username'],
        ':content' => $legit_content,
        ':timestamp' => $current_time,
        ':reply' => $reply,
        ':hashtag' => $hashtag_final,
        ':liked' => $_SESSION['user']['username']
    );

    $query = "
        INSERT INTO posts (
            author,
            content,
            timestamp,
            reply,
            hashtag,
            liked
        ) VALUES (
            :author,
            :content,
            :timestamp,
            :reply,
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

    header("Location: home.php");
    die("Redirecting to home.php");
  }
?>
