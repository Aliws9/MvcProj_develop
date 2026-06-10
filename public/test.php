<?php
require_once 'jdf/jdf.php';

$jalali = '1405/09/20 16:00:00';

// جدا کردن تاریخ
list($date, $time) = explode(' ', $jalali);
list($y, $m, $d) = explode('/', $date);
list($h, $i, $s) = explode(':', $time);

// تبدیل به میلادی
$g = jalali_to_gregorian($y, $m, $d);

$gregorian = sprintf(
    "%04d-%02d-%02d %02d:%02d:%02d",
    $g[0], $g[1], $g[2],
    $h, $i, $s
);

echo $gregorian;