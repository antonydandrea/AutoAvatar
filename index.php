<?php
require_once('src/AutoAvatar.php');

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

$profilePic = new AutoAvatar("pics", $colourArray, ['#FFF'], 70, 70, 30, realpath("DS-DIGI.ttf"));
$profilePic->generateNewImage(time().'.png', 'A');

