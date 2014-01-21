<?php

include_once __DIR__ . "/odf_type.php";

/**
 * Mainclass of the ODF-PHP package.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF
{
  public $content;
  public $settings;
  public $styles;
  public $meta;

  public $manifest;
  public $mimetype;
  public $meta_manifest;

  /**
   * Loads an existing file from disk.
   *
   * @param String $path
   */
  public function open($path)
  {
    $zip = new ZipArchive();

    $result = $zip->open($path);

    if ($result !== true)
      throw new \Exception("Can't open file $path");

    $this->content = $this->parse($zip->getStream("content.xml"));
    $this->settings = $this->parse($zip->getStream("settings.xml"));
    $this->styles = $this->parse($zip->getStream("styles.xml"));
    $this->meta = $this->parse($zip->getStream("meta.xml"));

    $this->manifest = $this->read($zip->getStream("manifest.rdf"));
    $this->mimetype = $this->read($zip->getStream("mimetype"));
    $this->meta_manifest = $this->read($zip->getStream("META-INF/manifest.xml"));

    $zip->close();
  }

  /**
   * Opens an empty file.
   *
   * @param String $type
   */
  public function create($type)
  {
    try {
      switch ($type)
	{
	case "text": 
	  $this->open(__DIR__ . "/data/text.odt");
	  break;
	case "spreadsheet":
	  $this->open(__DIR__ . "/data/spreadsheet.ods");
	  break;
	default:
	  throw new Exception("Unknown data type");
	  break;
	}
    } catch (Exception $e) {
      throw new Exception("Can't create file");
    }
  }

  /**
   * Saves the file to the disk.
   *
   * @param String $path
   */
  public function save($path)
  {
    $zip = new ZipArchive();
    $success = $zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true;

    if (!$success)
      throw new \Exception("Can't use $path for saving");

    $success &= $zip->addFromString("content.xml", $this->content->saveXML());
    $success &= $zip->addFromString("settings.xml", $this->settings->saveXML());
    $success &= $zip->addFromString("styles.xml", $this->styles->saveXML());
    $success &= $zip->addFromString("meta.xml", $this->meta->saveXML());

    $success &= $zip->addFromString("manifest.rdf", $this->manifest);
    $success &= $zip->addFromString("mimetype", $this->mimetype);

    $success &= $zip->addFromString("META-INF/manifest.xml", $this->meta_manifest);

    $success &= $zip->close();

    if (!$success)
      throw new \Exception("Can't save file $path");
  }

  /**
   * Reads from file and parses XML.
   *
   * @param resource $handle
   *
   * @return DOMDocument
   */
  private function parse($handle)
  {
    $content = "";
    while ($buffer = fgets($handle))
      $content .= $buffer;

    $doc = new DOMDocument();
    $doc->loadXML($content);
    return $doc;
  }

  /**
   * Reads from file and returns it unparsed.
   *
   * @param resource $handle
   *
   * @return String
   */
  private function read($handle)
  {
    $content = "";
    while ($buffer = fgets($handle))
      $content .= $buffer;
    return $content;
  }
}