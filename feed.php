<?php
  require("common.php");
	function pull_posts(){
		$query = "
     SELECT * FROM thebuzz.posts WHERE 1
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
