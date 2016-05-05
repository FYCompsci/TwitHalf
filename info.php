<?php
  if (isset($_GET['user'])){
    $page_user = $_GET['user'];
  }
  else{
    header("Location: home.php");
    die("Redirecting to home.php");
  }
  require("common.php");
  function pull_posts(){
		$query = "
     SELECT * FROM thebuzz.posts WHERE username='"+ $page_user +"'
    ";

    $result = mysql_query($query) or die ("Error in query: $query. ".mysql_error());

		$data = [];
		while($row = mysql_fetch_row($result)) {
			array_push($data, $row);
		}

		return(json_encode($data));
		mysql_free_result($result);
		mysql_close($connection);
	}
	echo pull_posts();
?>
