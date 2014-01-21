<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$sheet = new ODF();
$sheet->create("spreadsheet");

$body = $sheet->content->getElementsByTagName("body")->item(0);
$content = $body->getElementsByTagName("spreadsheet")->item(0);


//$content->appendChild(ODF_Spreadsheet::createRow($sheet->content), null);
		      

$sheet->save("create_spreadsheet_output.ods");