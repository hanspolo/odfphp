<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$sheet = new ODF();
$sheet->create("spreadsheet");

$content = ODF_Spreadsheet::getContentBody($sheet);

$cell1 = ODF_Spreadsheet::createTableCell($sheet->content, "Cell 1");
$cell2 = ODF_Spreadsheet::createTableCell($sheet->content, "Cell 2");

$row = ODF_Spreadsheet::createTableRow($sheet->content, null);
$row->appendChild($cell1);
$row->appendChild($cell2);

$content->appendChild(ODF_Spreadsheet::createTable($sheet->content, $row));

$sheet->save("create_spreadsheet_output.ods");