<?php
  if (isset($_GET['user'])){
    $page_user = $_GET['user'];
  }
  else{
    header("Location: home.php");
    die("Redirecting to home.php");
  }
  require("common.php");
  $query = "
      SELECT
          *
      FROM info
      WHERE
          username = :username
  ";
  $query_params = array(
      ':username' => $page_user
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
    echo json_encode($row);
  }
?>
