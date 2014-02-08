<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";
require_once __DIR__ . "/../src/odf_type.php";

$text = new ODF();
$text->open("./append_text_input.odt");

ODF_Shortcut::setDocument($text->content);

$content = ODF_Text::getContentBody($text);

$path = $text->addPicture("./test_image1.jpg");
$image = ODF_Draw::createImage($path);
$frame = ODF_Draw::createFrame($image, array(ODF_Attribute::image_height => "10cm", ODF_Attribute::image_width => "10cm"));
$p = ODF_Text::createParagraph($frame);

$content->appendChild($p);

$h = ODF_Text::createHeading("My new Headline");

$nodes = ODF_Shortcut::search("//office:document-content/office:body/office:text");
$nodes->item(0)->appendChild($h);

$text->save("append_text_output.odt");