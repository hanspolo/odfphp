<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$sheet = new ODF();
$sheet->create("spreadsheet");

ODF_Shortcut::setDocument($sheet->content);

$content = ODF_Spreadsheet::getContentBody($sheet);

$cell1 = ODF_Spreadsheet::createTableCell("Cell 1");
$cell2 = ODF_Spreadsheet::createTableCell("Cell 2");

$row = ODF_Spreadsheet::createTableRow(null);
$row->appendChild($cell1);
$row->appendChild($cell2);

$content->appendChild(ODF_Spreadsheet::createTable($row));

$sheet->save("create_spreadsheet_output.ods");