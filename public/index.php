<?php

//require_once 'protected/bootstrap.php';

$a = ['a', 'b', 'c'];
$pos = 1;
/*foreach ($a as $key => $val) {
	if ($key == 0)
		$a = pushAfter($a, $pos, ['test']);
//		$a = array_merge(array_slice($a, 0, $pos), ['test'], array_slice($a, $pos));

	var_dump($val);
}*/

for ($i = 0; $i < sizeof($a); $i++) {
	if ($i == 0)
		$a = pushAfter($a, $pos, ['test']);

	var_dump($a[$i]);
}

var_dump($a);

function pushAfter($a, $pos, $input)
{
	$a = array_merge(array_slice($a, 0, $pos), $input, array_slice($a, $pos));

	return $a;
}
//var_dump($a);