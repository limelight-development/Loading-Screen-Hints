<?php

$files = array_filter(array_map(function($path){
	return realpath($path);
}, glob(__DIR__ . '/../source/**')));
sort($files);

//var_dump($files);

$hints = [];
foreach ($files as $file){
	$text = file_get_contents($file);
	$text = trim($text);

	[$category, $author, $body] = array_map(function($line){
		return trim($line);
	}, explode("\n", $text, 3));

	$hints[] = '$hints[] = [' . var_export($category, true) . ', ' . var_export($author, true) . ', "' . strtr($body, ["\r\n" => '\n', "\n" => '\n', "\t" => '\t', "\"" => '\"', "'" => '\'']) . '"];';
}

$body = file_get_contents(__DIR__ . '/../index.php.src');
$body = str_replace('/* OTHER_HINTS */', join("\n", $hints), $body);
file_put_contents(__DIR__ . '/../index.php', $body);
