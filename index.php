<?php
header('Content-Type: application/json; charset=utf-8');

require 'vendor/autoload.php';

use App\Controllers\UploadController;

$controller = new UploadController();

$files = $controller->uploadFromRequest(md5('flipbook'), 'flipbook');
print_r(json_encode($files));
