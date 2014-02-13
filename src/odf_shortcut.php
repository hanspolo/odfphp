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
  private static $document;
  
  /**
   *
   */
  public static function setDocument(&$document)
  {
    self::$document = $document;
  }

  /**
   * Searchs inside the DOM
   *
   * @param String $path
   * @param DOMNode $relative
   *
   * @return DOMList
   */
  public static function search($path, $relative = null)
  {
    $xpath = new DOMXPath(self::$document);

    return $relative != null ? $xpath->query($path, $relative) : $xpath->query($path);
  }

  /**
   * Creates a new Element.
   *
   * @param String $title
   * @param Mixed $content
   * @param Boolean $isleaf
   *
   * @return DOMElement
   *
   * @throws Exception
   */
  protected static function createElement($title, $content, $isleaf = false)
  {
    if (is_null($content))
      $element = self::$document->createElement($title);
    else if (is_string($content))
      $element = self::$document->createElement($title, $content);
    else if (!$isleaf && $content instanceof DOMNode)
      {
	$element = self::$document->createElement($title);
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
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createHeading($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::class_names, ODF_Attribute::cond_style_name, ODF_Attribute::text_id, ODF_Attribute::is_list_header, ODF_Attribute::outline_level, ODF_Attribute::restart_numbering, ODF_Attribute::start_value, ODF_Attribute::style_name, ODF_Attribute::about, ODF_Attribute::content, ODF_Attribute::datatype, ODF_Attribute::property, ODF_Attribute::id);

    $h = self::createElement(ODF_Node::h, $content);
    self::setAttributes($h, $attributes, $allowed_attributes);

    return $h;
  }

  /**
   * Creates a Paragraph Element
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createParagraph($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::class_names, ODF_Attribute::cond_style_name, ODF_Attribute::text_id, ODF_Attribute::style_name, ODF_Attribute::about, ODF_Attribute::content, ODF_Attribute::datatype, ODF_Attribute::property, ODF_Attribute::id);

    $p = self::createElement(ODF_Node::p, $content);
    self::setAttributes($p, $attributes, $allowed_attributes);

    return $p;
  }

  /**
   * Creates a List Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createList($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::continue_list, ODF_Attribute::continue_numbering, ODF_Attribute::style_name, ODF_Attribute::id);

    $list = self::createElement(ODF_Node::list_body, $content);
    self::setAttributes($list, $attributes, $allowed_attributes);

    return $list;
  }

  /**
   * Creates a List-Header Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createListHeader($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::id);

    $listheader = self::createElement(ODF_Node::list_header, $content);

    self::setAttributes($listheader, $attributes, $allowed_attributes);

    return $listheader;
  }

  /**
   * Creates a List-Item Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createListItem($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::start_value, ODF_Attribute::style_override, ODF_Attribute::id);

    if (is_string($content))
      $content = self::createParagraph($content, $attributes);

    $listitem = self::createElement(ODF_Node::list_item, $content);
    self::setAttributes($listitem, $attributes, $allowed_attributes);

    return $listitem;
  }

  /**
   * Creates a Numbered-Paragraph Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createNumberedParagraph($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::continue_list, ODF_Attribute::text_level, ODF_Attribute::list_id, ODF_attribute::start_value, ODF_Attribute::style_name, ODF_Attribute::id);

    if (is_string($content))
      $content = self::createParagraph($content, $attributes);

    $numberedp = self::createElement(ODF_Node::numbered_p, $content);
    self::setAttributes($numberedp, $attributes, $allowed_attributes);

    return $numberedp;
  }

  /**
   * Creates a Page-Sequence Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createPageSequence($content = null, $attributes = array())
  {
    $allowed_attributes = array();

    $pagesequence = self::createElement(ODF_Node::page_sequence, $content);
    self::setAttributes($pagesequnce, $attributes, $allowed_attributes);

    return $pagesequence;
  }

  /**
   * Creates a Page Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createPage($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::master_page_name);

    $page = self::createElement(ODF_Node::page, $content);
    self::setAttributes($page, $attributes, $allowed_attributes);

    return $page;
  }

  /**
   * Creates a Section Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createSection($content = null, $attributes = array())
  {
    $allowed_attributes = array(ODF_Attribute::text_condition, ODF_Attribute::text_display, ODF_Attribute::text_name, ODF_Attribute::text_protected, ODF_Attribute::text_protection_key, ODF_Attribute::text_protection_key_digest_algorithm, ODF_Attribute::style_name, ODF_Attribute::id);

    $section = selct::createElement(ODF_Node::section, $content);
    self::setAttributes($section, $attributes, $allowed_attributes);

    return $section;
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
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTable($content = null, $attributes = array())
  {
    $allowed_attributes = array();

    $table = self::createElement(ODF_Node::table, $content);
    self::setAttributes($row, $attributes, $allowed_attributes);

    return $table;
  }

  /**
   * Creates a Table-Row Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTableRow($content = null, $attributes = array())
  {
    $allowed_attributes = array();

    $row = self::createElement(ODF_Node::table_row, $content);
    self::setAttributes($row, $attributes, $allowed_attributes);

    return $row;
  }

  /**
   * Create a Table-Cell Element.
   * If $content is a String, it creates a Paragrah containing this String.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTableCell($content = null, $attributes = array())
  {
    $allowed_attributes = array();

    if (is_string($content))
      $content = ODF_Text::createParagraph($content, $attributes);

    $cell = self::createElement(ODF_Node::table_cell, $content);
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
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createFrame($content = null, $attributes = array())
  {
    $allowed_attributes = array("draw:style-name", ODF_Attribute::image_height, ODF_Attribute::image_width, "text:anchor-type", "draw:z-index");

    $attributes["draw:style-name"] = "fr1";
    $attributes["text:anchor-type"] = "paragraph";
    $attributes["draw:z-index"] = 0;

    $frame = self::createElement(ODF_Node::frame, $content);
    self::setAttributes($frame, $attributes, $allowed_attributes);

    return $frame;
  }

  /**
   * Creates a Image Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createImage($content = null, $attributes = array())
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

    $image = self::createElement(ODF_Node::image, $content);
    self::setAttributes($image, $attributes, $allowed_attributes);

    return $image;
  }
}

/**
 * Shortcuts primarily for Style-Elements.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @pacakge ODF-PHP
 * @version 0.1
 */
class ODF_Style extends ODF_Shortcut
{
  /**
   * Creates a Style Element.
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createStyle($content = null, $attributes = array())
  {
    $allowed_attributes = array("style:auto-update", "style:class", "style:data-style-name", "style:default-outline-level", "style:display-name", "style:family", "style:list-level", "style:list-style-name", "style:master-page-name", "style:name", "style:next-style-name", "style:parent-style-name", "style:percentage-data-style-name");

    $style = self::createElement(ODF_Node::style, $content);
    self::setAttributes($image, $attributes, $allowed_attributes);

    return $style;
  }

  /**
   * Creates a ParagraphProperties Element.
   * 
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createParagraphProperties($content = null, $attributes = array())
  {
    $allowed_attributes = array("fo:background-color", "fo:border", "fo:border-bottom", "fo:border-left", "fo:border-right", "fo:border-top", "fo:break-after", "fo:break-before", "fo:hyphenation-keep", "fo:hyphenation-ladder-count", "fo:keep-together", "fo:keep-with-next", "fo:line-height", "fo:margin", "fo:margin-bottom", "fo:margin-left", "fo:margin-right", "fo:margin-top", "fo:orphans", "fo:padding", "fo:padding-bottom", "fo:padding-left", "fo:padding-right", "fo:padding-top", "fo:text-align", "fo:text-align-last", "fo:text-indent", "fo:widows", "style:auto-text-indent", "style:background-transparency", "style:border-line-width", "style:border-line-width-bottom", "style:border-line-width-left", "style:border-line-width-right", "style:border-line-width-top", "style:font-independent-line-spacing", "style:join-border", "style:justify-single-word", "style:line-break", "style:line-height-at-least", "style:line-spacing", "style:page-number", "style:punctuation-wrap", "style:register-true", "style:shadow", "style:snap-to-layout-grid", "style:tab-stop-distance", "style:text-autospace", "style:vertical-align", "style:writing-mode", "style:writing-mode-automatic", "text:line-number", "text:number-lines");

    $props = self::createElement(ODF_Node::paragraph_properties, $content);
    self::setAttributes($props, $attributes, $allowed_attributes);

    return $props;
  }

  /**
   * Creates a TextProperties Element
   *
   * @param Mixed $content
   * @param Array $attributes
   *
   * @return DOMElement
   */
  public static function createTextProperties($content = null, $attributes = array())
  {
    $allowed_attributes = array("fo:background-color", "fo:color", "fo:country", "fo:font-family", "fo:font-size", "fo:font-style", "fo:font-variant", "fo:font-weight", "fo:hyphenate", "fo:hyphenation-push-char-count", "fo:hyphenation-remain-char-count", "fo:language", "fo:letter-spacing", "fo:script", "fo:text-shadow", "fo:text-transform", "style:country-asian", "style:country-complex", "style:font-charset", "style:font-charset-asian", "style:font-charset-complex", "style:font-family-asian", "style:font-family-complex", "style:font-family-generic", "style:font-family-generic-asian", "style:font-family-generic-complex", "style:font-name", "style:font-name-asian", "style:font-name-complex", "style:font-pitch", "style:font-pitch-asian", "style:font-pitch-complex", "style:font-relief", "style:font-size-asian", "style:font-size-complex", "style:font-size-rel", "style:font-size-rel-asian", "style:font-size-rel-complex", "style:font-style-asian", "style:font-style-complex", "style:font-style-name", "style:font-style-name-asian", "style:font-style-name-complex", "style:font-weight-asian", "style:font-weight-complex", "style:language-asian", "style:language-complex", "style:letter-kerning", "style:rfc-language-tag", "style:rfc-language-tag-asian", "style:rfc-language-tag-complex", "style:script-asian", "style:script-complex", "style:script-type", "style:text-blinking", "style:text-combine", "style:text-combine-end-char", "style:text-combine-start-char", "style:text-emphasize", "style:text-line-through-color", "style:text-line-through-mode", "style:text-line-through-style", "style:text-line-through-text", "style:text-line-through-text-style", "style:text-line-through-type", "style:text-line-through-width", "style:text-outline", "style:text-overline-color", "style:text-overline-mode", "style:text-overline-style", "style:text-overline-type", "style:text-overline-width", "style:text-position", "style:text-rotation-angle", "style:text-rotation-scale", "style:text-scale", "style:text-underline-color", "style:text-underline-mode", "style:text-underline-style", "style:text-underline-type", "style:text-underline-width", "style:use-window-font-color", "text:condition", "text:display");

    $props = self::createElement(ODF_Node::text_properties, $content);
    self::setAttributes($props, $attributes, $allowed_attributes);

    return $props;
  }
}