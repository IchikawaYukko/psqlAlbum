<?php
$container = "NewZealand";
$thumb_dirname = "thumbs/";
$photos = [];
$thumbs = [];

exec("source ./OpenStackAuth.sh;swift list $container", $output);

foreach ($output as $file) {
	if(stripos($file, '.jpg') !== false) {
		if(strpos($file, $thumb_dirname) !== false) {
			array_push($thumbs, substr($file, strlen($thumb_dirname)));
		} else {
			array_push($photos, $file);
		}
	}
}
foreach($photos as $p) {
	if(in_array($p, $thumbs, true)) {
	} else {
		echo $p." thumb-notfound\n";
		echo run_cmd("source ./OpenStackAuth.sh;swift download $container \"$p\"");
		echo run_cmd("convert -resize 220x \"$p\" tmp.jpg");
		echo run_cmd("source ./OpenStackAuth.sh;swift upload --object-name \"thumbs/$p\" $container tmp.jpg");
		echo run_cmd("rm \"$p\" tmp.jpg");
	}
}

function run_cmd($command) {
	echo "run--".$command."\n";
	return exec($command);
	echo "\n";
}
#  var_dump( $thumbs );
