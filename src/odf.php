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
  public $others;

  /**
   * Constructor of the ODF class.
   */
  public function __construct()
  {
    $others = array();
  }

  /**
   * Loads an existing file from disk.
   *
   * @param String $path
   *
   * @throws Exception
   */
  public function open($path)
  {
    $zip = new ZipArchive();

    if ($zip->open($path) !== true)
      throw new Exception("Can't open file $path");

    for ($i = 0; $i < $zip->numFiles; $i++)
      {
	$name = $zip->getNameIndex($i);

	switch ($name)
	  {
	  case "content.xml":
	    $this->content = $this->parse($zip->getStream($name));
	    break;
	  case "meta.xml":
	    $this->meta = $this->parse($zip->getStream($name));
	    break;
	  case "settings.xml":
	    $this->settings = $this->parse($zip->getStream($name));
	    break;
	  case "styles.xml":
	    $this->styles = $this->parse($zip->getStream($name));
	    break;
	  case "manifest.rdf":
	    $this->manifest = $this->read($zip->getStream($name));
	    break;
	  case "mimetype":
	    $this->mimetype = $this->read($zip->getStream($name));
	    break;
	  case "META-INF/manifest.xml":
	    $this->meta_manifest = $this->parse($zip->getStream($name));
	    break;
	  default:
	    $this->others[$name] = $this->read($zip->getStream($name));
	    break;
	  }
      }

    $zip->close();
  }

  /**
   * Opens an empty file.
   *
   * @param String $type
   *
   * @throws Exception
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
   *
   * @throws Exception
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
    $success &= $zip->addFromString("META-INF/manifest.xml", $this->meta_manifest->saveXML());

    foreach ($this->others as $name => $data)
      $success &= $zip->addFromString($name, $data);

    $success &= $zip->close();

    if (!$success)
      throw new Exception("Can't save file $path");
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

  /**
   * Adds a Picture to the archive.
   *
   * @param String $path
   *
   * @return String
   *   The path that is used to access the image
   *
   * @throws Exception
   */
  public function addPicture($path)
  {
    $dest = sprintf("Pictures/%s", basename($path));

    if (!file_exists($path))
      throw new Exception("File '$path' doesn't exist");

    // Add image to Pictures/
    $handle = fopen($path, "r");
    $this->others[$dest] = $this->read($handle);

    // Add image to META-INF/manifest.xml
    $entry = $this->meta_manifest->createElement("manifest:file-entry");
    $entry->setAttribute("manifest:full-path", $dest);
    $entry->setAttribute("manifest:media-type", mime_content_type($path));
    $this->meta_manifest->getElementsByTagName("manifest")->item(0)->appendChild($entry);

    return $dest;
  }
}