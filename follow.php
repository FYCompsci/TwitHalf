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
    }
    $query = "
      UPDATE info SET following=:following WHERE username=:username
    ";
    $query_params = array(
      ':following' => $following.",".$_GET['follow'],
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
        $followers = $row['followers'] + 1;
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
        $followers = $row['followers'] - 1;
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
