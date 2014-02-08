<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$text = new ODF();
$text->create("text");

$content = ODF_Text::getContentBody($text);

ODF_Shortcut::setDocument($text->content);

$content->appendChild(ODF_Text::createHeading("Headline 1"));
$content->appendChild(ODF_Text::createParagraph("Using a paragraph shortcut."));
$content->appendChild(ODF_Text::createParagraph("And creating another paragraph"));

$item1 = ODF_Text::createListItem("Item 1");
$item2 = ODF_Text::createListItem("Item 2");
$list = ODF_Text::createList($item1);
$list->appendChild($item2);

$content->appendChild($list);

$text->save("create_text_output.odt");
