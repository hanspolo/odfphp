<?php

/**
 * Collection of ODF Nodetypes.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF_Node
{
  const body = "office:body";
  const text = "office:text";

  const h = "text:h";
  const p = "text:p";
  const list_body = "text:list";
  const list_header = "text:list-header";
  const list_item = "text:list-item";
  const line_break = "text:line-break";

  const table = "table:table";
  const table_row = "table:table-row";
  const table_column = "table:table-column";
  const table_cell = "table:table-cell";

  const frame = "draw:frame";
  const image = "draw:image";
}

/**
 * Collection of ODF Attributetypes.
 *
 * @author Philipp Hirsch <itself@hanspolo.net>
 * @license http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @package ODF-PHP
 * @version 0.1
 */
class ODF_Attribute
{
  const visibility = "office:visibility";

  const href = "xlink:href";

  const image_width = "svg:width";
  const image_height = "svg:height";

  const id = "xml:id";

  const continue_list = "text:continue-list";
  const continue_numbering = "text:continue-numbering";
  const start_value = "text:start-value";
  const style_name = "text:style-name";
  const style_override = "text:style-override";
}