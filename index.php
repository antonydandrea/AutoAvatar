<?php
require_once('src/ProfilePicGenerator.php');

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

$profilePic = new ProfilePicGenerator("pics", $colourArray, ['#FFF'], 70, 70);
$profilePic->generateNewImage(time().'.png', 'A');

