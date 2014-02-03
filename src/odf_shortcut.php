<?php

include_once __DIR__ . "/odf_type.php";

/**
 * Abstract class for Shortcuts.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
abstract class ODF_Shortcut
{
  /**
   * Searchs inside the DOM
   *
   * @param DOMDocument $document
   * @param String $path
   * @param DOMNode $relative
   *
   * @return DOMList
   */
  public static function search($document, $path, $relative = null)
  {
    $xpath = new DOMXPath($document);

    return $relative != null ? $xpath->query($path, $relative) : $xpath->query($path);
  }

  /**
   * Creates a new Element.
   *
   * @param DOMDocument $document
   * @param String $title
   * @param Mixed $content
   * @param Boolean $isleaf
   *
   * @return DOMElement
   *
   * @throws Exception
   */
  protected static function createElement($document, $title, $content, $isleaf = false)
  {
    if (is_null($content))
      $element = $document->createElement($title);
    else if (is_string($content))
      $element = $document->createElement($title, $content);
    else if (!$isleaf && $content instanceof DOMNode)
      {
	$element = $document->createElement($title);
	$element->appendChild($content);
      }
    else
      throw new Exception('Content has to be a String' . ($isleaf ? '' : ' or a DOMNode'));

    return $element;
  }

  /**
   * Adds or updates attributes to an element.
   *
   * @param DOMElement $element
   * @param Array $attributes
   * @param Array $allowed_attributes
   */
  protected static function setAttributes(&$element, $attributes, $allowed_attributes)
  {
    foreach ($attributes as $key => $value)
      {
	if (in_array($key, $allowed_attributes))
	  $element->setAttribute($key, $value);
      }
  }
}

/**
 * Shortcuts primarily for Text-documents.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF_Text extends ODF_Shortcut
{
  /**
   * Returns the Element /body/text.
   *
   * @param DOMDocument $document
   *
   * @return DOMElement
   */
  public static function getContentBody($document)
  {
    return $document->content->getElementsByTagName("body")->item(0)->getElementsByTagName("text")->item(0);
  }

  /**
   * Creates a Headline Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createHeading($document, $content, $attributes = array())
  {
    $allowed_attributes = array("text:style-name");

    $h = self::createElement($document, ODF_Node::h, $content);
    self::setAttributes($h, $attributes, $allowed_attributes);

    return $h;
  }

  /**
   * Creates a Paragraph Element
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createParagraph($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    $p = self::createElement($document, ODF_Node::p, $content);
    self::setAttributes($p, $attributes, $allowed_attributes);

    return $p;
  }

  /**
   * Creates a List Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createList($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    $list = self::createElement($document, ODF_Node::list_body, $content);
    self::setAttributes($list, $attributes, $allowed_attributes);

    return $list;
  }

  /**
   * Creates a List-Header Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createListHeader($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    $listheader = self::createElement($document, ODF_Node::list_header, $content);

    self::setAttributes($listheader, $attributes, $allowed_attributes);

    return $listheader;
  }

  /**
   * Creates a List-Item Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createListItem($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    if (is_string($content))
      $content = self::createParagraph($document, $content, $attributes);

    $listitem = self::createElement($document, ODF_Node::list_item, $content);
    self::setAttributes($listitem, $attributes, $allowed_attributes);

    return $listitem;
  }
}

/**
 * Shortcuts primarily for Spreadsheet-documents.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF_Spreadsheet extends ODF_Shortcut
{
  /**
   * Returns the Element /body/spreadsheet.
   *
   * @param DOMDocument $document
   *
   * @return DOMElement
   */
  public static function getContentBody($document)
  {
    return $document->content->getElementsByTagName("body")->item(0)->getElementsByTagName("spreadsheet")->item(0);
  }

  /**
   * Creates a Table Element.
   * 
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTable($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    $table = self::createElement($document, ODF_Node::table, $content);
    self::setAttributes($row, $attributes, $allowed_attributes);

    return $table;
  }

  /**
   * Creates a Table-Row Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTableRow($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    $row = self::createElement($document, ODF_Node::table_row, $content);
    self::setAttributes($row, $attributes, $allowed_attributes);

    return $row;
  }

  /**
   * Create a Table-Cell Element.
   * If $content is a String, it creates a Paragrah containing this String.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTableCell($document, $content, $attributes = array())
  {
    $allowed_attributes = array();

    if (is_string($content))
      $content = ODF_Text::createParagraph($document, $content, $attributes);

    $cell = self::createElement($document, ODF_Node::table_cell, $content);
    self::setAttributes($cell, $attributes, $allowed_attributes);

    return $cell;
  }
}


/**                                                                                                                                                                                                                              
 * Shortcuts primarily for Draw-documents.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF_Draw extends ODF_Shortcut
{
  /**
   * Returns the Element /body/spreadsheet.
   *
   * @param DOMDocument $document
   *
   * @return DOMElement
   */
  public static function getContentBody($document)
  {
    return $document->content->getElementsByTagName("body")->item(0)->getElementsByTagName("draw")->item(0);
  }

  /**
   * Creates a Frame Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createFrame($document, $content, $attributes = array())
  {
    $allowed_attributes = array("draw:style-name", ODF_Attribute::image_height, ODF_Attribute::image_width, "text:anchor-type", "draw:z-index");

    $attributes["draw:style-name"] = "fr1";
    $attributes["text:anchor-type"] = "paragraph";
    $attributes["draw:z-index"] = 0;

    $frame = self::createElement($document, ODF_Node::frame, $content);
    self::setAttributes($frame, $attributes, $allowed_attributes);

    return $frame;
  }

  /**
   * Creates a Image Element.
   *
   * @param DOMDocument $document
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createImage($document, $content, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::href, "xlink:type", "xlink:show", "xlink:actuate");

    $attributes["xlink:type"] = "simple";
    $attributes["xlink:show"] = "embed";
    $attributes["xlink:actuate"] = "onLoad";

    if (is_string($content))
      {
	$attributes[ODF_Attribute::href] = $content;
	$content = "";
      }

    $image = self::createElement($document, ODF_Node::image, $content);
    self::setAttributes($image, $attributes, $allowed_attributes);

    return $image;
  }
}