<?php
$file_name = "/style/MovingBackgrounds.min.css";

$file1 = basename($file_name);
$file2 = basename($file_name, "." . pathinfo($file1, PATHINFO_EXTENSION));

// Show filename with file extension
echo $file1 . "\n";


echo basename($file_name, "." . pathinfo(basename($file_name), PATHINFO_EXTENSION));
