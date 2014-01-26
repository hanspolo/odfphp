<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$text = new ODF();
$text->open("./append_text_input.odt");

$content = ODF_Text::getContentBody($text);



$text->save("append_text_output.odt");