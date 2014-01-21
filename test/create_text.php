<?php

require_once __DIR__ . "/../src/odf.php";
require_once __DIR__ . "/../src/odf_shortcut.php";

$text = new ODF();
$text->create("text");

$body = $text->content->getElementsByTagName("body")->item(0);
$content = $body->getElementsByTagName("text")->item(0);

$content->appendChild(ODF_Text::createHeading($text->content, "Headline 1"));
$content->appendChild(ODF_Text::createParagraph($text->content, "Using a paragraph shortcut."));
$content->appendChild(ODF_Text::createParagraph($text->content, "And creating another paragraph"));

$item1 = ODF_Text::createListItem($text->content, "Item 1");
$item2 = ODF_Text::createListItem($text->content, "Item 2");
$list = ODF_Text::createList($text->content, $item1);
$list->appendChild($item2);

$content->appendChild($list);

$text->save("create_text_output.odt");
