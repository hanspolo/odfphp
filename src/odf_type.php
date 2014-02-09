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
  const numbered_p = "text:numbered-paragraph";

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

  const about = "xhtml:about";
  const content = "xhtml:content";
  const datatype = "xhtml:datatype";
  const property = "xhtml:property";

  const class_names = "text:class-names";
  const cond_style_name = "text:cond-style-name";
  const continue_list = "text:continue-list";
  const continue_numbering = "text:continue-numbering";
  const list_id = "text:list-id";
  const master_page_name = "text:master-page-name";
  const start_value = "text:start-value";
  const style_name = "text:style-name";
  const style_override = "text:style-override";
  const text_id = "text:id";
  const text_level = "text:level";
}