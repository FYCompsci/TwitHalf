<?php
  require("common.php");
  if (isset($_GET['follow'])){
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
        $followers = $row['followers'] + 1;
    }
    $query = "
      UPDATE info SET following=:following,followers=:followers WHERE username=:username
    ";
    $query_params = array(
      ':following' => $following.",".$_GET['follow'],
      ':followers' => $followers,
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

    header("Location: user.php?username=".$_GET['follow']."&action=follow");
    die("Redirecting to user.php?username=".$_GET['follow']."&action=follow");
  }
  else if (isset($_GET['unfollow'])){
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
        $followers = $row['followers'] -1;
    }
    $comma_string = ",".$_GET['unfollow'];
    $following = str_replace($comma_string, '', $following);
    $following = str_replace($_GET['unfollow'], '', $following);
    $query = "
      UPDATE info SET following=:following,followers=:followers WHERE username=:username
    ";
    $query_params = array(
      ':following' => $following,
      ':followers' => $followers,
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

    header("Location: user.php?username=".$_GET['unfollow']."&action=unfollow");
    die("Redirecting to user.php?username=".$_GET['unfollow']."&action=unfollow");
  }
?>
