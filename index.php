<?php
require_once('AutoAvatar/Autoloader.php');

use AutoAvatar\ImageCompiler;
use AutoAvatar\Image;

$colourArray = [
    '#00BE4B',
    '#003C87',
    '#FF649B',
    '#00BEC8',
    '#826EDC',
    '#F0503C',
    '#006E78',
    '#82144B',
    '#37DCB9',
    '#00B9FA',
    '#CD3700',
];
try {
    $image = new Image(70, 70, 'png');
} catch (\Exception $e) {
    print $e->getMessage();
    die();
}
$profilePic = new ImageCompiler("pics", $colourArray, ['#FFF'], 30, realpath("DS-DIGI.ttf"));
$imageInfo = $profilePic->compileImage(time().'.png', 'A', $image);

var_dump($imageInfo);