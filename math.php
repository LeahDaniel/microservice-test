<?php
$x = 5116977589701;
$y = 1877633434349;
$z = 3657076394412;
$time = 1718826064.22627;

$x2 = $x * $x;
$y2 = $y * $y;
$z2 = $z * $z;

$sum = $x2 + $y2 + $z2;

$sqrtSum = sqrt($sum);

$dividedBySpeedOfLight = $sqrtSum / 299792458;

$total = $dividedBySpeedOfLight + $time;

echo($total);

//1718847958.6312