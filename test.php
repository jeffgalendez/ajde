<?php

$i = 5;
class Foo {
	function Bar() {
		global $i;
		$i = 10;
	}
}
$retval = call_user_func_array(array('Foo', 'Bar'), array());
var_dump($i);
var_dump($retval);
