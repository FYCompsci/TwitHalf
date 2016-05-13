<?php
  // this is the overarching follow function, which contains parameters for following and unfollowing the user
  require("common.php");
  if (isset($_GET['follow'])){
    // these lines of code only runs if the follow paramter is added to the function
    $query = "
        SELECT * FROM info WHERE username=:username
    ";

    $query_params = array(
        ':username' => $_SESSION['user']['username']
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
        $following = $row['following'];
    }
    $query = "
      UPDATE info SET following=:following WHERE username=:username
    ";
    $query_params = array(
      ':following' => $following.",".$_GET['follow'], // we do this to add the followed user to the list of people our current user is following
      ':username' => $_SESSION['user']['username']
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

    $query = "
        SELECT * FROM info WHERE username=:username
    ";

    $query_params = array(
        ':username' => $_GET['follow']
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
        $followers = $row['followers'] + 1; // we do this to add 1 to the amount of followers the followed person has
    }
    $query = "
      UPDATE info SET followers=:followers WHERE username=:username
    ";
    $query_params = array(
      ':followers' => $followers,
      ':username' => $_GET['follow']
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

    header("Location: user.php?username=".$_GET['follow']."&action=follow");
    die("Redirecting to user.php?username=".$_GET['follow']."&action=follow");
  }
  else if (isset($_GET['unfollow'])){
    // this code only runs if the unfollow parameter is added
    $query = "
        SELECT * FROM info WHERE username=:username
    ";

    $query_params = array(
        ':username' => $_SESSION['user']['username']
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
        $following = $row['following'];
    }
    // these next three lines of code execute to remove the followed user from the current user's list of users
    $comma_string = ",".$_GET['unfollow'];
    $following = str_replace($comma_string, '', $following);
    $following = str_replace($_GET['unfollow'], '', $following);
    $query = "
      UPDATE info SET following=:following WHERE username=:username
    ";
    $query_params = array(
      ':following' => $following,
      ':username' => $_SESSION['user']['username']
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

    $query = "
        SELECT * FROM info WHERE username=:username
    ";

    $query_params = array(
        ':username' => $_GET['unfollow']
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
        $followers = $row['followers'] - 1; // we do this to subtract one from the amount of followers the followed person has
    }
    $query = "
      UPDATE info SET followers=:followers WHERE username=:username
    ";
    $query_params = array(
      ':followers' => $followers,
      ':username' => $_GET['unfollow']
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

    header("Location: user.php?username=".$_GET['unfollow']."&action=unfollow");
    die("Redirecting to user.php?username=".$_GET['unfollow']."&action=unfollow");
  }
?>
