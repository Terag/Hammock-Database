<?php

$pn = $_GET['PN'];
$v = $_GET['V'];

$opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
            "Cookie: foo=bar\r\n"
    )
);

$context = stream_context_create($opts);

/* Envoi une requête HTTP vers www.example.com
   avec les en-têtes additionnels ci-dessus */

switch ($v) {
    case 1:
        $fp = fopen('http://www2.partslogistics.com/demo/search-part_num-'.$pn.'.html#mcrd', 'r', false, $context);
        break;
    case 2:
        $fp = fopen('https://www2.partslogistics.com/demo/search.php/part_num/'.$pn, 'r', false, $context);
        break;
    default:
        echo 'N/A';
        exit();
        break;
}

fpassthru($fp);
fclose($fp);