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
	 * FF Matrix Tag Field Data hook
	 *
	 * Check to see if this matrix includes an FFM Hierarchy cell,
	 * and if so, return the restructured field data
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
				return $this->_structure_data($field_data, $col_id);
			}
		}
	}

	/**
	 * Structure Data
	 *
	 * Restructures FF Matrix's field data,
	 * taking the hierarchy into account
	 *
	 * @access private
	 */
	function _structure_data(&$field_data, $indent_cell, $indent = -1)
	{
		$children = array();
		while($field_data)
		{
			if ($indent == -1 || $field_data[0][$indent_cell] == $indent+1)
			{
				$child = array_shift($field_data);
				$grandchildren = $this->_structure_data($field_data, $indent_cell, $indent+1);
				$child[$indent_cell] = $grandchildren ? $grandchildren : NULL;
				$children[] = $child;
			}
			else if ($field_data[0][$indent_cell] <= $indent)
			{
				break;
			}
		}
		return $children;
	}

}

/* End of file ft.ffm_hierarchy.php */
/* Location: ./system/fieldtypes/ffm_hierarchy/ft.ffm_hierarchy.php */