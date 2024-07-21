<?php

$post = new Post($controller->con);

// echo $post->getPostLikes(1);

// foreach ( as $key => $value) {
// 	print_r($value);
// }

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<div id="posts">
		<button class="btn btn-primary" onclick="clickme(1, this);">
			SENPAI!!!
		</button>
	</div>



	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script type="text/javascript">
	
		function clickme(a, b) {

			console.log(a);
			console.log($(b));

		}

		$(function() {

		});
	</script>
</body>
</html>