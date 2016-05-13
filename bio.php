<?php
  // this page is the edit bio function, which edits the bio of a user. You can use this function on your own user page
  require("common.php");
  if(!empty($_POST)){
    $legit_content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $query = "
      UPDATE info SET bio=:bio WHERE username=:username
    ";
    $query_params = array(
      ':bio' => $legit_content,
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

    header("Location: user.php?username=".$_SESSION['user']['username']."&action=bio");
    die("Redirecting to user.php?username=".$_SESSION['user']['username']."&action=bio");
  }
?>
