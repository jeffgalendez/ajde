<?php

if (isset($_GET['c'])) {
	
	?>
	
	<!--
	Adding up to at least 512 bytes with some text from
	http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	
	The server has not found anything matching the Request-URI. No
	indication is given of whether the condition is temporary or permanent. The
	410 (Gone) status code SHOULD be used if the server knows, through some
	internally configurable mechanism, that an old resource is permanently
	unavailable and has no forwarding address. This status code is commonly
	used when the server does not wish to reveal exactly why the request has
	been refused, or when no other response is applicable.
	-->
	
	<?php 
	
	@apache_setenv('no-gzip', 1);
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
	
    $sum = array();
    
	for ($i = 0; $i < $_GET['c']; $i++) {
		$mtime = microtime();
		$mtime = explode(' ', $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime;
		
		$temp = file_get_contents('http://ajde.local/ajdesite.html');
		
		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = ($endtime - $starttime);
		
		echo "attempt ".($i+1).": <em>" . round($totaltime, 5). "</em> seconds.";
		echo "<br/>";
		
		$sum[] = round($totaltime, 5);
		
		// give apache some rest
		usleep(100000);
		ob_implicit_flush(1);
	}
	echo '<strong>average: <em>' . round(array_sum($sum) / $i, 5) . '</em> seconds.</strong>';
	return;
}

?>

<p>
	loadtest. connections: <input type='text' value='5' id="c" />
	<button type='submit' onclick='document.getElementById("test").src="./loadtest.php?c=" + document.getElementById("c").value;'> start testing </button>
</p>

<iframe src='./loadtest.php?c=0' width='960' height='80%' id='test' ></iframe>