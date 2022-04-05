<?php 

header('Content-Type: image/jpeg');

if(isset($_GET['image'])) {
	$img = htmlentities($_GET['image']);
	if(empty($img)) {
		readfile('student_photos/blank.png');
	} else {
		if(file_exists('student_photos/' . $img)) {
			readfile('student_photos/' . $img);	
		} else {
			readfile('student_photos/blank.png');
		}
	}
} else {
	readfile('student_photos/blank.png');
}