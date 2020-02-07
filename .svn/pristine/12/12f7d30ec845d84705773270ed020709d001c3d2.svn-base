<?php

$title = 'Login History';
$subTitle = array(
    "Start Date: $STdate 00:00:00",
    "End Date: $EDdate 23:59:59"
);

$columns = array(
    'Sr.No',
    'Real Name',
    'User Name',
    'Role',
    'login with',
    'Page Name',
    'Datetime'
);
$columnlinks = array(
    '',
    '',
    '',
    '',
    '',
    '',
    ''
);


$middlecolumn='';
echo table_header($title, $subTitle, $columns, FALSE, $middlecolumn);


if ($columnlinks != null) {
    if (count($columnlinks) > 24) {
        echo "<style>.newTable th, .newTable td{padding:4px;}</style>";
    }
    $header = '';
//    $header .= '<tr>';
//    foreach ($columnlinks as $s_columns) {
//        $header .= "<th>$s_columns</th>";
//    }
//    $header .= '</tr>';
    echo $header;
}
?>