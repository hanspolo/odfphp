<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$sheet = new ODF();
$sheet->open("./append_spreadsheet_input.ods");

$content = ODF_Spreadsheet::getContentBody($sheet);


$row1 = $content->getElementsByTagName("table")->item(0)->getElementsByTagName("table-row")->item(0);
$row2 = $content->getElementsByTagName("table")->item(0)->getElementsByTagName("table-row")->item(1);

$row1->appendChild(ODF_Spreadsheet::createTableCell($sheet->content, "Item C.1"));
$row2->appendChild(ODF_Spreadsheet::createTableCell($sheet->content, "Item C.2"));

$itema3 = ODF_Spreadsheet::createTableCell($sheet->content, "Item A.3");
$itemb3 = ODF_Spreadsheet::createTableCell($sheet->content, "Item B.3");
$itemc3 = ODF_Spreadsheet::createTableCell($sheet->content, "Item C.3");

$row3 = ODF_Spreadsheet::createTableRow($sheet->content, $itema3);
$row3->appendChild($itemb3);
$row3->appendChild($itemc3);

$content->getElementsByTagName("table")->item(0)->appendChild($row3);

$sheet->save("append_spreadsheet_output.ods");