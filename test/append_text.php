<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$text = new ODF();
$text->open("./append_text_input.odt");

$content = ODF_Text::getContentBody($text);

$path = $text->addPicture("./test_image1.jpg");
$image = ODF_Draw::createImage($text->content, $path);
$frame = ODF_Draw::createFrame($text->content, $image);
$p = ODF_Text::createParagraph($text->content, $frame);

$content->appendChild($p);

$text->save("append_text_output.odt");