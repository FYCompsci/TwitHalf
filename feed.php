<?php
  //this is the feed function. Very simply, it outputs all the posts in the "posts" database in JSON format. It acts as a Pseudo-API, and also functions as an easy way to parse data (since JSON formatted files can be parsed by Javascript very easily.)
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
