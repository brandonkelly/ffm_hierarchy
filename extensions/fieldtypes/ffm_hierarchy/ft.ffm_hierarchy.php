<?php

if ( ! defined('EXT')) exit('Invalid file request');


/**
 * FFM Hierarchy Class
 *
 * @package   FF Matrix Expansion Pack
 * @author    Brandon Kelly <me@brandon-kelly.com>
 * @copyright Copyright (c) 2009 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */
class Ffm_hierarchy extends Fieldframe_Fieldtype {

	var $info = array(
		'name'     => 'FFM Hierarchy',
		'version'  => '0.0.2',
		'desc'     => 'Adds hierarchical nesting to your FF Matrix fields',
		'docs_url' => 'http://brandon-kelly.com/apps/ffm-pack/docs/hierarchy'
	);

	var $hooks = array(
		'ff_matrix_tag_field_data'
	);

	/**
	 * Display Cell
	 * 
	 * @param  string  $cell_name      The cell's name
	 * @param  mixed   $cell_data      The cell's current value
	 * @param  array   $cell_settings  The cell's settings
	 * @return string  The cell's HTML
	 */
	function display_cell($cell_name, $cell_data, $cell_settings)
	{
		$this->include_css('styles/ffm_hierarchy.css');
		$this->include_js('scripts/jquery.ffm_hierarchy.js');
		$this->insert_js('PATH_CP_IMG="'.PATH_CP_IMG.'";');

		$indent = $cell_data === '' ? '-1' : $cell_data;

		return '<a class="outdent"></a><a class="indent"></a>'
		     . '<input type="hidden" name="'.$cell_name.'" value="'.$indent.'"/>';
	}

	/**
	 * FF Matrix Tag Field Data Hook
	 *
	 * @param  array   $params          Name/value pairs from the opening tag
	 * @param  string  $tagdata         Chunk of tagdata between field tag pairs
	 * @param  string  $field_data      Currently saved field value
	 * @param  array   $field_settings  The field's settings
	 * @return string  Modified $tagdata
	 */
	function ff_matrix_tag_field_data($params, $tagdata, $field_data, $field_settings)
	{
		global $FF;
		foreach($field_settings['cols'] as $col_id=>$col)
		{
			if ($col['type'] == 'ffm_hierarchy')
			{
				$FF->log($this->get_children($field_data, $col_id));
			}
		}
	}

	function get_children(&$field_data, $indent_cell, $index=0, $indent=-1)
	{
		$children = array();
		for ($index; isset($field_data[$index]); $index++)
		{
			if ($field_data[$index][$indent_cell] == $indent+1)
			{
				$child = $field_data[$index];
				$child['children'] = $this->get_children($field_data, $indent_cell, $index, $indent+1);
				$children[] = $child;
			}
			else if ($field_data[$indent_cell] <= $indent)
			{
				break;
			}
		}
		return $children;
	}

}

/* End of file ft.ffm_hierarchy.php */
/* Location: ./system/fieldtypes/ffm_hierarchy/ft.ffm_hierarchy.php */